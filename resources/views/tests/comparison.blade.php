@extends('layouts.app')
@section('title', 'Perbandingan')
@section('page-title', 'Perbandingan Pre-Test & Post-Test')
@section('content')

@if(!$pretest || !$posttest)
<div class="alert alert-warning" style="border-radius:20px;font-weight:700;display:flex;align-items:center;gap:8px;"><i data-lucide="alert-triangle" style="width:20px;height:20px;"></i> Wah, kamu harus selesain misi {{ !$pretest ? 'Pre-Test' : '' }}{{ !$pretest && !$posttest ? ' dan ' : '' }}{{ !$posttest ? 'Post-Test' : '' }} dulu nih buat liat hasilnya.</div>
<div class="btn-group">
    @if(!$pretest)<a href="{{ route('siswa.pretest') }}" class="btn btn-primary btn-lg" style="display:flex;align-items:center;gap:8px;"><i data-lucide="clipboard-list" style="width:18px;height:18px;"></i> Mulai Pre-Test</a>@endif
    @if(!$posttest)<a href="{{ route('siswa.posttest') }}" class="btn btn-primary btn-lg" style="display:flex;align-items:center;gap:8px;"><i data-lucide="award" style="width:18px;height:18px;"></i> Mulai Post-Test</a>@endif
</div>
@else
<div class="stats-grid" style="grid-template-columns:repeat(auto-fit,minmax(250px,1fr));margin-bottom:32px">
    <div class="stat-card fade-up">
        <div class="stat-icon" style="background:#e0e7ff;color:#3b82f6"><i data-lucide="clipboard-list"></i></div>
        <div class="stat-value">{{ $pretest->avg_screen_time }}j</div>
        <div class="stat-label">Waktu Main HP (Dulu)</div>
    </div>
    <div class="stat-card fade-up" style="animation-delay:0.1s">
        <div class="stat-icon" style="background:#dcfce7;color:#16a34a"><i data-lucide="award"></i></div>
        <div class="stat-value">{{ $posttest->avg_screen_time }}j</div>
        <div class="stat-label">Waktu Main HP (Sekarang)</div>
    </div>
    <div class="stat-card fade-up" style="animation-delay:0.2s;background:var(--surface-2);border:2px solid var(--border)">
        @php $diff = $posttest->avg_screen_time - $pretest->avg_screen_time; @endphp
        <div class="stat-icon {{ $diff <= 0 ? 'green' : 'red' }} pulse-ring" style="background:{{ $diff <= 0 ? '#dcfce7' : '#fee2e2' }};color:{{ $diff <= 0 ? '#16a34a' : '#dc2626' }}"><i data-lucide="{{ $diff <= 0 ? 'party-popper' : 'trending-up' }}"></i></div>
        <div class="stat-value" style="color:{{ $diff <= 0 ? '#16a34a' : '#dc2626' }};font-size:36px">{{ $diff > 0 ? '+' : '' }}{{ $diff }}j</div>
        <div class="stat-label">{{ $diff <= 0 ? 'Hebat, turun lho!' : 'Wah, malah naik nih?' }}</div>
    </div>
</div>

<div class="dashboard-grid-2">
    <div class="card fade-up">
        <div class="card-header"><h3 style="color:var(--text);font-size:20px;display:flex;align-items:center;gap:8px;"><i data-lucide="bar-chart-2"></i> Bukti Keberhasilanmu!</h3></div>
        <div class="card-body">
            <div class="chart-container"><canvas id="compChart"></canvas></div>
        </div>
    </div>
    <div class="card fade-up" style="animation-delay:0.1s">
        <div class="card-header"><h3 style="color:var(--text);font-size:20px;display:flex;align-items:center;gap:8px;"><i data-lucide="clock"></i> Jam Tidur Dulu vs Sekarang</h3></div>
        <div class="card-body">
            <div class="table-container" style="border-radius:16px;border:2px solid var(--border)">
                <table style="width:100%">
                    <thead style="background:var(--surface-2)"><tr><th>Tipe</th><th>Dulu (Pre-Test)</th><th>Sekarang (Post-Test)</th></tr></thead>
                    <tbody>
                        <tr><td><strong style="color:var(--text);display:flex;align-items:center;gap:6px;"><i data-lucide="moon" style="width:16px;height:16px;color:#3b82f6;"></i> Jam Tidur</strong></td><td><span class="badge" style="background:var(--surface-2)">{{ $pretest->sleep_time }}</span></td><td><span class="badge" style="background:#dcfce7;color:#16a34a;">{{ $posttest->sleep_time }}</span></td></tr>
                        <tr><td><strong style="color:var(--text);display:flex;align-items:center;gap:6px;"><i data-lucide="sunrise" style="width:16px;height:16px;color:#f59e0b;"></i> Jam Bangun</strong></td><td><span class="badge" style="background:var(--surface-2)">{{ $pretest->wake_time }}</span></td><td><span class="badge" style="background:#dcfce7;color:#16a34a;">{{ $posttest->wake_time }}</span></td></tr>
                        <tr><td><strong style="color:var(--text);display:flex;align-items:center;gap:6px;"><i data-lucide="smartphone" style="width:16px;height:16px;color:#8b5cf6;"></i> Main HP</strong></td><td><span class="badge" style="background:var(--surface-2)">{{ $pretest->avg_screen_time }} j</span></td><td><span class="badge" style="background:#dcfce7;color:#16a34a;">{{ $posttest->avg_screen_time }} j</span></td></tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="card fade-up" style="border:2px solid var(--border);border-radius:16px">
    <div class="card-header" style="background:var(--surface);border-bottom:1px solid var(--border)"><h3 style="color:var(--text);font-size:20px;display:flex;align-items:center;gap:8px;"><i data-lucide="clipboard-check" style="color:#10b981;"></i> Apakah Kebiasaan Jelekmu Udah Hilang?</h3></div>
    <div class="card-body">
        <div class="table-container" style="border-radius:16px;border:2px solid var(--border)">
            <table style="width:100%">
                <thead style="background:var(--surface-2)"><tr><th>Kebiasaan</th><th style="text-align:center;">Dulu</th><th style="text-align:center;">Sekarang</th></tr></thead>
                <tbody>
                    @php $allHabits = array_unique(array_merge($pretest->gadget_habits ?? [], $posttest->gadget_habits ?? [])); @endphp
                    @foreach($allHabits as $h)
                    <tr>
                        <td style="font-weight:600;font-size:15px;color:var(--text-2)">{!! preg_replace('/[\x{1F600}-\x{1F64F}\x{1F300}-\x{1F5FF}\x{1F680}-\x{1F6FF}\x{1F700}-\x{1F77F}\x{1F780}-\x{1F7FF}\x{1F800}-\x{1F8FF}\x{1F900}-\x{1F9FF}\x{1FA00}-\x{1FA6F}\x{1FA70}-\x{1FAFF}\x{2600}-\x{26FF}\x{2700}-\x{27BF}\x{2300}-\x{23FF}\x{2B50}\x{2B06}\x{2934}\x{2935}\x{25AA}\x{25AB}\x{25FE}\x{25FD}\x{25FB}\x{25FC}\x{25B6}\x{25C0}\x{1F200}-\x{1F251}]/u', '', $h) !!}</td>
                        <td style="text-align:center">{!! in_array($h, $pretest->gadget_habits ?? []) ? '<i data-lucide="frown" class="bounce-anim" style="color:#ef4444;margin:auto;"></i>' : '<i data-lucide="minus" style="color:#94a3b8;margin:auto;"></i>' !!}</td>
                        <td style="text-align:center">{!! in_array($h, $posttest->gadget_habits ?? []) ? '<i data-lucide="frown" class="bounce-anim" style="color:#ef4444;margin:auto;"></i>' : '<div style="display:flex;flex-direction:column;align-items:center;gap:4px;"><i data-lucide="check-circle-2" style="color:#10b981;margin:auto;"></i><span style="font-size:12px;font-weight:700;color:#10b981">Hilang!</span></div>' !!}</td>
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
