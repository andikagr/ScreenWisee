<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\DailyTracking;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $guru = Auth::user();

        // Siswa yang di-assign ke guru ini ATAU yang belum di-assign ke guru manapun
        $siswaList = User::where('role', 'siswa')
            ->where(function($q) use ($guru) {
                $q->where('guru_id', $guru->id)
                  ->orWhereNull('guru_id');
            })
            ->get();
        $siswaIds = $siswaList->pluck('id');
        $today = Carbon::today();

        // Statistik per siswa
        $siswaStats = $siswaList->map(function (User $siswa) use ($today) {
            $totalTrackings = $siswa->dailyTrackings()->count();
            $todayTracking = $siswa->dailyTrackings()->whereDate('tracking_date', $today)->first();
            $avgScreenTime = $siswa->dailyTrackings()->avg('screen_time_hours') ?? 0;
            $hasPretest = $siswa->pretest()->exists();
            $hasPosttest = $siswa->posttest()->exists();

            // Challenge completion
            $completedChallenges = 0;
            $trackingsWithChallenges = $siswa->dailyTrackings()->whereNotNull('challenge_checklist')->get();
            foreach ($trackingsWithChallenges as $t) {
                if (is_array($t->challenge_checklist)) {
                    $completedChallenges += count(array_filter($t->challenge_checklist));
                }
            }

            return (object) [
                'user' => $siswa,
                'totalTrackings' => $totalTrackings,
                'todayTracking' => $todayTracking,
                'avgScreenTime' => round($avgScreenTime, 1),
                'hasPretest' => $hasPretest,
                'hasPosttest' => $hasPosttest,
                'completedChallenges' => $completedChallenges,
            ];
        });

        // Overall stats
        $totalSiswa = $siswaList->count();
        $siswaTrackingToday = $siswaList->filter(fn(User $s) => $s->dailyTrackings()->whereDate('tracking_date', $today)->exists())->count();
        $overallAvgScreenTime = DailyTracking::whereIn('user_id', $siswaIds)->avg('screen_time_hours') ?? 0;

        // Weekly trend data
        $weeklyData = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = $today->copy()->subDays($i);
            $avg = DailyTracking::whereIn('user_id', $siswaIds)
                ->whereDate('tracking_date', $date)->avg('screen_time_hours') ?? 0;
            $weeklyData[] = ['date' => $date->format('d/m'), 'avg' => round($avg, 1)];
        }

        // Kelas breakdown
        $kelasStats = $siswaList->groupBy('kelas')->map(function ($group, $kelas) {
            $ids = $group->pluck('id');
            $avg = DailyTracking::whereIn('user_id', $ids)->avg('screen_time_hours') ?? 0;
            return (object) [
                'kelas' => $kelas ?: 'Tidak ada',
                'count' => $group->count(),
                'avgScreenTime' => round($avg, 1),
            ];
        })->values();

        // Recent screenshots
        $recentScreenshots = DailyTracking::whereIn('user_id', $siswaIds)
            ->whereNotNull('screenshot_path')
            ->with('user')
            ->orderByDesc('tracking_date')
            ->limit(8)
            ->get();

        return view('guru.dashboard', compact(
            'siswaStats', 'totalSiswa', 'siswaTrackingToday', 'overallAvgScreenTime',
            'weeklyData', 'kelasStats', 'recentScreenshots'
        ));
    }

    public function studentDetail($id)
    {
        $guru = Auth::user();
        // Guru bisa lihat siswa yang di-assign ke dia atau yang belum punya guru
        $siswa = User::where('id', $id)
            ->where('role', 'siswa')
            ->where(function($q) use ($guru) {
                $q->where('guru_id', $guru->id)->orWhereNull('guru_id');
            })
            ->firstOrFail();

        $trackings = $siswa->dailyTrackings()->orderByDesc('tracking_date')->get();
        $pretest = $siswa->pretest;
        $posttest = $siswa->posttest;

        return view('guru.student-detail', compact('siswa', 'trackings', 'pretest', 'posttest'));
    }
}
