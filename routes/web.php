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

        foreach ($siswas as $siswa) {
            // Hapus data tracking lama untuk siswa ini
            App\Models\DailyTracking::where('user_id', $siswa->id)->delete();

            // Buat 7 hari tracking ke belakang
            for ($i = 6; $i >= 0; $i--) {
                $date = \Carbon\Carbon::today()->subDays($i);
                
                // Variasi screen time (2.0 - 8.5 jam)
                $screenTime = rand(20, 85) / 10;
                
                // Pembagian aktivitas
                $sosmed = round($screenTime * (rand(30, 50) / 100), 1);
                $game = round($screenTime * (rand(20, 40) / 100), 1);
                $belajar = round($screenTime * (rand(10, 30) / 100), 1);
                $lainnya = round($screenTime - ($sosmed + $game + $belajar), 1);
                if ($lainnya < 0) $lainnya = 0;

                // Challenge (pilih 1 challenge random)
                $randomChallenge = $challenges->random();
                $checklist = [$randomChallenge->id => true];

                $allTrackings[] = [
                    'user_id' => $siswa->id,
                    'tracking_date' => $date->format('Y-m-d'),
                    'screen_time_hours' => $screenTime,
                    'activities' => json_encode([
                        'sosmed' => $sosmed,
                        'game' => $game,
                        'belajar' => $belajar,
                        'lainnya' => $lainnya,
                    ]),
                    'challenge_checklist' => json_encode($checklist),
                    'screenshot_path' => null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
            
            // Hapus cache leaderboard
            \Illuminate\Support\Facades\Cache::forget('leaderboard_guru_' . $siswa->guru_id);
            $count++;
        }

        // Lakukan Bulk Insert
        if (!empty($allTrackings)) {
            App\Models\DailyTracking::insert($allTrackings);
        }
        
        \Illuminate\Support\Facades\Cache::forget('leaderboard_global');

        return "<h1>✅ SUKSES!</h1><p>Berhasil mengisi jurnal 7 hari terakhir untuk $count siswa dengan screen time yang bervariasi.</p>";
    } catch (\Exception $e) {
        return '<h1>❌ GAGAL!</h1><pre>' . $e->getMessage() . '</pre>';
    }
});
