<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\TestController;
use App\Http\Controllers\Siswa\DashboardController as SiswaDashboard;
use App\Http\Controllers\Siswa\TrackingController;
use App\Http\Controllers\Guru\DashboardController as GuruDashboard;
use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ChallengeController;
use App\Http\Controllers\Admin\ReportController;

Route::get('/db-test', function () {
    try {
        DB::connection()->getPdo();
        return 'DB Connected Successfully!';
    } catch (\Exception $e) {
        return 'DB Connection Failed: ' . $e->getMessage();
    }
});

Route::get('/', function () {
    if (auth()->check()) {
        $user = auth()->user();
        if ($user->isAdmin()) return redirect()->route('admin.dashboard');
        if ($user->isGuru()) return redirect()->route('guru.dashboard');
        return redirect()->route('siswa.dashboard');
    }
    return view('welcome');
})->name('home');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
    
    // Google Auth Routes
    Route::get('/auth/google', [App\Http\Controllers\Auth\GoogleController::class, 'redirectToGoogle'])->name('google.login');
    Route::get('/auth/google/callback', [App\Http\Controllers\Auth\GoogleController::class, 'handleGoogleCallback'])->name('google.callback');
});

Route::any('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware(['auth'])->group(function () {
    Route::get('/student/{id}/pdf', [ReportController::class, 'exportStudentPdf'])->name('export.student.pdf');
    Route::get('/profile', [App\Http\Controllers\ProfileController::class, 'edit'])->name('profile.edit');
    Route::post('/profile', [App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
});

Route::middleware(['auth', 'role:siswa'])->prefix('siswa')->name('siswa.')->group(function () {
    Route::get('/dashboard', [SiswaDashboard::class, 'index'])->name('dashboard');
    Route::get('/tracking/create', [TrackingController::class, 'create'])->name('tracking.create');
    Route::post('/tracking', [TrackingController::class, 'store'])->name('tracking.store');
    Route::get('/tracking/history', [TrackingController::class, 'history'])->name('tracking.history');
    Route::get('/pretest', [TestController::class, 'showPretest'])->name('pretest');
    Route::post('/pretest', [TestController::class, 'storePretest'])->name('pretest.store');
    Route::get('/posttest', [TestController::class, 'showPosttest'])->name('posttest');
    Route::post('/posttest', [TestController::class, 'storePosttest'])->name('posttest.store');
    Route::get('/comparison', [TestController::class, 'comparison'])->name('comparison');
});

Route::middleware(['auth', 'role:guru'])->prefix('guru')->name('guru.')->group(function () {
    Route::get('/dashboard', [GuruDashboard::class, 'index'])->name('dashboard');
    Route::get('/student/{id}', [GuruDashboard::class, 'studentDetail'])->name('student.detail');
});

Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboard::class, 'index'])->name('dashboard');
    Route::resource('users', UserController::class);
    Route::resource('challenges', ChallengeController::class);
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/pdf', [ReportController::class, 'exportPdf'])->name('reports.pdf');
});

// Route khusus untuk menjalankan migration di production (Vercel)
Route::get('/migrate-db-sekarang', function () {
    try {
        \Illuminate\Support\Facades\Artisan::call('migrate', ['--force' => true]);
        return '<h1>✅ MIGRATION SUKSES!</h1><pre>' . \Illuminate\Support\Facades\Artisan::output() . '</pre>';
    } catch (\Exception $e) {
        return '<h1>❌ MIGRATION GAGAL!</h1><pre>' . $e->getMessage() . '</pre>';
    }
});

Route::get('/seed-dummy-data', function () {
    try {
        $siswas = App\Models\User::where('role', 'siswa')->get();
        if ($siswas->isEmpty()) {
            return '<h1>❌ Tidak ada data siswa di database!</h1>';
        }

        $challenges = App\Models\Challenge::all();
        if ($challenges->isEmpty()) {
            return '<h1>❌ Tidak ada data challenge di database! Silakan buat challenge dulu dari menu admin.</h1>';
        }

        $count = 0;
        $allTrackings = [];
        $allPretests = [];
        $allPosttests = [];

        // Hapus data lama agar tidak tumpang tindih (Bulk Delete)
        App\Models\Pretest::truncate();
        App\Models\Posttest::truncate();
        App\Models\DailyTracking::truncate();

        foreach ($siswas as $siswa) {
            // --- GENERATE PRETEST (Kondisi Awal - Lebih Buruk) ---
            $preScreenTime = rand(60, 120) / 10; // 6.0 - 12.0 jam
            $allPretests[] = [
                'user_id' => $siswa->id,
                'avg_screen_time' => $preScreenTime,
                'sleep_time' => sprintf('%02d:00', rand(23, 25) % 24), // 23:00, 00:00, 01:00
                'wake_time' => sprintf('%02d:30', rand(5, 7)),
                'gadget_habits' => json_encode(['Bermain game sebelum tidur', 'Makan sambil main HP']),
                'notes' => 'Sering begadang dan kurang tidur',
                'created_at' => now()->subDays(8),
                'updated_at' => now()->subDays(8),
            ];

            // --- GENERATE POSTTEST (Kondisi Akhir - Lebih Baik) ---
            $postScreenTime = rand(20, 50) / 10; // 2.0 - 5.0 jam
            $allPosttests[] = [
                'user_id' => $siswa->id,
                'avg_screen_time' => $postScreenTime,
                'sleep_time' => sprintf('%02d:00', rand(21, 22)), // 21:00, 22:00
                'wake_time' => sprintf('%02d:00', rand(5, 6)),
                'gadget_habits' => json_encode(['Tidak bawa HP ke kasur']),
                'notes' => 'Lebih segar dan bisa tidur cepat',
                'created_at' => now(),
                'updated_at' => now(),
            ];

            // Tentukan "Persona" siswa secara acak untuk variasi yang realistis
            // 1 = Rajin, 2 = Biasa, 3 = Malas
            $persona = rand(1, 3);
            
            if ($persona === 1) { // Rajin
                $daysToSkip = rand(0, 1);
                $challengeProb = 90; // 90% ngerjain challenge
                $improvementRate = 1.0; // Perbaikan bagus
            } elseif ($persona === 2) { // Biasa
                $daysToSkip = rand(2, 4);
                $challengeProb = 50; // 50% ngerjain challenge
                $improvementRate = 0.5; // Perbaikan setengah-setengah
            } else { // Malas
                $daysToSkip = rand(4, 6);
                $challengeProb = 20; // Cuma 20% kemungkinan ngerjain challenge
                $improvementRate = 0.1; // Hampir ga ada perbaikan
            }

            $skippedDays = [];
            while(count($skippedDays) < $daysToSkip) {
                $randDay = rand(0, 6);
                if (!in_array($randDay, $skippedDays)) {
                    $skippedDays[] = $randDay;
                }
            }

            // Buat 7 hari tracking ke belakang
            for ($i = 6; $i >= 0; $i--) {
                if (in_array($i, $skippedDays)) {
                    continue; // Skip hari ini agar streak terputus dan kerjanya dikit
                }

                $date = \Carbon\Carbon::today()->subDays($i);
                
                // Variasi screen time tracking (berdasarkan persona)
                $expectedScreenTime = $preScreenTime - (($preScreenTime - $postScreenTime) / 6 * (6 - $i) * $improvementRate);
                $trackingScreenTime = round($expectedScreenTime + (rand(-10, 10) / 10), 1); // noise lebih besar
                if ($trackingScreenTime < 0) $trackingScreenTime = 0;
                
                // Pembagian aktivitas
                $sosmed = round($trackingScreenTime * (rand(30, 60) / 100), 1);
                $game = round($trackingScreenTime * (rand(20, 50) / 100), 1);
                $belajar = round($trackingScreenTime * (rand(0, 20) / 100), 1); // belajar dikit
                $lainnya = round($trackingScreenTime - ($sosmed + $game + $belajar), 1);
                if ($lainnya < 0) $lainnya = 0;

                // Challenge (berdasarkan probabilitas persona)
                $checklist = [];
                if (rand(1, 100) <= $challengeProb) {
                    $randomChallenge = $challenges->random();
                    $checklist = [$randomChallenge->id => true];
                }

                $allTrackings[] = [
                    'user_id' => $siswa->id,
                    'tracking_date' => $date->format('Y-m-d'),
                    'screen_time_hours' => $trackingScreenTime,
                    'activities' => json_encode([
                        'sosmed' => $sosmed,
                        'game' => $game,
                        'belajar' => $belajar,
                        'lainnya' => $lainnya,
                    ]),
                    'challenge_checklist' => json_encode($checklist),
                    'screenshot_path' => null,
                    'created_at' => now()->subDays($i),
                    'updated_at' => now()->subDays($i),
                ];
            }
            
            // Hapus cache leaderboard
            \Illuminate\Support\Facades\Cache::forget('leaderboard_guru_' . $siswa->guru_id);
            $count++;
        }

        // Lakukan Bulk Insert
        if (!empty($allPretests)) App\Models\Pretest::insert($allPretests);
        if (!empty($allPosttests)) App\Models\Posttest::insert($allPosttests);
        if (!empty($allTrackings)) App\Models\DailyTracking::insert($allTrackings);
        
        \Illuminate\Support\Facades\Cache::forget('leaderboard_global');

        return "<h1>✅ SUKSES!</h1><p>Berhasil mengisi Pre-test, Post-test, dan jurnal 7 hari terakhir untuk $count siswa secara fantastis dan sangat bervariasi.</p>";
    } catch (\Exception $e) {
        return '<h1>❌ GAGAL!</h1><pre>' . $e->getMessage() . '</pre>';
    }
});
