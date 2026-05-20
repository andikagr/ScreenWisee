<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Challenge;
use App\Models\DailyTracking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class TrackingController extends Controller
{
    public function create()
    {
        $user = Auth::user();
        $today = Carbon::today();

        // Cek apakah sudah tracking hari ini
        $existingTracking = DailyTracking::where('user_id', $user->id)
            ->whereDate('tracking_date', $today)
            ->first();

        // Hitung hari program berdasarkan tracking pertama user (bukan hari dalam seminggu)
        $firstTracking = DailyTracking::where('user_id', $user->id)
            ->orderBy('tracking_date')
            ->first();
        $programDay = $firstTracking
            ? Carbon::parse($firstTracking->tracking_date)->startOfDay()->diffInDays($today) + 1
            : 1;

        // Cari challenge berdasarkan hari program user
        $todayChallenge = Challenge::whereDate('challenge_date', $today)->first();
        if (!$todayChallenge) {
            $todayChallenge = Challenge::where('day_number', $programDay)->first();
            if (!$todayChallenge) {
                $maxDay = Challenge::max('day_number') ?? 7;
                $loopDay = (($programDay - 1) % $maxDay) + 1;
                $todayChallenge = Challenge::where('day_number', $loopDay)->first();
            }
        }
        // Wrap jadi collection agar view tetap bisa foreach
        $challenges = $todayChallenge ? collect([$todayChallenge]) : collect();

        return view('siswa.tracking.create', compact('existingTracking', 'challenges'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'screen_time_hours' => ['required', 'numeric', 'min:0', 'max:24'],
            'sosmed' => ['nullable', 'numeric', 'min:0'],
            'game' => ['nullable', 'numeric', 'min:0'],
            'belajar' => ['nullable', 'numeric', 'min:0'],
            'lainnya' => ['nullable', 'numeric', 'min:0'],
            'screenshot' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
            'challenge_checklist' => ['nullable', 'array'],
        ]);

        $user = Auth::user();
        $today = Carbon::today();

        $activities = [
            'sosmed' => $request->sosmed ?? 0,
            'game' => $request->game ?? 0,
            'belajar' => $request->belajar ?? 0,
            'lainnya' => $request->lainnya ?? 0,
        ];

        $screenshotPath = null;
        if ($request->hasFile('screenshot')) {
            $screenshotPath = $request->file('screenshot')->store('screenshots', 'public');
        }

        $challengeChecklist = [];
        // Hitung hari program berdasarkan tracking pertama user
        $firstTracking = DailyTracking::where('user_id', $user->id)
            ->orderBy('tracking_date')
            ->first();
        $programDay = $firstTracking
            ? Carbon::parse($firstTracking->tracking_date)->startOfDay()->diffInDays($today) + 1
            : 1;
        $todayChallenge = Challenge::whereDate('challenge_date', $today)->first();
        if (!$todayChallenge) {
            $todayChallenge = Challenge::where('day_number', $programDay)->first();
            if (!$todayChallenge) {
                $maxDay = Challenge::max('day_number') ?? 7;
                $loopDay = (($programDay - 1) % $maxDay) + 1;
                $todayChallenge = Challenge::where('day_number', $loopDay)->first();
            }
        }
        if ($todayChallenge) {
            $challengeChecklist[$todayChallenge->id] = in_array($todayChallenge->id, $request->challenge_checklist ?? []);
        }

        DailyTracking::updateOrCreate(
            ['user_id' => $user->id, 'tracking_date' => $today],
            [
                'screen_time_hours' => $request->screen_time_hours,
                'activities' => $activities,
                'challenge_checklist' => $challengeChecklist,
                'screenshot_path' => $screenshotPath,
            ]
        );

        return redirect()->route('siswa.dashboard')->with('success', 'Tracking harian berhasil disimpan! 🎉');
    }

    public function history()
    {
        $trackings = DailyTracking::where('user_id', Auth::id())
            ->orderByDesc('tracking_date')
            ->paginate(10);

        return view('siswa.tracking.history', compact('trackings'));
    }
}
