<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Laporan ScreenWise</title>
    <style>
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 11px; color: #1e293b; margin: 0; padding: 20px; }
        h1 { text-align: center; color: #065f46; font-size: 22px; margin-bottom: 4px; }
        h2 { color: #047857; font-size: 14px; border-bottom: 2px solid #10b981; padding-bottom: 6px; margin-top: 24px; }
        .subtitle { text-align: center; color: #64748b; font-size: 12px; margin-bottom: 20px; }
        .stats-row { display: flex; gap: 10px; margin-bottom: 20px; }
        .stat-box { flex: 1; background: #ecfdf5; border: 1px solid #a7f3d0; border-radius: 8px; padding: 12px; text-align: center; }
        .stat-box .value { font-size: 20px; font-weight: bold; color: #065f46; }
        .stat-box .label { font-size: 10px; color: #64748b; margin-top: 4px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th { background: #065f46; color: white; padding: 8px 10px; text-align: left; font-size: 10px; text-transform: uppercase; letter-spacing: 0.5px; }
        td { padding: 7px 10px; border-bottom: 1px solid #e2e8f0; font-size: 11px; }
        tr:nth-child(even) { background: #f8fafc; }
        .badge { display: inline-block; padding: 2px 8px; border-radius: 10px; font-size: 10px; font-weight: 600; }
        .badge-green { background: #d1fae5; color: #065f46; }
        .badge-red { background: #fee2e2; color: #dc2626; }
        .badge-gray { background: #f1f5f9; color: #64748b; }
        .success-banner { background: #ecfdf5; border: 1px solid #10b981; border-radius: 8px; padding: 12px; text-align: center; margin: 16px 0; }
        .warning-banner { background: #fef3c7; border: 1px solid #f59e0b; border-radius: 8px; padding: 12px; text-align: center; margin: 16px 0; }
        .footer { text-align: center; margin-top: 30px; font-size: 10px; color: #94a3b8; border-top: 1px solid #e2e8f0; padding-top: 10px; }
    </style>
</head>
<body>
    <h1>📊 Laporan Program ScreenWise</h1>
    <p class="subtitle">Tracking System - Kebiasaan Digital Sehat Siswa SMP<br>Dicetak: {{ now()->format('d F Y, H:i') }} WIB</p>

    <table style="margin-bottom:20px">
        <tr>
            <td style="background:#ecfdf5;text-align:center;border:1px solid #a7f3d0;border-radius:8px;padding:12px;width:25%">
                <div style="font-size:20px;font-weight:bold;color:#065f46">{{ $totalSiswa }}</div>
                <div style="font-size:10px;color:#64748b">Total Siswa</div>
            </td>
            <td style="background:#ecfdf5;text-align:center;border:1px solid #a7f3d0;border-radius:8px;padding:12px;width:25%">
                <div style="font-size:20px;font-weight:bold;color:#065f46">{{ $completedBoth }}</div>
                <div style="font-size:10px;color:#64748b">Lengkap Pre+Post</div>
            </td>
            <td style="background:#fef3c7;text-align:center;border:1px solid #fde68a;border-radius:8px;padding:12px;width:25%">
                <div style="font-size:20px;font-weight:bold;color:#92400e">{{ round($avgPreScreenTime,1) }} jam</div>
                <div style="font-size:10px;color:#64748b">Avg Pre-Test</div>
            </td>
            <td style="background:#ecfdf5;text-align:center;border:1px solid #a7f3d0;border-radius:8px;padding:12px;width:25%">
                <div style="font-size:20px;font-weight:bold;color:#065f46">{{ round($avgPostScreenTime,1) }} jam</div>
                <div style="font-size:10px;color:#64748b">Avg Post-Test</div>
            </td>
        </tr>
    </table>

    @if($avgPreScreenTime > 0 && $avgPostScreenTime > 0)
        @php $change = round($avgPostScreenTime - $avgPreScreenTime, 1); @endphp
        @if($change <= 0)
        <div class="success-banner">
            🎉 <strong>Program Berhasil!</strong> Screen time rata-rata menurun sebesar <strong>{{ abs($change) }} jam</strong>
            (dari {{ round($avgPreScreenTime,1) }} jam → {{ round($avgPostScreenTime,1) }} jam)
        </div>
        @else
        <div class="warning-banner">
            ⚠️ Screen time rata-rata meningkat sebesar <strong>{{ abs($change) }} jam</strong>. Perlu evaluasi lebih lanjut.
        </div>
        @endif
    @endif

    <h2>Detail Per Siswa</h2>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Siswa</th>
                <th>Kelas</th>
                <th>Pre-Test</th>
                <th>Post-Test</th>
                <th>Perubahan</th>
                <th>Total Tracking</th>
                <th>Avg Harian</th>
            </tr>
        </thead>
        <tbody>
            @foreach($reportData as $i => $r)
            <tr>
                <td>{{ $i + 1 }}</td>
                <td><strong>{{ $r->siswa->name }}</strong></td>
                <td>{{ $r->siswa->kelas ?? '-' }}</td>
                <td>{{ $r->preScreenTime ? $r->preScreenTime . ' jam' : '-' }}</td>
                <td>{{ $r->postScreenTime ? $r->postScreenTime . ' jam' : '-' }}</td>
                <td>
                    @if($r->change !== null)
                        <span class="badge {{ $r->change <= 0 ? 'badge-green' : 'badge-red' }}">{{ $r->change > 0 ? '+' : '' }}{{ $r->change }} jam</span>
                    @else
                        <span class="badge badge-gray">-</span>
                    @endif
                </td>
                <td>{{ $r->totalDays }} hari</td>
                <td>{{ $r->avgTracking ? $r->avgTracking . ' jam' : '-' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        ScreenWise Tracking System &copy; {{ date('Y') }} — Program KKN Kebiasaan Digital Sehat
    </div>
</body>
</html>
