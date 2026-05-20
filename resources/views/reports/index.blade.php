@extends('layouts.app')
@section('title', 'Laporan Program')
@section('page-title', 'Laporan Program ScreenWise')
@section('content')

<div style="display:flex;justify-content:flex-end;margin-bottom:20px">
    <a href="{{ route('admin.reports.pdf') }}" class="btn btn-primary" style="display:inline-flex;align-items:center;gap:6px;"><i data-lucide="download" style="width:16px;height:16px;"></i> Export PDF</a>
</div>
<div class="stats-grid" style="grid-template-columns:repeat(auto-fit, minmax(180px, 1fr))">
    <div class="stat-card"><div class="stat-icon blue"><i data-lucide="users"></i></div><div class="stat-value">{{ $totalSiswa }}</div><div class="stat-label">Total Siswa</div></div>
    <div class="stat-card"><div class="stat-icon green"><i data-lucide="check-circle"></i></div><div class="stat-value">{{ $completedBoth }}</div><div class="stat-label">Lengkap Pre+Post</div></div>
    <div class="stat-card"><div class="stat-icon yellow"><i data-lucide="clipboard-list"></i></div><div class="stat-value">{{ round($avgPreScreenTime,1) }} jam</div><div class="stat-label">Avg Pre-Test</div></div>
    <div class="stat-card"><div class="stat-icon green"><i data-lucide="check-circle"></i></div><div class="stat-value">{{ round($avgPostScreenTime,1) }} jam</div><div class="stat-label">Avg Post-Test</div></div>
</div>

@if($avgPreScreenTime > 0 && $avgPostScreenTime > 0)
@php $change = round($avgPostScreenTime - $avgPreScreenTime, 1); @endphp
<div class="challenge-card" style="margin-bottom:28px;{{ $change <= 0 ? '' : 'background:linear-gradient(135deg,#ef4444,#dc2626)' }}">
    <h3 style="display:flex;align-items:center;justify-content:center;gap:8px;">{!! $change <= 0 ? '<i data-lucide="party-popper" style="color:#10b981;"></i> Program Berhasil!' : '<i data-lucide="alert-triangle" style="color:#f59e0b;"></i> Perlu Evaluasi' !!}</h3>
    <p>Rata-rata screen time {{ $change <= 0 ? 'menurun' : 'meningkat' }} sebesar <strong>{{ abs($change) }} jam</strong> (dari {{ round($avgPreScreenTime,1) }} jam → {{ round($avgPostScreenTime,1) }} jam)</p>
</div>
@endif

<div class="card">
    <div class="card-header"><h3 style="display:flex;align-items:center;gap:8px;"><i data-lucide="bar-chart-2"></i> Detail Per Siswa</h3></div>
    <div class="card-body">
        <div class="table-container">
            <table>
                <thead><tr><th>Nama</th><th>Pre-Test</th><th>Post-Test</th><th>Perubahan</th><th>Tracking</th><th>Avg Harian</th></tr></thead>
                <tbody>
                    @foreach($reportData as $r)
                    <tr>
                        <td><strong>{{ $r->siswa->name }}</strong><br><span style="font-size:12px;color:var(--dark-400)">{{ $r->siswa->kelas ?? '' }}</span></td>
                        <td>{{ $r->preScreenTime ? $r->preScreenTime . ' jam' : '-' }}</td>
                        <td>{{ $r->postScreenTime ? $r->postScreenTime . ' jam' : '-' }}</td>
                        <td>
                            @if($r->change !== null)
                                <span class="badge {{ $r->change <= 0 ? 'badge-green' : 'badge-red' }}">{{ $r->change > 0 ? '+' : '' }}{{ $r->change }} jam</span>
                            @else - @endif
                        </td>
                        <td>{{ $r->totalDays }} hari</td>
                        <td>
                            <div style="display:flex;align-items:center;justify-content:space-between;">
                                <span>{{ $r->avgTracking ? $r->avgTracking . ' jam' : '-' }}</span>
                                <a href="{{ route('admin.users.show', $r->siswa) }}" class="btn btn-sm" style="background:var(--accent-yellow);color:var(--dark-800);border:none;padding:4px 8px;font-size:12px;">Detail</a>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
