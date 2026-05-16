@extends('layouts.app')
@section('title', 'Tambah User')
@section('page-title', 'Tambah User Baru')
@section('content')
<div class="card" style="max-width:600px">
    <div class="card-header"><h3 style="display:flex;align-items:center;gap:8px;"><i data-lucide="user-plus"></i> Form Tambah User</h3></div>
    <div class="card-body">
        <form method="POST" action="{{ route('admin.users.store') }}">
            @csrf
            <div class="form-group"><label class="form-label">Nama</label><input type="text" name="name" class="form-input" value="{{ old('name') }}" required></div>
            <div class="form-group"><label class="form-label">Email</label><input type="email" name="email" class="form-input" value="{{ old('email') }}" required></div>
            <div class="form-group"><label class="form-label">Password</label><input type="password" name="password" class="form-input" required></div>
            <div class="form-group">
                <label class="form-label">Role</label>
                <select name="role" class="form-select" id="roleSelect">
                    <option value="siswa">Siswa</option><option value="guru">Guru</option><option value="admin">Admin</option>
                </select>
            </div>
            <div class="form-group" id="kelasGroup"><label class="form-label">Kelas</label><input type="text" name="kelas" class="form-input" value="{{ old('kelas') }}" placeholder="7A, 8B, 9C"></div>
            <div class="form-group" id="guruGroup">
                <label class="form-label">Assign ke Guru</label>
                <select name="guru_id" class="form-select"><option value="">-- Pilih Guru --</option>@foreach($guruList as $g)<option value="{{ $g->id }}">{{ $g->name }}</option>@endforeach</select>
            </div>
            <div class="btn-group"><button type="submit" class="btn btn-primary" style="display:inline-flex;align-items:center;gap:6px;"><i data-lucide="save" style="width:18px;height:18px;"></i> Simpan</button><a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Batal</a></div>
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
