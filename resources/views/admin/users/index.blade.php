@extends('layouts.app')
@section('title', 'Kelola User')
@section('page-title', 'Kelola User')
@section('content')

@php $unassignedSiswa = $users->where('role', 'siswa')->whereNull('guru_id')->count(); @endphp

@if($unassignedSiswa > 0)
<div class="alert alert-warning">
    <div style="display:flex;align-items:center;gap:8px;"><i data-lucide="alert-triangle" style="width:20px;height:20px;"></i> <span>Ada <strong>{{ $unassignedSiswa }} siswa</strong> yang belum di-assign ke guru (ditandai <i data-lucide="alert-circle" style="color:var(--danger);width:16px;height:16px;display:inline-block;vertical-align:-3px;"></i>). Klik <strong>Edit</strong> untuk assign guru ke siswa tersebut agar Guru bisa memonitor mereka.</span></div>
</div>
@endif

<div class="card">
    <div class="card-header">
        <h3 style="display:flex;align-items:center;gap:8px;"><i data-lucide="users"></i> Daftar User</h3>
        <a href="{{ route('admin.users.create') }}" class="btn btn-primary btn-sm">+ Tambah User</a>
    </div>
    <div class="card-body">
        <form method="GET" action="{{ route('admin.users.index') }}" style="display:flex;gap:12px;margin-bottom:24px;flex-wrap:wrap">
            <div style="position:relative;flex:1;min-width:200px;">
                <i data-lucide="search" style="position:absolute;left:12px;top:50%;transform:translateY(-50%);color:var(--text-3);width:18px;height:18px;"></i>
                <input type="text" name="search" class="form-input" placeholder="Cari nama, email, kelas..." value="{{ request('search') }}" style="width:100%;border-radius:20px;padding:12px 20px 12px 38px">
            </div>
            <select name="role" class="form-input" style="width:auto;min-width:140px;border-radius:20px">
                <option value="">Semua Peran</option>
                <option value="admin" {{ request('role') === 'admin' ? 'selected' : '' }}>Admin</option>
                <option value="guru" {{ request('role') === 'guru' ? 'selected' : '' }}>Guru</option>
                <option value="siswa" {{ request('role') === 'siswa' ? 'selected' : '' }}>Murid</option>
            </select>
            <button type="submit" class="btn btn-sm bounce-anim" style="background:var(--primary-500);color:white;border-radius:20px;box-shadow:0 4px 0 var(--primary-700)">Cari</button>
            @if(request('search') || request('role'))
            <a href="{{ route('admin.users.index') }}" class="btn btn-sm" style="background:var(--dark-100);color:var(--dark-800);border-radius:20px">Reset</a>
            @endif
        </form>
        <div class="table-container" style="border-radius:24px;border:3px solid var(--dark-100)">
            <table style="width:100%">
                <thead style="background:var(--primary-50)"><tr><th>Nama</th><th>Email</th><th>Peran</th><th>Kelas</th><th>Guru Pembimbing</th><th>Aksi</th></tr></thead>
                <tbody>
                    @foreach($users as $u)
                    @php $noGuru = ($u->role === 'siswa' && is_null($u->guru_id)); @endphp
                    <tr style="{{ $noGuru ? 'border-left: 4px solid var(--danger);' : '' }}">
                        <td>
                            <strong style="color:var(--primary-600);font-size:15px">{{ $u->name }}</strong>
                            @if($noGuru)<span style="color:var(--danger);font-size:12px;font-weight:700;margin-left:4px;display:inline-flex;align-items:center;gap:4px;"><i data-lucide="alert-circle" style="width:14px;height:14px;"></i> Belum di-assign</span>@endif
                        </td>
                        <td>{{ $u->email }}</td>
                        <td><span class="badge {{ $u->role === 'admin' ? 'badge-red' : ($u->role === 'guru' ? 'badge-blue' : 'badge-green') }}" style="padding:6px 12px">{{ ucfirst($u->role) }}</span></td>
                        <td><span class="badge" style="background:var(--dark-100)">{{ $u->kelas ?? '-' }}</span></td>
                        <td>
                            @if($noGuru)
                                <span style="color:var(--danger);font-weight:700;font-size:13px;display:inline-flex;align-items:center;gap:4px;"><i data-lucide="alert-triangle" style="width:14px;height:14px;"></i> Belum ada</span>
                            @else
                                {{ $u->guru?->name ?? '-' }}
                            @endif
                        </td>
                        <td>
                            <div class="btn-group" style="gap:8px">
                                @if($u->role === 'siswa')
                                <a href="{{ route('admin.users.show', $u) }}" class="btn btn-sm" style="background:var(--accent-yellow);color:var(--dark-800);border:none">👁️ Detail</a>
                                @endif
                                <a href="{{ route('admin.users.edit', $u) }}" class="btn btn-sm" style="background:var(--primary-100);color:var(--primary-700);border:none">✏️ Edit{{ $noGuru ? ' (Assign Guru!)' : '' }}</a>
                                @if($u->id !== auth()->id())
                                <form method="POST" action="{{ route('admin.users.destroy', $u) }}" onsubmit="return confirm('Hapus user ini?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm" style="background:var(--danger);color:white;border:none">🗑️ Hapus</button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="pagination">{{ $users->links('pagination.simple') }}</div>
    </div>
</div>
@endsection
