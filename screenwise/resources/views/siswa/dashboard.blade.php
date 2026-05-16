@extends('layouts.app')
@section('title', 'Dashboard Siswa')
@section('page-title', 'Dashboard Siswa')
@section('content')

{{-- ONBOARDING MODAL --}}
@if($showOnboarding)
<div class="onboarding-overlay" id="onboardingOverlay">
    <div class="onboarding-modal fade-up">
        <div style="text-align:center;margin-bottom:24px">
            <img src="{{ asset('images/logo.svg') }}" alt="ScreenWise" style="width:100px;height:100px;margin-bottom:16px" class="bounce-anim">
            <h2 style="font-size:32px;font-weight:900;color:var(--primary-600)">Halo Jagoan! 🎉</h2>
            <p style="color:var(--dark-500);margin-top:8px;font-size:16px;font-weight:600">Yuk mulai misi pertamamu bareng ScreenWise!</p>
        </div>
        <div class="onboarding-steps">
            <div class="onboarding-step fade-up" style="animation-delay:0.1s">
                <div class="onboarding-step-icon float-anim">📝</div>
                <div><strong style="color:var(--dark-800);font-size:16px">Misi 1: Cerita Dulu Yuk!</strong><br><span style="font-size:14px;color:var(--dark-500)">Isi Pre-Test buat kasih tau kebiasaan main HP-mu sekarang.</span></div>
            </div>
            <div class="onboarding-step fade-up" style="animation-delay:0.2s">
                <div class="onboarding-step-icon float-anim">📊</div>
                <div><strong style="color:var(--dark-800);font-size:16px">Misi 2: Jurnal Harian</strong><br><span style="font-size:14px;color:var(--dark-500)">Catat waktumu setiap hari selama seminggu penuh.</span></div>
            </div>
            <div class="onboarding-step fade-up" style="animation-delay:0.3s">
                <div class="onboarding-step-icon float-anim">🎯</div>
                <div><strong style="color:var(--dark-800);font-size:16px">Misi 3: Ikut Tantangan Seru</strong><br><span style="font-size:14px;color:var(--dark-500)">Berani kurangi screen time? Selesaikan tantangannya!</span></div>
            </div>
            <div class="onboarding-step fade-up" style="animation-delay:0.4s">
                <div class="onboarding-step-icon float-anim">🏆</div>
                <div><strong style="color:var(--dark-800);font-size:16px">Misi 4: Buktikan Kamu Hebat!</strong><br><span style="font-size:14px;color:var(--dark-500)">Isi Post-Test buat pamerin perbaikanmu.</span></div>
            </div>
        </div>
        <div style="display:flex;gap:16px;margin-top:32px">
            <a href="{{ route('siswa.pretest') }}" class="btn btn-lg" style="background:var(--accent-yellow);color:var(--dark-900);flex:1;justify-content:center;box-shadow:0 8px 0 var(--accent-yellow-dark)">🚀 Mulai Misi 1!</a>
            <button onclick="document.getElementById('onboardingOverlay').style.display='none'" class="btn btn-secondary btn-lg" style="flex:1;justify-content:center">Nanti Aja Deh</button>
        </div>
    </div>
</div>
@endif

{{-- NOTIFICATIONS --}}
@foreach($notifications as $notif)
<div class="alert alert-{{ $notif['type'] }}" style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:8px">
    <span>{{ $notif['msg'] }}</span>
    @if(isset($notif['action']))
    <a href="{{ $notif['action'] }}" class="btn btn-sm btn-primary">{{ $notif['btn'] }}</a>
    @endif
</div>
@endforeach

{{-- Challenge Hari Ini --}}
@if($todayChallenge)
<div class="challenge-card fade-up">
    <div class="float-anim" style="position: absolute; right: 20px; top: -10px; font-size: 80px; opacity: 0.3;">🎮</div>
    <div class="challenge-badge">Tantangan Hari ke-{{ $todayChallenge->day_number }}</div>
    <h3>🎯 {{ $todayChallenge->title }}</h3>
    <p>{{ $todayChallenge->description }}</p>
</div>
@endif

{{-- Gamifikasi Stats --}}
<div class="stats-grid" style="grid-template-columns:repeat(auto-fit, minmax(180px, 1fr))">
    <div class="stat-card fade-up" style="animation-delay: 0.1s"><div class="stat-icon pink bounce-anim">⏰</div><div class="stat-value">{{ round($avgScreenTime, 1) }}j</div><div class="stat-label">Rata-rata Main HP</div></div>
    <div class="stat-card fade-up" style="animation-delay: 0.2s"><div class="stat-icon blue float-anim">📝</div><div class="stat-value">{{ $totalTrackings }}</div><div class="stat-label">Jurnal Diisi</div></div>
    <div class="stat-card fade-up" style="animation-delay: 0.3s"><div class="stat-icon yellow pulse-ring" style="background:var(--accent-yellow)">🔥</div><div class="stat-value">{{ $streak }}</div><div class="stat-label">Streak Apimu!</div></div>
    <div class="stat-card fade-up" style="animation-delay: 0.4s"><div class="stat-icon green bounce-anim">⭐</div><div class="stat-value">{{ $points }}</div><div class="stat-label">Poin Keren</div></div>
    <div class="stat-card fade-up" style="animation-delay: 0.5s"><div class="stat-icon blue float-anim">🏆</div><div class="stat-value">{{ count($badges) }}</div><div class="stat-label">Lencana</div></div>
</div>

{{-- Badges --}}
@if(count($badges))
<div class="card fade-up" style="margin-bottom:32px">
    <div class="card-header"><h3 class="text-accent">🏅 Koleksi Lencanamu!</h3></div>
    <div class="card-body">
        <div style="display:flex;flex-wrap:wrap;gap:16px">
            @foreach($badges as $b)
            <div class="badge-card">
                <div style="font-size:48px" class="float-anim">{{ $b['icon'] }}</div>
                <div class="badge-title">{{ $b['name'] }}</div>
                <div class="badge-desc">{{ $b['desc'] }}</div>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endif

<div style="display:grid;grid-template-columns:1fr 1fr;gap:24px;margin-bottom:32px">
    {{-- Status Hari Ini --}}
    <div class="card fade-up">
        <div class="card-header"><h3 class="text-primary">📌 Misi Hari Ini</h3></div>
        <div class="card-body">
            @if($todayTracking)
                <div style="text-align:center;padding:20px 0">
                    <div class="bounce-anim" style="font-size:64px;margin-bottom:16px">🌟</div>
                    <p style="color:var(--accent-green-dark);font-weight:900;font-size:20px;margin-bottom:8px">Yay! Jurnal hari ini udah diisi!</p>
                    <p style="font-size:16px;color:var(--dark-600);font-weight:700">Total Waktu Main HP: <span style="color:var(--primary-600);font-size:24px;font-weight:900">{{ $todayTracking->screen_time_hours }} jam</span></p>
                </div>
                @if($todayTracking->activities)
                <div style="margin-top:16px;text-align:center">
                    @foreach($todayTracking->activities as $key => $val)
                        <span class="badge badge-blue" style="margin:4px;font-size:14px;padding:8px 16px">{{ ucfirst($key) }}: {{ $val }}j</span>
                    @endforeach
                </div>
                @endif
            @else
                <div style="text-align:center;padding:20px 0">
                    <div class="float-anim" style="font-size:64px;margin-bottom:16px">📝</div>
                    <p style="color:var(--dark-600);margin-bottom:20px;font-weight:700;font-size:16px">Kamu belum isi jurnal hari ini nih. Yuk isi sekarang biar streak-mu nggak putus!</p>
                    <a href="{{ route('siswa.tracking.create') }}" class="btn btn-lg" style="background:var(--accent-yellow);color:var(--dark-900);box-shadow:0 6px 0 var(--accent-yellow-dark)">🚀 Isi Jurnal Sekarang</a>
                </div>
            @endif
        </div>
    </div>

    {{-- Chart Screen Time --}}
    <div class="card fade-up">
        <div class="card-header"><h3 class="text-primary">📈 Waktu Main HP Seminggu Ini</h3></div>
        <div class="card-body">
            <div class="chart-container"><canvas id="weeklyChart"></canvas></div>
        </div>
    </div>
</div>

{{-- Challenge Progress --}}
@if($challenges->count())
@php
    $completedCount = 0;
    if ($todayTracking && is_array($todayTracking->challenge_checklist)) {
        $completedCount = count(array_filter($todayTracking->challenge_checklist));
    }
    $progressPct = round($completedCount / $challenges->count() * 100);
@endphp
<div class="card fade-up" style="margin-bottom:32px;border:4px solid var(--accent-yellow)">
    <div class="card-header" style="background:var(--accent-yellow);border-bottom:none"><h3 style="color:var(--dark-900);font-size:20px;display:flex;justify-content:space-between;width:100%">🏆 Progres Tantanganmu <span class="badge" style="background:white;color:var(--dark-900)">{{ $completedCount }}/{{ $challenges->count() }}</span></h3></div>
    <div class="card-body">
        <div style="margin-bottom:24px">
            <div style="display:flex;justify-content:space-between;font-size:15px;font-weight:700;margin-bottom:8px">
                <span>Wow, udah {{ $progressPct }}% kelar lho!</span>
                <span>{{ $completedCount }} dari {{ $challenges->count() }} tantangan</span>
            </div>
            <div class="progress-bar" style="height:32px;border-radius:30px;background:var(--primary-50)"><div class="progress-fill" style="width:{{ $progressPct }}%;background:repeating-linear-gradient(45deg, var(--accent-yellow-dark), var(--accent-yellow-dark) 15px, var(--accent-yellow) 15px, var(--accent-yellow) 30px);border-radius:30px"></div></div>
        </div>
        <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(280px,1fr));gap:16px">
            @foreach($challenges as $ch)
            @php $isDone = $todayTracking && isset($todayTracking->challenge_checklist[$ch->id]) && $todayTracking->challenge_checklist[$ch->id]; @endphp
            <div style="padding:20px;background:{{ $isDone ? 'var(--primary-50)' : 'white' }};border-radius:24px;border:4px solid {{ $isDone ? 'var(--primary-300)' : 'var(--dark-100)' }};box-shadow:{{ $isDone ? '0 8px 0 var(--primary-200)' : '0 8px 0 var(--dark-100)' }};transition:transform .2s">
                <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:8px">
                    <div style="font-size:14px;color:{{ $isDone ? 'var(--primary-600)' : 'var(--dark-500)' }};font-weight:900;text-transform:uppercase">Hari {{ $ch->day_number }}</div>
                    @if($isDone)<span class="badge" style="background:var(--accent-green);color:var(--dark-900);font-size:14px">✅ Keren!</span>@endif
                </div>
                <div style="font-weight:800;font-size:16px;color:{{ $isDone ? 'var(--primary-700)' : 'var(--dark-800)' }}">{{ $ch->title }}</div>
                <div style="font-size:14px;color:var(--dark-500);margin-top:8px;font-weight:600">{{ Str::limit($ch->description, 80) }}</div>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endif

@endsection
@push('scripts')
<script>
const labels = {!! json_encode($weeklyTrackings->pluck('tracking_date')->map(fn($d) => \Carbon\Carbon::parse($d)->format('d/m'))) !!};
const data = {!! json_encode($weeklyTrackings->pluck('screen_time_hours')) !!};
new Chart(document.getElementById('weeklyChart'), {
    type: 'line',
    data: { labels, datasets: [{ label: 'Waktu Main HP (jam)', data, borderColor: '#0ea5e9', backgroundColor: 'rgba(14, 165, 233, 0.2)', fill: true, tension: 0.5, pointBackgroundColor: '#0284c7', pointRadius: 6, pointHoverRadius: 10, borderWidth: 4 }] },
    options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true, title: { display: true, text: 'Jam' } } } }
});
</script>
@endpush
