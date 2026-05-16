@extends('layouts.app')
@section('title', 'Tambah Challenge')
@section('page-title', 'Tambah Challenge')
@section('content')
<div class="card" style="max-width:600px">
    <div class="card-header"><h3>🎯 Form Challenge Baru</h3></div>
    <div class="card-body">
        <form method="POST" action="{{ route('admin.challenges.store') }}">
            @csrf
            <div class="form-group"><label class="form-label">Hari ke-</label><input type="number" name="day_number" class="form-input" min="1" max="7" value="{{ old('day_number') }}" required placeholder="1-7"></div>
            <div class="form-group"><label class="form-label">Judul Challenge</label><input type="text" name="title" class="form-input" value="{{ old('title') }}" required placeholder="Contoh: Digital Detox"></div>
            <div class="form-group"><label class="form-label">Deskripsi</label><textarea name="description" class="form-textarea" required placeholder="Jelaskan challenge...">{{ old('description') }}</textarea></div>
            <div class="form-group"><label class="form-label">Tanggal (opsional)</label><input type="date" name="challenge_date" class="form-input" value="{{ old('challenge_date') }}"></div>
            <div class="btn-group"><button type="submit" class="btn btn-primary">💾 Simpan</button><a href="{{ route('admin.challenges.index') }}" class="btn btn-secondary">Batal</a></div>
        </form>
    </div>
</div>
@endsection
