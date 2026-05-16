@extends('layouts.app')
@section('title', 'Edit User')
@section('page-title', 'Edit User: ' . $user->name)
@section('content')
<div class="card" style="max-width:600px">
    <div class="card-header"><h3>✏️ Edit User</h3></div>
    <div class="card-body">
        <form method="POST" action="{{ route('admin.users.update', $user) }}">
            @csrf @method('PUT')
            <div class="form-group"><label class="form-label">Nama</label><input type="text" name="name" class="form-input" value="{{ old('name', $user->name) }}" required></div>
            <div class="form-group"><label class="form-label">Email</label><input type="email" name="email" class="form-input" value="{{ old('email', $user->email) }}" required></div>
            <div class="form-group"><label class="form-label">Password Baru (kosongkan jika tidak diubah)</label><input type="password" name="password" class="form-input"></div>
            <div class="form-group">
                <label class="form-label">Role</label>
                <select name="role" class="form-select" id="roleSelect">
                    <option value="siswa" {{ $user->role === 'siswa' ? 'selected' : '' }}>Siswa</option>
                    <option value="guru" {{ $user->role === 'guru' ? 'selected' : '' }}>Guru</option>
                    <option value="admin" {{ $user->role === 'admin' ? 'selected' : '' }}>Admin</option>
                </select>
            </div>
            <div class="form-group" id="kelasGroup" style="{{ $user->role !== 'siswa' ? 'display:none' : '' }}"><label class="form-label">Kelas</label><input type="text" name="kelas" class="form-input" value="{{ old('kelas', $user->kelas) }}"></div>
            <div class="form-group" id="guruGroup" style="{{ $user->role !== 'siswa' ? 'display:none' : '' }}">
                <label class="form-label">Assign ke Guru</label>
                <select name="guru_id" class="form-select"><option value="">-- Pilih Guru --</option>@foreach($guruList as $g)<option value="{{ $g->id }}" {{ $user->guru_id == $g->id ? 'selected' : '' }}>{{ $g->name }}</option>@endforeach</select>
            </div>
            <div class="btn-group"><button type="submit" class="btn btn-primary">💾 Update</button><a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Batal</a></div>
        </form>
    </div>
</div>
<script>
document.getElementById('roleSelect').addEventListener('change', function() {
    document.getElementById('kelasGroup').style.display = this.value === 'siswa' ? 'block' : 'none';
    document.getElementById('guruGroup').style.display = this.value === 'siswa' ? 'block' : 'none';
});
</script>
@endsection
