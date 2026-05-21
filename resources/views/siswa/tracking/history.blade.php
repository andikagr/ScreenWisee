@extends('layouts.app')
@section('title', 'Riwayat Tracking')
@section('page-title', 'Riwayat Tracking')
@section('content')

<div class="card fade-up" style="border:4px solid var(--primary-100);border-radius:32px">
    <div class="card-header" style="background:var(--primary-50);border-bottom:none;padding:24px">
        <h3 style="color:var(--primary-600);font-size:24px;display:flex;align-items:center;gap:8px;"><i data-lucide="book-open" style="width:28px;height:28px;"></i> Buku Petualangan (Riwayat Jurnal)</h3>
        <a href="{{ route('siswa.tracking.create') }}" class="btn btn-sm pulse-ring" style="background:var(--accent-pink-dark);color:white;box-shadow:0 4px 0 #be185d;display:flex;align-items:center;gap:6px;"><i data-lucide="plus" style="width:16px;height:16px;"></i> Isi Jurnal Baru</a>
    </div>
    <div class="card-body" style="padding:32px">
        @if($trackings->count())
        <div class="table-container" style="border-radius:24px;border:3px solid var(--primary-100)">
            <table style="width:100%">
                <thead style="background:var(--primary-50)">
                    <tr>
                        <th style="display:flex;align-items:center;gap:6px;"><i data-lucide="calendar" style="width:16px;height:16px;"></i> Tanggal Misi</th>
                        <th><div style="display:flex;align-items:center;gap:6px;"><i data-lucide="smartphone" style="width:16px;height:16px;"></i> Waktu Main HP</div></th>
                        <th><div style="display:flex;align-items:center;gap:6px;"><i data-lucide="gamepad-2" style="width:16px;height:16px;"></i> Ngapain Aja?</div></th>
                        <th><div style="display:flex;align-items:center;gap:6px;"><i data-lucide="camera" style="width:16px;height:16px;"></i> Bukti Foto</div></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($trackings as $t)
                    <tr>
                        <td><strong style="color:var(--dark-800)">{{ $t->tracking_date->format('d M Y') }}</strong></td>
                        <td><span class="badge {{ $t->screen_time_hours <= 3 ? 'badge-green' : ($t->screen_time_hours <= 6 ? 'badge-yellow' : 'badge-red') }}" style="font-size:14px;padding:6px 12px">{{ $t->screen_time_hours }} jam</span></td>
                        <td>
                            @if($t->activities)
                                <div style="display:flex;gap:4px;flex-wrap:wrap">
                                @foreach($t->activities as $k => $v)
                                    @if($v > 0)<span class="badge" style="background:var(--dark-100);color:var(--dark-800)">{{ ucfirst($k) }}: {{ $v }}j</span>@endif
                                @endforeach
                                </div>
                            @else - @endif
                        </td>
                        <td>
                            @if($t->screenshot_path)
                                <a href="{{ $t->screenshot_url }}" target="_blank" class="btn btn-sm bounce-anim" style="background:var(--accent-yellow);color:var(--dark-900);border:none;box-shadow:0 4px 0 var(--accent-yellow-dark);display:inline-flex;align-items:center;gap:6px;"><i data-lucide="camera" style="width:14px;height:14px;"></i> Lihat Foto</a>
                            @else - @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="pagination" style="margin-top:24px">
            {{ $trackings->links('pagination.simple') }}
        </div>
        @else
        <div class="empty-state fade-up">
            <div class="empty-state-icon float-anim" style="font-size:64px;display:flex;justify-content:center;"><i data-lucide="inbox" style="width:64px;height:64px;color:var(--primary-400);"></i></div>
            <h3 style="color:var(--primary-600);font-size:24px;margin-top:16px;">Buku Petualanganmu Masih Kosong Nih!</h3>
            <p style="color:var(--dark-500);font-weight:600;font-size:16px">Yuk, mulai catat aktivitas main HP kamu tiap hari!</p>
            <a href="{{ route('siswa.tracking.create') }}" class="btn btn-lg bounce-anim" style="background:var(--accent-pink-dark);color:white;box-shadow:0 8px 0 #be185d;margin-top:16px;display:inline-flex;align-items:center;gap:8px;"><i data-lucide="pen-tool" style="width:20px;height:20px;"></i> Mulai Misi Pertamamu!</a>
        </div>
        @endif
    </div>
</div>

@endsection
