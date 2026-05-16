@extends('layouts.app')
@section('title', 'Kelola Challenge')
@section('page-title', 'Kelola Challenge')
@section('content')
<div class="card fade-up" style="border:4px solid var(--accent-pink);border-radius:32px">
    <div class="card-header" style="background:var(--accent-pink);border-bottom:none;padding:24px">
        <h3 style="color:var(--dark-900);font-size:24px">🎯 Daftar Tantangan</h3>
        <a href="{{ route('admin.challenges.create') }}" class="btn btn-sm bounce-anim" style="background:var(--dark-900);color:white">+ Buat Tantangan Baru</a>
    </div>
    <div class="card-body" style="padding:24px">
        @if($challenges->count())
        <div class="table-container" style="border-radius:24px;border:3px solid var(--dark-100)">
            <table style="width:100%">
                <thead style="background:var(--primary-50)"><tr><th>Hari</th><th>Judul Tantangan</th><th>Deskripsi Singkat</th><th>Tanggal</th><th>Aksi</th></tr></thead>
                <tbody>
                    @foreach($challenges as $ch)
                    <tr>
                        <td><span class="badge" style="background:var(--accent-yellow);color:var(--dark-900)">Hari {{ $ch->day_number }}</span></td>
                        <td><strong style="color:var(--primary-600);font-size:16px">{{ $ch->title }}</strong></td>
                        <td style="color:var(--dark-600)">{{ Str::limit($ch->description, 60) }}</td>
                        <td><span class="badge" style="background:var(--dark-100);color:var(--dark-800)">{{ $ch->challenge_date ? $ch->challenge_date->format('d M Y') : 'Kapan Saja' }}</span></td>
                        <td>
                            <div class="btn-group" style="gap:8px">
                                <a href="{{ route('admin.challenges.edit', $ch) }}" class="btn btn-sm" style="background:var(--primary-100);color:var(--primary-700);border:none;display:inline-flex;align-items:center;gap:4px;"><i data-lucide="edit-2" style="width:14px;height:14px;"></i> Edit</a>
                                <form method="POST" action="{{ route('admin.challenges.destroy', $ch) }}" onsubmit="return confirm('Hapus challenge ini?')">@csrf @method('DELETE')<button class="btn btn-sm" style="background:var(--danger);color:white;border:none;display:inline-flex;align-items:center;gap:4px;"><i data-lucide="trash-2" style="width:14px;height:14px;"></i> Hapus</button></form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="empty-state fade-up">
            <div class="empty-state-icon float-anim" style="font-size:64px">🎯</div>
            <h3 style="color:var(--primary-600);font-size:24px">Belum Ada Tantangan</h3>
            <p style="color:var(--dark-500);font-weight:600">Yuk, buat tantangan pertama biar murid-murid makin semangat!</p>
            <a href="{{ route('admin.challenges.create') }}" class="btn btn-lg bounce-anim" style="background:var(--primary-500);color:white;box-shadow:0 8px 0 var(--primary-700)">+ Bikin Tantangan!</a>
        </div>
        @endif
    </div>
</div>
@endsection
