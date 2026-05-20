@extends('layouts.app')
@section('title', 'Dashboard')
@section('page-title', 'Dashboard')
@section('content')

{{-- ONBOARDING MODAL --}}
@if($showOnboarding)
<div class="onboarding-overlay" id="onboardingOverlay">
    <div class="onboarding-modal fade-up">
        <div style="text-align:center;margin-bottom:24px">
            <img src="{{ asset('images/logo.svg') }}" alt="ScreenWise" style="width:80px;height:80px;margin-bottom:12px" class="bounce-anim">
            <h2 style="font-size:26px;color:var(--primary-700)">Hai, {{ auth()->user()->name }}! </h2>
            <p style="color:var(--dark-500);margin-top:6px;font-size:15px;font-weight:600">Yuk mulai program ScreenWise kamu!</p>
        </div>
        <div class="onboarding-steps">
            <div class="onboarding-step fade-up" style="animation-delay:0.1s">
                <div class="onboarding-step-icon"></div>
                <div><strong style="color:var(--dark-800);font-size:15px">Langkah 1: Isi Pre-Test</strong><br><span style="font-size:13px;color:var(--dark-500)">Ceritakan kebiasaan main HP kamu sekarang.</span></div>
            </div>
            <div class="onboarding-step fade-up" style="animation-delay:0.2s">
                <div class="onboarding-step-icon"></div>
                <div><strong style="color:var(--dark-800);font-size:15px">Langkah 2: Jurnal Harian</strong><br><span style="font-size:13px;color:var(--dark-500)">Catat screen time setiap hari selama seminggu.</span></div>
            </div>
            <div class="onboarding-step fade-up" style="animation-delay:0.3s">
                <div class="onboarding-step-icon"></div>
                <div><strong style="color:var(--dark-800);font-size:15px">Langkah 3: Ikuti Tantangan</strong><br><span style="font-size:13px;color:var(--dark-500)">Selesaikan tantangan harian untuk kurangi screen time.</span></div>
            </div>
            <div class="onboarding-step fade-up" style="animation-delay:0.4s">
                <div class="onboarding-step-icon"></div>
                <div><strong style="color:var(--dark-800);font-size:15px">Langkah 4: Post-Test</strong><br><span style="font-size:13px;color:var(--dark-500)">Lihat seberapa besar kemajuanmu!</span></div>
            </div>
        </div>
        <div style="display:flex;gap:12px;margin-top:28px">
            <a href="{{ route('siswa.pretest') }}" class="btn btn-primary btn-lg" style="flex:1;justify-content:center;">Mulai Sekarang</a>
            <button onclick="document.getElementById('onboardingOverlay').style.display='none'" class="btn btn-secondary btn-lg" style="flex:1;justify-content:center;">Nanti Saja</button>
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
    <div class="challenge-badge">Tantangan Hari ke-{{ $todayChallenge->day_number }}</div>
    <h3 style="display:flex;align-items:center;gap:8px;"><i data-lucide="target" style="width:24px;height:24px;"></i> {{ $todayChallenge->title }}</h3>
    <p>{{ $todayChallenge->description }}</p>
</div>
@endif

{{-- Stats --}}
<div class="stats-grid fade-up" style="grid-template-columns:repeat(auto-fit, minmax(160px, 1fr))">
    <div class="stat-card">
        <div class="stat-icon pink"><i data-lucide="clock"></i></div>
        <div class="stat-value">{{ round($avgScreenTime, 1) }}j</div>
        <div class="stat-label">Rata-rata Harian</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon blue"><i data-lucide="clipboard-list"></i></div>
        <div class="stat-value">{{ $totalTrackings }}</div>
        <div class="stat-label">Jurnal Terisi</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon yellow"><i data-lucide="flame"></i></div>
        <div class="stat-value">{{ $streak }}</div>
        <div class="stat-label">Streak Hari</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon green"><i data-lucide="star"></i></div>
        <div class="stat-value">{{ $points }}</div>
        <div class="stat-label">Poin Terkumpul</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon blue"><i data-lucide="award"></i></div>
        <div class="stat-value">{{ count($badges) }}</div>
        <div class="stat-label">Lencana</div>
    </div>
</div>

{{-- Badges --}}
@if(count($badges))
<div class="card fade-up" style="margin-bottom:24px">
    <div class="card-header"><h3 class="text-accent" style="display:flex;align-items:center;gap:8px;"><i data-lucide="award" style="width:24px;height:24px;"></i> Koleksi Lencanamu</h3></div>
    <div class="card-body">
        <div style="display:flex;flex-wrap:wrap;gap:12px">
            @foreach($badges as $b)
            <div class="badge-card">
                <div style="font-size:40px" class="float-anim">{{ $b['icon'] }}</div>
                <div class="badge-title">{{ $b['name'] }}</div>
                <div class="badge-desc">{{ $b['desc'] }}</div>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endif

<div class="dashboard-grid-2">
    {{-- Status Hari Ini --}}
    <div class="card fade-up">
        <div class="card-header"><h3 class="text-primary" style="display:flex;align-items:center;gap:8px;"><i data-lucide="pin" style="width:20px;height:20px;"></i> Hari Ini</h3></div>
        <div class="card-body">
            @if($todayTracking)
                <div style="text-align:center;padding:16px 0">
                    <div class="bounce-anim" style="margin-bottom:12px;display:flex;justify-content:center;"><i data-lucide="star" style="width:52px;height:52px;color:var(--warning);"></i></div>
                    <p style="color:var(--primary-dark);font-weight:800;font-size:18px;margin-bottom:6px">Jurnal hari ini sudah diisi!</p>
                    <p style="font-size:15px;color:var(--dark-600);font-weight:600">Screen time: <span style="color:var(--primary-600);font-size:22px;font-weight:900">{{ $todayTracking->screen_time_hours }} jam</span></p>
                </div>
                @if($todayTracking->activities)
                <div style="margin-top:12px;text-align:center">
                    @foreach($todayTracking->activities as $key => $val)
                        <span class="badge badge-blue" style="margin:3px;font-size:13px;padding:6px 12px">{{ ucfirst($key) }}: {{ $val }}j</span>
                    @endforeach
                </div>
                @endif
            @else
                <div style="text-align:center;padding:16px 0">
                    <div class="float-anim" style="margin-bottom:12px;display:flex;justify-content:center;"><i data-lucide="edit-3" style="width:52px;height:52px;color:var(--dark-400);"></i></div>
                    <p style="color:var(--dark-600);margin-bottom:16px;font-weight:600;font-size:15px">Kamu belum isi jurnal hari ini. Yuk isi sekarang biar streak-mu tidak putus!</p>
                    <a href="{{ route('siswa.tracking.create') }}" class="btn btn-primary" style="background:var(--primary);box-shadow:0 4px 0 var(--primary-dark);display:inline-flex;align-items:center;gap:8px;"><i data-lucide="pen-line" style="width:16px;height:16px;"></i> Isi Jurnal Sekarang</a>
                </div>
            @endif
        </div>
    </div>

    {{-- Chart --}}
    <div class="card fade-up">
        <div class="card-header"><h3 class="text-primary" style="display:flex;align-items:center;gap:8px;"><i data-lucide="trending-up" style="width:20px;height:20px;"></i> Screen Time Seminggu</h3></div>
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
<div class="card fade-up" style="margin-bottom:24px">
    <div class="card-header" style="background:var(--accent-yellow);border-bottom:none">
        <h3 style="color:var(--dark-900);font-size:18px;display:flex;justify-content:space-between;align-items:center;width:100%">
            <span style="display:flex;align-items:center;gap:8px;"><i data-lucide="award" style="width:20px;height:20px;"></i> Progres Tantangan</span>
            <span class="badge" style="background:white;color:var(--dark-900)">{{ $completedCount }}/{{ $challenges->count() }}</span>
        </h3>
    </div>
    <div class="card-body">
        <div style="margin-bottom:20px">
            <div style="display:flex;justify-content:space-between;font-size:14px;font-weight:700;margin-bottom:8px">
                <span>{{ $progressPct }}% selesai</span>
                <span>{{ $completedCount }} dari {{ $challenges->count() }} tantangan</span>
            </div>
            <div class="progress-bar">
                <div class="progress-fill" style="width:{{ $progressPct }}%"></div>
            </div>
        </div>
        <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(260px,1fr));gap:12px">
            @foreach($challenges as $ch)
            @php $isDone = $todayTracking && isset($todayTracking->challenge_checklist[$ch->id]) && $todayTracking->challenge_checklist[$ch->id]; @endphp
            <div style="padding:16px;background:{{ $isDone ? 'var(--primary-50)' : 'white' }};border-radius:16px;border:2px solid {{ $isDone ? 'var(--primary-200)' : 'var(--dark-100)' }};transition:transform .2s">
                <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:6px">
                    <div style="font-size:12px;color:{{ $isDone ? 'var(--primary-600)' : 'var(--dark-500)' }};font-weight:800;text-transform:uppercase;letter-spacing:0.5px">Hari {{ $ch->day_number }}</div>
                    @if($isDone)<span class="badge" style="background:var(--accent-green);color:var(--dark-900);font-size:12px;display:inline-flex;align-items:center;gap:4px;"><i data-lucide="check-circle-2" style="width:14px;height:14px;"></i> Selesai</span>@endif
                </div>
                <div style="font-weight:800;font-size:15px;color:{{ $isDone ? 'var(--primary-700)' : 'var(--dark-800)' }}">{{ $ch->title }}</div>
                <div style="font-size:13px;color:var(--dark-500);margin-top:6px;font-weight:600">{{ Str::limit($ch->description, 80) }}</div>
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
    data: { labels, datasets: [{ label: 'Screen Time (jam)', data, borderColor: '#8b5cf6', backgroundColor: 'rgba(139,92,246,0.12)', fill: true, tension: 0.4, pointBackgroundColor: '#7c3aed', pointRadius: 5, pointHoverRadius: 8, borderWidth: 3 }] },
    options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true, title: { display: true, text: 'Jam' } } } }
});
</script>
@endpush
