@extends('layouts.app')
@section('title', 'Edit Challenge')
@section('page-title', 'Edit Challenge')
@section('content')
<div class="card" style="max-width:600px">
    <div class="card-header"><h3 style="display:flex;align-items:center;gap:8px;"><i data-lucide="edit-2"></i> Edit Challenge</h3></div>
    <div class="card-body">
        <form method="POST" action="{{ route('admin.challenges.update', $challenge) }}">
            @csrf @method('PUT')
            <div class="form-group"><label class="form-label">Hari ke-</label><input type="number" name="day_number" class="form-input" min="1" max="7" value="{{ old('day_number', $challenge->day_number) }}" required></div>
            <div class="form-group"><label class="form-label">Judul Challenge</label><input type="text" name="title" class="form-input" value="{{ old('title', $challenge->title) }}" required></div>
            <div class="form-group"><label class="form-label">Deskripsi</label><textarea name="description" class="form-textarea" required>{{ old('description', $challenge->description) }}</textarea></div>
            <div class="form-group"><label class="form-label">Tanggal</label><input type="date" name="challenge_date" class="form-input" value="{{ old('challenge_date', $challenge->challenge_date?->format('Y-m-d')) }}"></div>
            <div class="btn-group"><button type="submit" class="btn btn-primary" style="display:inline-flex;align-items:center;gap:6px;"><i data-lucide="save" style="width:18px;height:18px;"></i> Update</button><a href="{{ route('admin.challenges.index') }}" class="btn btn-secondary">Batal</a></div>
        </form>
    </div>
</div>
@endsection
