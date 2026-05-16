@extends('layouts.app')
@section('title', 'Kelola User')
@section('page-title', 'Kelola User')
@section('content')

<div class="card">
    <div class="card-header">
        <h3>👥 Daftar User</h3>
        <a href="{{ route('admin.users.create') }}" class="btn btn-primary btn-sm">+ Tambah User</a>
    </div>
    <div class="card-body">
        <form method="GET" action="{{ route('admin.users.index') }}" style="display:flex;gap:12px;margin-bottom:24px;flex-wrap:wrap">
            <input type="text" name="search" class="form-input" placeholder="🔍 Cari nama, email, kelas..." value="{{ request('search') }}" style="flex:1;min-width:200px;border-radius:20px;padding:12px 20px">
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
                    <tr>
                        <td><strong style="color:var(--primary-600);font-size:15px">{{ $u->name }}</strong></td>
                        <td style="color:var(--dark-600)">{{ $u->email }}</td>
                        <td><span class="badge {{ $u->role === 'admin' ? 'badge-red' : ($u->role === 'guru' ? 'badge-blue' : 'badge-green') }}" style="padding:6px 12px">{{ ucfirst($u->role) }}</span></td>
                        <td><span class="badge" style="background:var(--dark-100);color:var(--dark-800)">{{ $u->kelas ?? '-' }}</span></td>
                        <td style="color:var(--dark-700)">{{ $u->guru?->name ?? '-' }}</td>
                        <td>
                            <div class="btn-group" style="gap:8px">
                                @if($u->role === 'siswa')
                                <a href="{{ route('admin.users.show', $u) }}" class="btn btn-sm" style="background:var(--accent-yellow);color:var(--dark-800);border:none">👁️ Detail</a>
                                @endif
                                <a href="{{ route('admin.users.edit', $u) }}" class="btn btn-sm" style="background:var(--primary-100);color:var(--primary-700);border:none">✏️ Edit</a>
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
