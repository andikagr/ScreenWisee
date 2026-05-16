@extends('layouts.app')
@section('title', 'Detail Siswa')
@section('page-title', 'Detail: ' . $siswa->name)
@section('content')

<div class="stats-grid" style="grid-template-columns:repeat(auto-fit,minmax(200px,1fr))">
    <div class="stat-card fade-up">
        <div class="stat-icon blue float-anim">👤</div>
        <div class="stat-value" style="font-size:18px">{{ $siswa->name }}</div>
        <div class="stat-label">Kelas: {{ $siswa->kelas ?? '-' }}</div>
    </div>
    <div class="stat-card fade-up" style="animation-delay:0.1s">
        <div class="stat-icon green bounce-anim">📊</div>
        <div class="stat-value">{{ $trackings->count() }} hari</div>
        <div class="stat-label">Total Jurnal Diisi</div>
    </div>
    <div class="stat-card fade-up" style="animation-delay:0.2s">
        <div class="stat-icon {{ $pretest ? 'green' : 'yellow' }} pulse-ring">📝</div>
        <div class="stat-value">{{ $pretest ? $pretest->avg_screen_time . ' jam' : 'Belum' }}</div>
        <div class="stat-label">Misi Awal (Pre-Test)</div>
    </div>
    <div class="stat-card fade-up" style="animation-delay:0.3s">
        <div class="stat-icon {{ $posttest ? 'green' : 'yellow' }} pulse-ring">✅</div>
        <div class="stat-value">{{ $posttest ? $posttest->avg_screen_time . ' jam' : 'Belum' }}</div>
        <div class="stat-label">Misi Akhir (Post-Test)</div>
    </div>
</div>

<div style="display:grid;grid-template-columns:1fr 1fr;gap:24px;margin-bottom:32px">
    <div class="card fade-up">
        <div class="card-header"><h3 style="color:var(--primary-600);font-size:18px">📈 Grafik Waktu Main HP</h3></div>
        <div class="card-body"><div class="chart-container"><canvas id="studentChart"></canvas></div></div>
    </div>
    <div class="card fade-up" style="animation-delay:0.1s">
        <div class="card-header"><h3 style="color:var(--accent-pink-dark);font-size:18px">📊 Distribusi Aktivitas</h3></div>
        <div class="card-body"><div class="chart-container"><canvas id="activityChart"></canvas></div></div>
    </div>
</div>

<div class="card fade-up" style="border:4px solid var(--accent-yellow);border-radius:32px">
    <div class="card-header" style="background:var(--accent-yellow);border-bottom:none; display: flex; justify-content: space-between; align-items: center;">
        <h3 style="color:var(--dark-900);font-size:20px; margin: 0;">📋 Riwayat Jurnal Siswa</h3>
        <a href="{{ route('export.student.pdf', $siswa->id) }}" class="btn btn-sm pulse-ring" style="background: var(--accent-pink-dark); color: white; border: none; box-shadow: 0 4px 0 #be185d;">📄 Export PDF</a>
    </div>
    <div class="card-body">
        <div class="table-container" style="border-radius:24px;border:3px solid var(--primary-100)">
            <table style="width:100%">
                <thead style="background:var(--primary-50)"><tr><th>Tanggal</th><th>Screen Time</th><th>Aktivitas</th><th>Bukti Screenshot</th></tr></thead>
                <tbody>
                    @foreach($trackings as $t)
                    <tr>
                        <td><strong style="color:var(--dark-800)">{{ $t->tracking_date->format('d M Y') }}</strong></td>
                        <td><span class="badge {{ $t->screen_time_hours <= 3 ? 'badge-green' : ($t->screen_time_hours <= 6 ? 'badge-yellow' : 'badge-red') }}">{{ $t->screen_time_hours }} jam</span></td>
                        <td>@if($t->activities) <div style="display:flex;flex-wrap:wrap;gap:4px"> @foreach($t->activities as $k=>$v)@if($v>0)<span class="badge" style="background:var(--dark-100);color:var(--dark-800)">{{ ucfirst($k) }}:{{ $v }}j</span>@endif @endforeach </div> @else - @endif</td>
                        <td>@if($t->screenshot_path)<a href="{{ asset('storage/'.$t->screenshot_path) }}" target="_blank" class="btn btn-sm bounce-anim" style="background:var(--accent-pink-dark);color:white;border:none">📷 Lihat</a>@else - @endif</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<div style="margin-top:24px"><a href="{{ route('guru.dashboard') }}" class="btn btn-lg" style="background:var(--dark-100);color:var(--dark-800);box-shadow:0 8px 0 var(--dark-200)">← Kembali ke Dashboard</a></div>
@endsection

@push('scripts')
<script>
const dates = {!! json_encode($trackings->pluck('tracking_date')->map(fn($d) => \Carbon\Carbon::parse($d)->format('d/m'))) !!};
const screenData = {!! json_encode($trackings->pluck('screen_time_hours')) !!};

new Chart(document.getElementById('studentChart'), {
    type: 'line',
    data: { labels: dates, datasets: [{ label: 'Screen Time', data: screenData, borderColor: '#10b981', backgroundColor: 'rgba(16,185,129,0.1)', fill: true, tension: 0.4, pointRadius: 5 }] },
    options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true } } }
});

@php
$totals = ['sosmed'=>0,'game'=>0,'belajar'=>0,'lainnya'=>0];
foreach($trackings as $t) { if($t->activities) { foreach($t->activities as $k=>$v) { $totals[$k] = ($totals[$k] ?? 0) + $v; } } }
@endphp
new Chart(document.getElementById('activityChart'), {
    type: 'doughnut',
    data: { labels: ['Sosmed','Game','Belajar','Lainnya'], datasets: [{ data: [{{ $totals['sosmed'] }},{{ $totals['game'] }},{{ $totals['belajar'] }},{{ $totals['lainnya'] }}], backgroundColor: ['#f59e0b','#ef4444','#10b981','#3b82f6'] }] },
    options: { responsive: true, maintainAspectRatio: false }
});
</script>
@endpush
