@extends('layouts.app')
@section('title', 'Dashboard Guru')
@section('page-title', 'Dashboard Guru')
@section('content')

<div class="stats-grid" style="grid-template-columns:repeat(auto-fit, minmax(200px, 1fr))">
    <div class="stat-card fade-up"><div class="stat-icon blue float-anim">👥</div><div class="stat-value">{{ $totalSiswa }}</div><div class="stat-label">Total Murid</div></div>
    <div class="stat-card fade-up" style="animation-delay:0.1s"><div class="stat-icon green bounce-anim">✅</div><div class="stat-value">{{ $siswaTrackingToday }}</div><div class="stat-label">Udah Ngisi Hari Ini</div></div>
    <div class="stat-card fade-up" style="animation-delay:0.2s"><div class="stat-icon yellow float-anim">📊</div><div class="stat-value">{{ round($overallAvgScreenTime, 1) }}j</div><div class="stat-label">Rata-rata Main HP</div></div>
    <div class="stat-card fade-up" style="animation-delay:0.3s"><div class="stat-icon pink pulse-ring">⚠️</div><div class="stat-value">{{ $totalSiswa - $siswaTrackingToday }}</div><div class="stat-label">Belum Ngisi Jurnal</div></div>
</div>

{{-- Charts Row --}}
<div style="display:grid;grid-template-columns:1fr 1fr;gap:24px;margin-bottom:32px">
    <div class="card fade-up">
        <div class="card-header"><h3 style="color:var(--primary-600);font-size:18px">📈 Trend Waktu Main HP Seminggu</h3></div>
        <div class="card-body"><div class="chart-container"><canvas id="weeklyChart"></canvas></div></div>
    </div>
    <div class="card fade-up" style="animation-delay:0.2s">
        <div class="card-header"><h3 style="color:var(--accent-pink-dark);font-size:18px">📊 Rata-rata Per Kelas</h3></div>
        <div class="card-body"><div class="chart-container"><canvas id="kelasChart"></canvas></div></div>
    </div>
</div>

{{-- Recent Screenshots --}}
@if($recentScreenshots->count())
<div class="card fade-up" style="margin-bottom:32px; border:4px solid var(--accent-yellow); border-radius:32px">
    <div class="card-header" style="background:var(--accent-yellow);border-bottom:none"><h3 style="color:var(--dark-900);font-size:20px">📸 Laporan Screenshot Terbaru</h3></div>
    <div class="card-body">
        <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(200px,1fr));gap:16px">
            @foreach($recentScreenshots as $ss)
            <div style="border:3px solid var(--dark-100);border-radius:24px;overflow:hidden;transition:all .3s;background:white" class="screenshot-card">
                <a href="{{ asset('storage/' . $ss->screenshot_path) }}" target="_blank">
                    <img src="{{ asset('storage/' . $ss->screenshot_path) }}" alt="Screenshot" style="width:100%;height:140px;object-fit:cover;border-bottom:3px solid var(--dark-100)">
                </a>
                <div style="padding:12px;text-align:center">
                    <div style="font-weight:900;font-size:14px;color:var(--primary-600)">{{ $ss->user->name }}</div>
                    <div style="font-size:12px;color:var(--dark-500);font-weight:700">{{ $ss->tracking_date->format('d M Y') }} · {{ $ss->screen_time_hours }}j</div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endif

{{-- Student Table with Search/Filter --}}
<div class="card fade-up">
    <div class="card-header"><h3>👥 Daftar Siswa</h3><button class="btn btn-sm btn-outline" onclick="exportCSV()">📥 Export CSV</button></div>
    <div class="card-body">
        @if($siswaStats->count())
        <div style="display:flex;gap:12px;margin-bottom:16px;flex-wrap:wrap">
            <input type="text" id="searchInput" class="form-input" placeholder="🔍 Cari nama siswa..." style="flex:1;min-width:200px">
            <select id="kelasFilter" class="form-input" style="width:auto;min-width:140px">
                <option value="">Semua Kelas</option>
                @foreach($siswaStats->pluck('user.kelas')->unique()->filter() as $k)
                <option value="{{ $k }}">{{ $k }}</option>
                @endforeach
            </select>
            <select id="statusFilter" class="form-input" style="width:auto;min-width:160px">
                <option value="">Semua Status</option>
                <option value="tracked">Sudah Tracking</option>
                <option value="untracked">Belum Tracking</option>
            </select>
        </div>
        <div class="table-container" style="border-radius:24px;border:3px solid var(--primary-100)">
            <table id="siswaTable">
                <thead style="background:var(--primary-50)">
                    <tr><th>Nama Pahlawan</th><th>Kelas</th><th>Rata-rata Waktu Main HP</th><th>Total Jurnal</th><th>Hari Ini</th><th>Tantangan</th><th>Pre/Post Test</th><th>Aksi</th></tr>
                </thead>
                <tbody>
                    @foreach($siswaStats as $s)
                    <tr data-name="{{ strtolower($s->user->name) }}" data-kelas="{{ $s->user->kelas ?? '' }}" data-status="{{ $s->todayTracking ? 'tracked' : 'untracked' }}">
                        <td><strong style="color:var(--primary-600);font-size:15px">{{ $s->user->name }}</strong></td>
                        <td><span class="badge" style="background:var(--dark-100);color:var(--dark-800)">{{ $s->user->kelas ?? '-' }}</span></td>
                        <td><span class="badge {{ $s->avgScreenTime <= 3 ? 'badge-green' : ($s->avgScreenTime <= 6 ? 'badge-yellow' : 'badge-red') }}" style="font-size:13px">{{ $s->avgScreenTime }} jam</span></td>
                        <td><strong style="color:var(--dark-600)">{{ $s->totalTrackings }}</strong> hari</td>
                        <td>{!! $s->todayTracking ? '<span class="bounce-anim" style="display:inline-block;font-size:20px">✅</span>' : '<span style="display:inline-block;font-size:20px;opacity:0.5">❌</span>' !!}</td>
                        <td><span class="badge badge-blue" style="font-size:14px;background:var(--accent-purple);color:white">{{ $s->completedChallenges }}</span></td>
                        <td>
                            {!! $s->hasPretest ? '<span class="badge badge-green" style="margin-right:4px">Pre✅</span>' : '<span class="badge badge-gray" style="margin-right:4px">Pre❌</span>' !!}
                            {!! $s->hasPosttest ? '<span class="badge badge-green">Post✅</span>' : '<span class="badge badge-gray">Post❌</span>' !!}
                        </td>
                        <td><a href="{{ route('guru.student.detail', $s->user->id) }}" class="btn btn-sm" style="background:var(--primary-100);color:var(--primary-700);border:none">Lihat Detail</a></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div id="noResults" style="display:none;text-align:center;padding:40px 20px;color:var(--dark-400);font-weight:700;font-size:16px">Yah, nggak nemu nama siswanya nih 🧐</div>
        @else
        <div class="empty-state fade-up"><div class="empty-state-icon float-anim" style="font-size:64px">👥</div><h3 style="color:var(--primary-600)">Belum Ada Murid Nih</h3><p style="color:var(--dark-500);font-weight:600">Santai aja, murid-murid bakal muncul kalau udah di-assign sama Admin ya!</p></div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
new Chart(document.getElementById('weeklyChart'), {
    type: 'line',
    data: { labels: {!! json_encode(collect($weeklyData)->pluck('date')) !!},
        datasets: [{ label: 'Avg Screen Time', data: {!! json_encode(collect($weeklyData)->pluck('avg')) !!},
            borderColor: '#10b981', backgroundColor: 'rgba(16,185,129,0.1)', fill: true, tension: 0.4, pointRadius: 5, pointBackgroundColor: '#10b981' }] },
    options: { responsive: true, maintainAspectRatio: false, scales: { y: { beginAtZero: true, title: { display: true, text: 'Jam' } } } }
});
new Chart(document.getElementById('kelasChart'), {
    type: 'bar',
    data: { labels: {!! json_encode($kelasStats->pluck('kelas')) !!},
        datasets: [{ label: 'Avg Screen Time', data: {!! json_encode($kelasStats->pluck('avgScreenTime')) !!},
            backgroundColor: ['#10b981','#3b82f6','#f59e0b','#ef4444','#8b5cf6'], borderRadius: 8 }] },
    options: { responsive: true, maintainAspectRatio: false, scales: { y: { beginAtZero: true } }, plugins: { legend: { display: false } } }
});

// Search & Filter
const search = document.getElementById('searchInput');
const kelasF = document.getElementById('kelasFilter');
const statusF = document.getElementById('statusFilter');
const rows = document.querySelectorAll('#siswaTable tbody tr');
const noRes = document.getElementById('noResults');

function filterTable() {
    const q = search.value.toLowerCase();
    const kf = kelasF.value;
    const sf = statusF.value;
    let visible = 0;
    rows.forEach(r => {
        const matchName = r.dataset.name.includes(q);
        const matchKelas = !kf || r.dataset.kelas === kf;
        const matchStatus = !sf || r.dataset.status === sf;
        const show = matchName && matchKelas && matchStatus;
        r.style.display = show ? '' : 'none';
        if (show) visible++;
    });
    if (noRes) noRes.style.display = visible === 0 ? 'block' : 'none';
}
if (search) search.addEventListener('input', filterTable);
if (kelasF) kelasF.addEventListener('change', filterTable);
if (statusF) statusF.addEventListener('change', filterTable);

// CSV Export
function exportCSV() {
    const headers = ['Nama','Kelas','Avg Screen Time','Total Tracking','Hari Ini','Challenge'];
    let csv = headers.join(',') + '\n';
    rows.forEach(r => {
        if (r.style.display === 'none') return;
        const cells = r.querySelectorAll('td');
        csv += [cells[0].textContent.trim(), cells[1].textContent.trim(), cells[2].textContent.trim(),
                cells[3].textContent.trim(), cells[4].textContent.trim().includes('✅') ? 'Ya' : 'Tidak',
                cells[5].textContent.trim()].join(',') + '\n';
    });
    const blob = new Blob([csv], {type:'text/csv'});
    const a = document.createElement('a');
    a.href = URL.createObjectURL(blob);
    a.download = 'data-siswa-' + new Date().toISOString().slice(0,10) + '.csv';
    a.click();
}
</script>
@endpush
