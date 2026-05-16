@extends('layouts.app')
@section('title', 'Perbandingan')
@section('page-title', 'Perbandingan Pre-Test & Post-Test')
@section('content')

@if(!$pretest || !$posttest)
<div class="alert alert-warning" style="border-radius:20px;font-weight:700">⚠️ Wah, kamu harus selesain misi {{ !$pretest ? 'Pre-Test' : '' }}{{ !$pretest && !$posttest ? ' dan ' : '' }}{{ !$posttest ? 'Post-Test' : '' }} dulu nih buat liat hasilnya.</div>
<div class="btn-group">
    @if(!$pretest)<a href="{{ route('siswa.pretest') }}" class="btn btn-lg" style="background:var(--accent-yellow);color:var(--dark-900);box-shadow:0 6px 0 var(--accent-yellow-dark)">🎬 Mulai Pre-Test</a>@endif
    @if(!$posttest)<a href="{{ route('siswa.posttest') }}" class="btn btn-lg" style="background:var(--accent-green);color:var(--dark-900);box-shadow:0 6px 0 var(--accent-green-dark)">🏆 Mulai Post-Test</a>@endif
</div>
@else
<div class="stats-grid" style="grid-template-columns:repeat(auto-fit,minmax(250px,1fr));margin-bottom:32px">
    <div class="stat-card fade-up">
        <div class="stat-icon yellow float-anim">🎬</div>
        <div class="stat-value">{{ $pretest->avg_screen_time }}j</div>
        <div class="stat-label">Waktu Main HP (Dulu)</div>
    </div>
    <div class="stat-card fade-up" style="animation-delay:0.1s">
        <div class="stat-icon green bounce-anim">🏆</div>
        <div class="stat-value">{{ $posttest->avg_screen_time }}j</div>
        <div class="stat-label">Waktu Main HP (Sekarang)</div>
    </div>
    <div class="stat-card fade-up" style="animation-delay:0.2s;background:var(--primary-50);border:4px solid var(--primary-100)">
        @php $diff = $posttest->avg_screen_time - $pretest->avg_screen_time; @endphp
        <div class="stat-icon {{ $diff <= 0 ? 'green' : 'red' }} pulse-ring">{{ $diff <= 0 ? '🎉' : '📈' }}</div>
        <div class="stat-value" style="color:{{ $diff <= 0 ? 'var(--accent-green-dark)' : 'var(--danger)' }};font-size:36px">{{ $diff > 0 ? '+' : '' }}{{ $diff }}j</div>
        <div class="stat-label">{{ $diff <= 0 ? 'Hebat, turun lho!' : 'Wah, malah naik nih?' }}</div>
    </div>
</div>

<div style="display:grid;grid-template-columns:1fr 1fr;gap:24px;margin-bottom:32px">
    <div class="card fade-up">
        <div class="card-header"><h3 style="color:var(--primary-600);font-size:20px">📊 Bukti Keberhasilanmu!</h3></div>
        <div class="card-body">
            <div class="chart-container"><canvas id="compChart"></canvas></div>
        </div>
    </div>
    <div class="card fade-up" style="animation-delay:0.1s">
        <div class="card-header"><h3 style="color:var(--accent-purple-dark);font-size:20px">🕐 Jam Tidur Dulu vs Sekarang</h3></div>
        <div class="card-body">
            <div class="table-container" style="border-radius:24px;border:3px solid var(--primary-100)">
                <table style="width:100%">
                    <thead style="background:var(--primary-50)"><tr><th>Tipe</th><th>Dulu (Pre-Test)</th><th>Sekarang (Post-Test)</th></tr></thead>
                    <tbody>
                        <tr><td><strong style="color:var(--primary-600)">😴 Jam Tidur</strong></td><td><span class="badge" style="background:var(--dark-100)">{{ $pretest->sleep_time }}</span></td><td><span class="badge" style="background:var(--accent-green)">{{ $posttest->sleep_time }}</span></td></tr>
                        <tr><td><strong style="color:var(--primary-600)">🌅 Jam Bangun</strong></td><td><span class="badge" style="background:var(--dark-100)">{{ $pretest->wake_time }}</span></td><td><span class="badge" style="background:var(--accent-green)">{{ $posttest->wake_time }}</span></td></tr>
                        <tr><td><strong style="color:var(--primary-600)">📱 Main HP</strong></td><td><span class="badge" style="background:var(--dark-100)">{{ $pretest->avg_screen_time }} j</span></td><td><span class="badge" style="background:var(--accent-green)">{{ $posttest->avg_screen_time }} j</span></td></tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="card fade-up" style="border:4px solid var(--accent-pink);border-radius:32px">
    <div class="card-header" style="background:var(--accent-pink);border-bottom:none"><h3 style="color:var(--dark-900);font-size:20px">📋 Apakah Kebiasaan Jelekmu Udah Hilang?</h3></div>
    <div class="card-body">
        <div class="table-container" style="border-radius:24px;border:3px solid var(--primary-100)">
            <table style="width:100%">
                <thead style="background:var(--primary-50)"><tr><th>Kebiasaan</th><th>Dulu</th><th>Sekarang</th></tr></thead>
                <tbody>
                    @php $allHabits = array_unique(array_merge($pretest->gadget_habits ?? [], $posttest->gadget_habits ?? [])); @endphp
                    @foreach($allHabits as $h)
                    <tr>
                        <td style="font-weight:600;font-size:15px;color:var(--dark-800)">{{ $h }}</td>
                        <td style="text-align:center">{!! in_array($h, $pretest->gadget_habits ?? []) ? '<span class="bounce-anim" style="display:inline-block;font-size:24px">😭</span>' : '<span style="display:inline-block;font-size:24px;opacity:0.3">❌</span>' !!}</td>
                        <td style="text-align:center">{!! in_array($h, $posttest->gadget_habits ?? []) ? '<span class="bounce-anim" style="display:inline-block;font-size:24px">😭</span>' : '<span style="display:inline-block;font-size:24px">😎</span><br><span style="font-size:12px;font-weight:700;color:var(--accent-green-dark)">Hilang!</span>' !!}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endif

@endsection
@push('scripts')
@if($pretest && $posttest)
<script>
new Chart(document.getElementById('compChart'), {
    type: 'bar',
    data: {
        labels: ['Screen Time (jam)'],
        datasets: [
            { label: 'Pre-Test', data: [{{ $pretest->avg_screen_time }}], backgroundColor: '#f59e0b', borderRadius: 8 },
            { label: 'Post-Test', data: [{{ $posttest->avg_screen_time }}], backgroundColor: '#10b981', borderRadius: 8 }
        ]
    },
    options: { responsive: true, maintainAspectRatio: false, scales: { y: { beginAtZero: true } } }
});
</script>
@endif
@endpush
