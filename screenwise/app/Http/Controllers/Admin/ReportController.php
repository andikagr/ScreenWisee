<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\DailyTracking;
use App\Models\Pretest;
use App\Models\Posttest;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportController extends Controller
{
    public function index()
    {
        $siswaList = User::where('role', 'siswa')->with(['pretest', 'posttest', 'dailyTrackings'])->get();

        $reportData = $siswaList->map(function ($siswa) {
            $preScreenTime = $siswa->pretest?->avg_screen_time ?? null;
            $postScreenTime = $siswa->posttest?->avg_screen_time ?? null;
            $avgTracking = $siswa->dailyTrackings->avg('screen_time_hours');
            $totalDays = $siswa->dailyTrackings->count();

            $change = null;
            if ($preScreenTime && $postScreenTime) {
                $change = round($postScreenTime - $preScreenTime, 1);
            }

            return (object) [
                'siswa' => $siswa,
                'preScreenTime' => $preScreenTime,
                'postScreenTime' => $postScreenTime,
                'avgTracking' => $avgTracking ? round($avgTracking, 1) : null,
                'totalDays' => $totalDays,
                'change' => $change,
            ];
        });

        // Aggregated stats
        $avgPreScreenTime = Pretest::avg('avg_screen_time') ?? 0;
        $avgPostScreenTime = Posttest::avg('avg_screen_time') ?? 0;
        $totalSiswa = User::where('role', 'siswa')->count();
        $completedBoth = User::where('role', 'siswa')
            ->whereHas('pretest')
            ->whereHas('posttest')
            ->count();

        return view('reports.index', compact(
            'reportData', 'avgPreScreenTime', 'avgPostScreenTime', 'totalSiswa', 'completedBoth'
        ));
    }

    public function exportPdf()
    {
        $siswaList = User::where('role', 'siswa')->with(['pretest', 'posttest', 'dailyTrackings'])->get();

        $reportData = $siswaList->map(function ($siswa) {
            $preScreenTime = $siswa->pretest?->avg_screen_time ?? null;
            $postScreenTime = $siswa->posttest?->avg_screen_time ?? null;
            $avgTracking = $siswa->dailyTrackings->avg('screen_time_hours');
            $totalDays = $siswa->dailyTrackings->count();

            $change = null;
            if ($preScreenTime && $postScreenTime) {
                $change = round($postScreenTime - $preScreenTime, 1);
            }

            return (object) [
                'siswa' => $siswa,
                'preScreenTime' => $preScreenTime,
                'postScreenTime' => $postScreenTime,
                'avgTracking' => $avgTracking ? round($avgTracking, 1) : null,
                'totalDays' => $totalDays,
                'change' => $change,
            ];
        });

        $avgPreScreenTime = Pretest::avg('avg_screen_time') ?? 0;
        $avgPostScreenTime = Posttest::avg('avg_screen_time') ?? 0;
        $totalSiswa = User::where('role', 'siswa')->count();
        $completedBoth = User::where('role', 'siswa')
            ->whereHas('pretest')->whereHas('posttest')->count();

        $pdf = Pdf::loadView('reports.pdf', compact(
            'reportData', 'avgPreScreenTime', 'avgPostScreenTime', 'totalSiswa', 'completedBoth'
        ));

        $pdf->setPaper('A4', 'landscape');

        return $pdf->download('laporan-screenwise-' . Carbon::now()->format('Y-m-d') . '.pdf');
    }

    public function exportStudentPdf($id)
    {
        $user = auth()->user();
        
        $siswa = User::where('id', $id)->where('role', 'siswa')->firstOrFail();
        
        // Authorization check
        if ($user->role === 'guru' && $siswa->guru_id !== $user->id) {
            abort(403, 'Anda tidak berhak melihat data siswa ini.');
        }

        $trackings = $siswa->dailyTrackings()->orderBy('tracking_date', 'asc')->get();
        $pretest = $siswa->pretest;
        $posttest = $siswa->posttest;

        // Convert images to base64 so DOMPDF can render them
        foreach ($trackings as $t) {
            if ($t->screenshot_path && \Storage::disk('public')->exists($t->screenshot_path)) {
                $path = storage_path('app/public/' . $t->screenshot_path);
                $type = pathinfo($path, PATHINFO_EXTENSION);
                $data = file_get_contents($path);
                $t->base64_image = 'data:image/' . $type . ';base64,' . base64_encode($data);
            }
        }

        $pdf = Pdf::loadView('reports.student_pdf', compact('siswa', 'trackings', 'pretest', 'posttest'));
        $pdf->setPaper('A4', 'portrait');

        return $pdf->download('laporan-jurnal-' . \Str::slug($siswa->name) . '-' . Carbon::now()->format('Y-m-d') . '.pdf');
    }
}
