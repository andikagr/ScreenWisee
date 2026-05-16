<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Jurnal Siswa - {{ $siswa->name }}</title>
    <style>
        body { font-family: 'Helvetica', 'Arial', sans-serif; font-size: 14px; color: #333; line-height: 1.5; }
        .header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #10b981; padding-bottom: 10px; }
        .header h1 { margin: 0; color: #065f46; font-size: 24px; }
        .header p { margin: 5px 0 0; color: #6b7280; font-size: 14px; }
        .info-table { width: 100%; margin-bottom: 30px; border-collapse: collapse; }
        .info-table th { text-align: left; background: #ecfdf5; padding: 10px; border: 1px solid #a7f3d0; width: 25%; }
        .info-table td { padding: 10px; border: 1px solid #a7f3d0; }
        .data-table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .data-table th { background-color: #10b981; color: white; padding: 10px; border: 1px solid #059669; text-align: left; }
        .data-table td { padding: 10px; border: 1px solid #d1fae5; vertical-align: top; }
        .screenshot { max-width: 150px; max-height: 150px; border: 1px solid #ccc; border-radius: 4px; }
        .badge { display: inline-block; padding: 3px 8px; border-radius: 12px; font-size: 11px; background: #e5e7eb; margin: 2px; }
        .badge-green { background: #d1fae5; color: #065f46; }
        .badge-yellow { background: #fef08a; color: #854d0e; }
        .badge-red { background: #fecaca; color: #991b1b; }
        .page-break { page-break-after: always; }
    </style>
</head>
<body>

    <div class="header">
        <h1>Laporan Jurnal Digital: {{ $siswa->name }}</h1>
        <p>ScreenWise Tracking System - Dicetak pada {{ \Carbon\Carbon::now()->format('d M Y H:i') }}</p>
    </div>

    <table class="info-table">
        <tr>
            <th>Nama Lengkap</th>
            <td>{{ $siswa->name }}</td>
            <th>Kelas</th>
            <td>{{ $siswa->kelas ?? '-' }}</td>
        </tr>
        <tr>
            <th>Email</th>
            <td>{{ $siswa->email }}</td>
            <th>Guru Pendamping</th>
            <td>{{ $siswa->guru->name ?? '-' }}</td>
        </tr>
        <tr>
            <th>Pre-Test (Misi Awal)</th>
            <td>{{ $pretest ? $pretest->avg_screen_time . ' jam' : 'Belum Mengisi' }}</td>
            <th>Post-Test (Misi Akhir)</th>
            <td>{{ $posttest ? $posttest->avg_screen_time . ' jam' : 'Belum Mengisi' }}</td>
        </tr>
    </table>

    <h2 style="color: #065f46; font-size: 18px; border-bottom: 1px solid #10b981; padding-bottom: 5px;">Riwayat Jurnal Harian</h2>
    
    @if($trackings->count() > 0)
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 15%">Tanggal</th>
                <th style="width: 15%">Screen Time</th>
                <th style="width: 35%">Detail Aktivitas</th>
                <th style="width: 35%">Bukti (Screenshot)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($trackings as $t)
            <tr>
                <td><strong>{{ $t->tracking_date->format('d/m/Y') }}</strong></td>
                <td>
                    <span class="badge {{ $t->screen_time_hours <= 3 ? 'badge-green' : ($t->screen_time_hours <= 6 ? 'badge-yellow' : 'badge-red') }}">
                        {{ $t->screen_time_hours }} jam
                    </span>
                </td>
                <td>
                    @if($t->activities)
                        @foreach($t->activities as $k => $v)
                            @if($v > 0)
                                <span class="badge">{{ ucfirst($k) }}: {{ $v }}j</span>
                            @endif
                        @endforeach
                    @else
                        -
                    @endif
                    <br><br>
                    <strong>Cerita hari ini:</strong><br>
                    <span style="font-size: 12px; color: #4b5563;">{{ $t->journal_text ?? '-' }}</span>
                </td>
                <td style="text-align: center;">
                    @if(isset($t->base64_image))
                        <img src="{{ $t->base64_image }}" class="screenshot" alt="Screenshot">
                    @else
                        <span style="color: #9ca3af; font-size: 12px; font-style: italic;">Tidak ada lampiran</span>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @else
    <p style="text-align: center; color: #6b7280; padding: 20px; border: 1px dashed #ccc;">Siswa belum memiliki riwayat jurnal.</p>
    @endif

</body>
</html>
