@extends('layouts.app')
@section('title', 'Dashboard Admin')
@section('page-title', 'Dashboard Admin')
@section('content')

<div class="stats-grid" style="grid-template-columns:repeat(auto-fit, minmax(200px, 1fr))">
    <div class="stat-card fade-up"><div class="stat-icon green float-anim"><i data-lucide="users"></i></div><div class="stat-value">{{ $totalSiswa }}</div><div class="stat-label">Total Murid</div></div>
    <div class="stat-card fade-up" style="animation-delay:0.1s"><div class="stat-icon blue bounce-anim"><i data-lucide="graduation-cap"></i></div><div class="stat-value">{{ $totalGuru }}</div><div class="stat-label">Total Guru</div></div>
    <div class="stat-card fade-up" style="animation-delay:0.2s"><div class="stat-icon yellow float-anim"><i data-lucide="bar-chart-2"></i></div><div class="stat-value">{{ round($overallAvgScreenTime, 1) }}j</div><div class="stat-label">Rata-rata Main HP</div></div>
    <div class="stat-card fade-up" style="animation-delay:0.3s"><div class="stat-icon red pulse-ring"><i data-lucide="alert-triangle"></i></div><div class="stat-value">{{ $siswaNotTrackedToday }}</div><div class="stat-label">Belum Ngisi Jurnal</div></div>
</div>

<div class="dashboard-grid-2">
    <div class="card fade-up">
        <div class="card-header"><h3 style="color:var(--primary-600);font-size:18px;display:flex;align-items:center;gap:8px;"><i data-lucide="trending-up" style="width:20px;height:20px;"></i> Trend Waktu Main HP Seminggu</h3></div>
        <div class="card-body"><div class="chart-container"><canvas id="weeklyChart"></canvas></div></div>
    </div>
    <div class="card fade-up" style="animation-delay:0.2s">
        <div class="card-header"><h3 style="color:var(--accent-pink-dark);font-size:18px;display:flex;align-items:center;gap:8px;"><i data-lucide="bar-chart-2" style="width:20px;height:20px;"></i> Pre-Test vs Post-Test</h3></div>
        <div class="card-body"><div class="chart-container"><canvas id="compChart"></canvas></div></div>
    </div>
</div>

{{-- Global Leaderboard --}}
@if(isset($leaderboard) && $leaderboard->count() > 0)
<div class="card fade-up" style="margin-bottom:24px; border:3px solid var(--accent-purple); border-radius:24px">
    <div class="card-header" style="background:var(--primary-50); border-bottom:none">
        <h3 style="color:var(--primary-700); font-size:18px; display:flex; align-items:center; gap:8px;">
            <i data-lucide="trophy" style="width:20px;height:20px;color:var(--accent-purple-dark)"></i> Top 10 Screen Time Terendah (Global)
        </h3>
    </div>
    <div class="card-body" style="padding:16px">
        <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(300px, 1fr)); gap:12px;">
            @foreach($leaderboard as $index => $student)
                <div style="display:flex; justify-content:space-between; align-items:center; padding:12px 16px; border-radius:16px; background:white; border:2px solid var(--dark-100);">
                    <div style="display:flex; align-items:center; gap:12px;">
                        <div style="width:32px; height:32px; border-radius:50%; background:{{ $index === 0 ? '#fbbf24' : ($index === 1 ? '#94a3b8' : ($index === 2 ? '#b45309' : 'var(--dark-200)')) }}; color:white; display:flex; align-items:center; justify-content:center; font-weight:900; font-size:14px;">
                            {{ $index + 1 }}
                        </div>
                        <div style="font-weight:800; color:var(--dark-800); font-size:15px">
                            {{ $student->name }}
                        </div>
                    </div>
                    <div style="font-weight:900; color:var(--primary-600); font-size:15px">
                        {{ number_format($student->avg_screen_time, 1) }} jam
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endif

<div class="stats-grid">
    <div class="card fade-up" style="border:4px solid var(--accent-yellow)">
        <div class="card-header" style="background:var(--accent-yellow);border-bottom:none"><h3 style="color:var(--dark-900);font-size:20px;display:flex;align-items:center;gap:8px;"><i data-lucide="list" style="width:24px;height:24px;"></i> Ringkasan Aplikasi</h3></div>
        <div class="card-body">
            <div style="display:grid;gap:12px">
                <div style="display:flex;justify-content:space-between;padding:16px;background:white;border:3px solid var(--dark-100);border-radius:20px;font-weight:600"><span>Total User Terdaftar</span><strong style="color:var(--primary-600);font-size:18px">{{ $totalUsers }}</strong></div>
                <div style="display:flex;justify-content:space-between;padding:16px;background:white;border:3px solid var(--dark-100);border-radius:20px;font-weight:600"><span>Jurnal Diisi Hari Ini</span><strong style="color:var(--primary-600);font-size:18px">{{ $trackingsToday }}</strong></div>
                <div style="display:flex;justify-content:space-between;padding:16px;background:white;border:3px solid var(--dark-100);border-radius:20px;font-weight:600"><span>Total Tantangan</span><strong style="color:var(--primary-600);font-size:18px">{{ $totalChallenges }}</strong></div>
                <div style="display:flex;justify-content:space-between;padding:16px;background:var(--primary-50);border:none;border-radius:20px;font-weight:600"><span>Rata-rata Pre-Test</span><strong style="color:var(--primary-700);font-size:18px">{{ round($avgPreScreenTime,1) }} jam</strong></div>
                <div style="display:flex;justify-content:space-between;padding:16px;background:var(--primary-50);border:none;border-radius:20px;font-weight:600"><span>Rata-rata Post-Test</span><strong style="color:var(--primary-700);font-size:18px">{{ round($avgPostScreenTime,1) }} jam</strong></div>
            </div>
        </div>
    </div>
    <div class="card fade-up" style="animation-delay:0.2s">
        <div class="card-header"><h3 style="color:var(--primary-600);font-size:20px;display:flex;align-items:center;gap:8px;"><i data-lucide="rocket" style="width:24px;height:24px;"></i> Aksi Cepat</h3></div>
        <div class="card-body" style="display:flex;flex-direction:column;justify-content:center">
            <div style="display:grid;gap:16px">
                <a href="{{ route('admin.users.create') }}" class="btn btn-lg" style="background:var(--primary-500);color:white;justify-content:center;box-shadow:0 8px 0 var(--primary-700);display:flex;align-items:center;gap:8px;"><i data-lucide="user-plus" style="width:20px;height:20px;"></i> Tambah User Baru</a>
                <a href="{{ route('admin.challenges.create') }}" class="btn btn-lg" style="background:var(--accent-pink-dark);color:white;justify-content:center;box-shadow:0 8px 0 #be185d;display:flex;align-items:center;gap:8px;"><i data-lucide="award" style="width:20px;height:20px;"></i> Tambah Tantangan</a>
                <a href="{{ route('admin.reports.index') }}" class="btn btn-lg" style="background:var(--accent-yellow);color:var(--dark-900);justify-content:center;box-shadow:0 8px 0 var(--accent-yellow-dark);display:flex;align-items:center;gap:8px;"><i data-lucide="bar-chart-2" style="width:20px;height:20px;"></i> Buka Laporan</a>
            </div>
        </div>
    </div>
</div>

@endsection
@push('scripts')
<script>
const wLabels = {!! json_encode(collect($weeklyData)->pluck('date')) !!};
const wData = {!! json_encode(collect($weeklyData)->pluck('avg')) !!};
new Chart(document.getElementById('weeklyChart'), {
    type: 'line', data: { labels: wLabels, datasets: [{ label: 'Avg Screen Time', data: wData, borderColor: '#10b981', backgroundColor: 'rgba(16,185,129,0.1)', fill: true, tension: 0.4, pointRadius: 5 }] },
    options: { responsive: true, maintainAspectRatio: false, scales: { y: { beginAtZero: true } } }
});
new Chart(document.getElementById('compChart'), {
    type: 'bar', data: { labels: ['Screen Time (jam)'], datasets: [
        { label: 'Pre-Test', data: [{{ round($avgPreScreenTime,1) }}], backgroundColor: '#f59e0b', borderRadius: 8 },
        { label: 'Post-Test', data: [{{ round($avgPostScreenTime,1) }}], backgroundColor: '#10b981', borderRadius: 8 }
    ]}, options: { responsive: true, maintainAspectRatio: false, scales: { y: { beginAtZero: true } } }
});
</script>
@endpush
