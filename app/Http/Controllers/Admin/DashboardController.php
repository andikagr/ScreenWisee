<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\DailyTracking;
use App\Models\Challenge;
use App\Models\Pretest;
use App\Models\Posttest;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $totalUsers = User::count();
        $totalSiswa = User::where('role', 'siswa')->count();
        $totalGuru = User::where('role', 'guru')->count();
        $totalChallenges = Challenge::count();

        $today = Carbon::today();
        $trackingsToday = DailyTracking::whereDate('tracking_date', $today)->count();
        $overallAvgScreenTime = DailyTracking::avg('screen_time_hours') ?? 0;

        // Pre-test vs Post-test aggregated
        $avgPreScreenTime = Pretest::avg('avg_screen_time') ?? 0;
        $avgPostScreenTime = Posttest::avg('avg_screen_time') ?? 0;

        // Weekly tracking trend
        $weeklyData = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = $today->copy()->subDays($i);
            $avg = DailyTracking::whereDate('tracking_date', $date)->avg('screen_time_hours') ?? 0;
            $weeklyData[] = [
                'date' => $date->format('d/m'),
                'avg' => round($avg, 1),
            ];
        }

        // Siswa yang belum tracking hari ini
        $siswaNotTrackedToday = User::where('role', 'siswa')
            ->whereDoesntHave('dailyTrackings', function ($q) use ($today) {
                $q->whereDate('tracking_date', $today);
            })
            ->count();

        // === GLOBAL LEADERBOARD ===
        $cacheKey = 'leaderboard_global';
        $leaderboard = cache()->remember($cacheKey, now()->addMinutes(10), function () {
            return User::where('role', 'siswa')
                ->withAvg('dailyTrackings as avg_screen_time', 'screen_time_hours')
                ->having('avg_screen_time', '>', 0)
                ->orderBy('avg_screen_time', 'asc')
                ->limit(10)
                ->get();
        });

        return view('admin.dashboard', compact(
            'totalUsers', 'totalSiswa', 'totalGuru', 'totalChallenges',
            'trackingsToday', 'overallAvgScreenTime',
            'avgPreScreenTime', 'avgPostScreenTime',
            'weeklyData', 'siswaNotTrackedToday', 'leaderboard'
        ));
    }
}
