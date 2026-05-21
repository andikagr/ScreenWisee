<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Challenge;
use App\Models\DailyTracking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $today = Carbon::today();

        // Hitung hari program berdasarkan tracking pertama user (bukan hari dalam seminggu)
        $firstTracking = DailyTracking::where('user_id', $user->id)
            ->orderBy('tracking_date')
            ->first();
        $programDay = $firstTracking
            ? Carbon::parse($firstTracking->tracking_date)->startOfDay()->diffInDays($today) + 1
            : 1;

        // Challenge hari ini berdasarkan hari program user
        $todayChallenge = Challenge::whereDate('challenge_date', $today)->first();
        if (!$todayChallenge) {
            $todayChallenge = Challenge::where('day_number', $programDay)->first();
            // Jika hari program melebihi jumlah challenge, loop dari awal
            if (!$todayChallenge) {
                $maxDay = Challenge::max('day_number') ?? 7;
                $loopDay = (($programDay - 1) % $maxDay) + 1;
                $todayChallenge = Challenge::where('day_number', $loopDay)->first();
            }
        }

        // Tracking hari ini
        $todayTracking = DailyTracking::where('user_id', $user->id)
            ->whereDate('tracking_date', $today)
            ->first();

        // Tracking 7 hari terakhir untuk chart
        $weeklyTrackings = DailyTracking::where('user_id', $user->id)
            ->where('tracking_date', '>=', $today->copy()->subDays(6))
            ->orderBy('tracking_date')
            ->get();

        // Statistik
        $totalTrackings = DailyTracking::where('user_id', $user->id)->count();
        $avgScreenTime = DailyTracking::where('user_id', $user->id)->avg('screen_time_hours') ?? 0;

        // Pre-test & post-test status
        $hasPretest = $user->pretest()->exists();
        $hasPosttest = $user->posttest()->exists();

        // Challenges list
        $challenges = Challenge::orderBy('day_number')->get();

        // === GAMIFIKASI ===
        // Streak: hitung hari berturut-turut tracking
        $streak = 0;
        $allTrackings = DailyTracking::where('user_id', $user->id)
            ->orderByDesc('tracking_date')->pluck('tracking_date');
        $checkDate = $today->copy();
        foreach ($allTrackings as $td) {
            $trackDate = Carbon::parse($td)->startOfDay();
            if ($trackDate->equalTo($checkDate)) {
                $streak++;
                $checkDate->subDay();
            } elseif ($trackDate->lt($checkDate)) {
                break;
            }
        }

        // Poin: tracking=10, challenge=5, pretest=20, posttest=20, streak bonus
        $challengesDone = 0;
        $trackingsWithChallenges = DailyTracking::where('user_id', $user->id)
            ->whereNotNull('challenge_checklist')->get();
        foreach ($trackingsWithChallenges as $t) {
            if (is_array($t->challenge_checklist)) {
                $challengesDone += count(array_filter($t->challenge_checklist));
            }
        }
        $points = ($totalTrackings * 10) + ($challengesDone * 5)
            + ($hasPretest ? 20 : 0) + ($hasPosttest ? 20 : 0)
            + (min($streak, 7) * 3); // streak bonus

        // Badges
        $badges = [];
        if ($totalTrackings >= 1) $badges[] = ['icon' => '🌟', 'name' => 'Pemula', 'desc' => 'Tracking pertama'];
        if ($totalTrackings >= 7) $badges[] = ['icon' => '🔥', 'name' => '7 Hari', 'desc' => 'Tracking 7 hari'];
        if ($streak >= 3) $badges[] = ['icon' => '⚡', 'name' => 'Streak 3', 'desc' => '3 hari berturut'];
        if ($streak >= 7) $badges[] = ['icon' => '🏆', 'name' => 'Streak 7', 'desc' => 'Full week streak'];
        if ($challengesDone >= 7) $badges[] = ['icon' => '🎯', 'name' => 'Challenger', 'desc' => 'Semua challenge'];
        if ($hasPretest && $hasPosttest) $badges[] = ['icon' => '📊', 'name' => 'Evaluator', 'desc' => 'Pre + Post'];
        if ($avgScreenTime > 0 && $avgScreenTime <= 3) $badges[] = ['icon' => '💎', 'name' => 'Digital Wise', 'desc' => 'Screen time ≤3j'];

        // === NOTIFIKASI ===
        $notifications = [];
        if (!$todayTracking) {
            $notifications[] = ['type' => 'warning', 'msg' => '📊 Kamu belum mengisi tracking hari ini! Yuk isi sekarang.', 'action' => route('siswa.tracking.create'), 'btn' => 'Isi Tracking'];
        }
        if (!$hasPretest) {
            $notifications[] = ['type' => 'info', 'msg' => '📝 Kamu belum mengisi Pre-Test. Isi dulu sebelum memulai program.', 'action' => route('siswa.pretest'), 'btn' => 'Isi Pre-Test'];
        }
        if ($totalTrackings >= 7 && !$hasPosttest) {
            $notifications[] = ['type' => 'success', 'msg' => '✅ Kamu sudah tracking 7 hari! Waktunya isi Post-Test untuk evaluasi.', 'action' => route('siswa.posttest'), 'btn' => 'Isi Post-Test'];
        }
        if ($streak >= 3) {
            $notifications[] = ['type' => 'success', 'msg' => '🔥 Streak ' . $streak . ' hari! Pertahankan kebiasaan baikmu!'];
        }

        // === ONBOARDING ===
        $showOnboarding = ($totalTrackings === 0 && !$hasPretest);

        return view('siswa.dashboard', compact(
            'user', 'todayChallenge', 'todayTracking', 'weeklyTrackings',
            'totalTrackings', 'avgScreenTime', 'hasPretest', 'hasPosttest', 'challenges',
            'streak', 'points', 'badges', 'notifications', 'showOnboarding', 'programDay'
        ));
    }
}
