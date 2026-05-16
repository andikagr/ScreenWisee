@extends('layouts.app')
@section('title', 'Tracking Harian')
@section('page-title', 'Tracking Harian')
@section('content')

@if($existingTracking)
<div class="alert alert-warning" style="border-radius:24px">⚠️ Wah, kamu udah ngisi jurnal hari ini lho! Data bakal ke-update kalau kamu simpan lagi.</div>
@endif

<form method="POST" action="{{ route('siswa.tracking.store') }}" enctype="multipart/form-data" id="trackingForm" novalidate>
    @csrf

    {{-- Step 1: Screen Time --}}
    <div class="card fade-up" style="margin-bottom:32px; border:4px solid var(--primary-100); border-radius:32px">
        <div class="card-header" style="background:var(--primary-50); border-bottom:none">
            <h3 style="color:var(--primary-600); font-size:20px">📱 Waktu Main HP Hari Ini</h3>
            <span class="badge" style="background:var(--primary-200); color:var(--dark-800)">Misi 1/3</span>
        </div>
        <div class="card-body">
            <div class="form-group">
                <label class="form-label" style="font-size:16px">Total Waktu Main (dalam Jam) <span style="color:var(--danger)">*</span></label>
                <input type="number" name="screen_time_hours" id="totalScreenTime" class="form-input" step="0.5" min="0" max="24"
                    value="{{ old('screen_time_hours', $existingTracking?->screen_time_hours) }}" required placeholder="Contoh: 4.5" style="font-size:20px; font-weight:900; text-align:center">
                <div class="form-hint" style="text-align:center">Ketik total jam pemakaian gadget kamu seharian ini (0-24 jam)</div>
                <div class="form-error" id="screenTimeError" style="display:none;text-align:center"></div>
            </div>

            <div class="form-group" style="margin-top:32px">
                <label class="form-label" style="font-size:16px">Ngapain Aja Tuh? (Boleh dikosongin)</label>
                <div class="form-row" style="gap:16px">
                    <div style="background:white; padding:16px; border-radius:20px; border:3px solid var(--dark-100); text-align:center">
                        <label class="form-label" style="font-size:16px">📱 Sosmed</label>
                        <input type="number" name="sosmed" class="form-input activity-input" step="0.5" min="0"
                            value="{{ old('sosmed', $existingTracking?->activities['sosmed'] ?? '') }}" placeholder="0" style="text-align:center">
                    </div>
                    <div style="background:white; padding:16px; border-radius:20px; border:3px solid var(--dark-100); text-align:center">
                        <label class="form-label" style="font-size:16px">🎮 Game</label>
                        <input type="number" name="game" class="form-input activity-input" step="0.5" min="0"
                            value="{{ old('game', $existingTracking?->activities['game'] ?? '') }}" placeholder="0" style="text-align:center">
                    </div>
                    <div style="background:white; padding:16px; border-radius:20px; border:3px solid var(--dark-100); text-align:center">
                        <label class="form-label" style="font-size:16px">📚 Belajar</label>
                        <input type="number" name="belajar" class="form-input activity-input" step="0.5" min="0"
                            value="{{ old('belajar', $existingTracking?->activities['belajar'] ?? '') }}" placeholder="0" style="text-align:center">
                    </div>
                    <div style="background:white; padding:16px; border-radius:20px; border:3px solid var(--dark-100); text-align:center">
                        <label class="form-label" style="font-size:16px">📦 Lainnya</label>
                        <input type="number" name="lainnya" class="form-input activity-input" step="0.5" min="0"
                            value="{{ old('lainnya', $existingTracking?->activities['lainnya'] ?? '') }}" placeholder="0" style="text-align:center">
                    </div>
                </div>
                <div id="activitySum" style="margin-top:16px;font-size:15px;color:var(--dark-500);text-align:center">Total rincian: <strong style="font-size:20px">0</strong> jam</div>
                <div class="form-error" id="activityError" style="display:none;text-align:center"></div>
            </div>
        </div>
    </div>

    {{-- Step 2: Challenge --}}
    @if($challenges->count())
    <div class="card fade-up" style="margin-bottom:32px; border:4px solid var(--accent-yellow); border-radius:32px; animation-delay: 0.1s">
        <div class="card-header" style="background:var(--accent-yellow); border-bottom:none">
            <h3 style="color:var(--dark-900); font-size:20px">🎯 Tantangan Seru Hari Ini</h3>
            <span class="badge" style="background:white; color:var(--dark-900)">Misi 2/3</span>
        </div>
        <div class="card-body">
            <div style="margin-bottom:24px">
                <div style="display:flex;justify-content:space-between;font-size:15px;font-weight:700;margin-bottom:8px">
                    <span>Ceklis tantangan yang udah berhasil kamu lewati!</span>
                    <span id="challengeCount">0/{{ $challenges->count() }}</span>
                </div>
                <div class="progress-bar" style="height:24px;border-radius:20px;background:var(--primary-50)">
                    <div class="progress-fill" id="progressFill" style="width:0%;background:repeating-linear-gradient(45deg, var(--accent-yellow-dark), var(--accent-yellow-dark) 10px, var(--accent-yellow) 10px, var(--accent-yellow) 20px);border-radius:20px"></div>
                </div>
            </div>
            @foreach($challenges as $ch)
            <label class="form-check" style="border:3px solid var(--dark-100); border-radius:20px; padding:20px; background:white; margin-bottom:16px">
                <input type="checkbox" name="challenge_checklist[]" value="{{ $ch->id }}" class="challenge-check" style="width:32px; height:32px"
                    {{ isset($existingTracking) && isset($existingTracking->challenge_checklist[$ch->id]) && $existingTracking->challenge_checklist[$ch->id] ? 'checked' : '' }}>
                <div>
                    <div style="font-weight:800;font-size:16px;color:var(--dark-800)">Hari {{ $ch->day_number }}: {{ $ch->title }}</div>
                    <div style="font-size:14px;color:var(--dark-500);font-weight:600">{{ Str::limit($ch->description, 80) }}</div>
                </div>
            </label>
            @endforeach
        </div>
    </div>
    @endif

    {{-- Step 3: Screenshot --}}
    <div class="card fade-up" style="margin-bottom:32px; border:4px solid var(--accent-pink); border-radius:32px; animation-delay: 0.2s">
        <div class="card-header" style="background:var(--accent-pink); border-bottom:none">
            <h3 style="color:var(--dark-900); font-size:20px">📸 Upload Bukti Screenshot</h3>
            <span class="badge" style="background:white; color:var(--dark-900)">Misi 3/3</span>
        </div>
        <div class="card-body">
            <div class="file-upload" id="dropZone" onclick="document.getElementById('screenshotInput').click()" style="border-color:var(--accent-pink); border-width:4px">
                <div class="file-upload-icon float-anim" style="font-size:64px">🖼️</div>
                <p style="font-size:18px; font-weight:800; color:var(--dark-800)">Pencet atau tarik foto screenshot kamu ke sini</p>
                <p style="font-size:14px;color:var(--dark-500);font-weight:600">Bisa JPG atau PNG (Maks 2MB ya!)</p>
                <div class="file-preview" id="filePreview"></div>
            </div>
            <input type="file" name="screenshot" id="screenshotInput" accept="image/jpeg,image/png" style="display:none">
            <div class="form-error" id="fileError" style="display:none; text-align:center; font-size:16px; margin-top:16px"></div>
        </div>
    </div>

    <div class="btn-group" style="margin-top:16px; display:flex; gap:16px">
        <button type="submit" class="btn btn-lg" id="submitBtn" style="flex:2; background:var(--primary-500); color:white; justify-content:center; box-shadow:0 8px 0 var(--primary-700)">💾 Simpan Jurnalnya!</button>
        <a href="{{ route('siswa.dashboard') }}" class="btn btn-secondary btn-lg" style="flex:1; justify-content:center; box-shadow:0 8px 0 var(--dark-300)">Batal</a>
    </div>
</form>

@endsection
@push('scripts')
<script>
// Activity sum calculation
const activityInputs = document.querySelectorAll('.activity-input');
const totalInput = document.getElementById('totalScreenTime');
const sumDisplay = document.getElementById('activitySum');
const activityError = document.getElementById('activityError');
const screenTimeError = document.getElementById('screenTimeError');

function updateActivitySum() {
    let sum = 0;
    activityInputs.forEach(i => sum += parseFloat(i.value) || 0);
    const total = parseFloat(totalInput.value) || 0;
    sumDisplay.innerHTML = 'Total rincian: <strong>' + sum.toFixed(1) + '</strong> jam';
    if (total > 0 && sum > total) {
        sumDisplay.style.color = 'var(--danger)';
        activityError.textContent = '⚠️ Total rincian melebihi screen time!';
        activityError.style.display = 'block';
    } else {
        sumDisplay.style.color = 'var(--dark-500)';
        activityError.style.display = 'none';
    }
}
activityInputs.forEach(i => i.addEventListener('input', updateActivitySum));
totalInput.addEventListener('input', updateActivitySum);
updateActivitySum();

// Challenge progress bar
const challengeChecks = document.querySelectorAll('.challenge-check');
const progressFill = document.getElementById('progressFill');
const challengeCount = document.getElementById('challengeCount');
const totalChallenges = challengeChecks.length;

function updateProgress() {
    const checked = document.querySelectorAll('.challenge-check:checked').length;
    const pct = totalChallenges > 0 ? (checked / totalChallenges * 100) : 0;
    if (progressFill) { progressFill.style.width = pct + '%'; }
    if (challengeCount) { challengeCount.textContent = checked + '/' + totalChallenges; }
}
challengeChecks.forEach(c => c.addEventListener('change', updateProgress));
updateProgress();

// File upload with drag & drop + validation
const input = document.getElementById('screenshotInput');
const preview = document.getElementById('filePreview');
const dropZone = document.getElementById('dropZone');
const fileError = document.getElementById('fileError');

function validateFile(file) {
    if (!['image/jpeg','image/png'].includes(file.type)) {
        fileError.textContent = '❌ Format tidak valid. Gunakan JPG atau PNG.';
        fileError.style.display = 'block'; return false;
    }
    if (file.size > 2 * 1024 * 1024) {
        fileError.textContent = '❌ File terlalu besar. Maksimal 2MB.';
        fileError.style.display = 'block'; return false;
    }
    fileError.style.display = 'none'; return true;
}

function showPreview(file) {
    if (!validateFile(file)) { preview.innerHTML = ''; return; }
    const reader = new FileReader();
    reader.onload = e => { preview.innerHTML = '<img src="' + e.target.result + '" alt="Preview"><p style="font-size:12px;color:var(--primary-600);margin-top:6px">✅ ' + file.name + ' (' + (file.size/1024).toFixed(0) + 'KB)</p>'; };
    reader.readAsDataURL(file);
}

input.addEventListener('change', function() { if (this.files[0]) showPreview(this.files[0]); });
dropZone.addEventListener('dragover', e => { e.preventDefault(); dropZone.classList.add('dragover'); });
dropZone.addEventListener('dragleave', () => dropZone.classList.remove('dragover'));
dropZone.addEventListener('drop', e => { e.preventDefault(); dropZone.classList.remove('dragover'); if (e.dataTransfer.files[0]) { input.files = e.dataTransfer.files; showPreview(e.dataTransfer.files[0]); } });

// Form validation
document.getElementById('trackingForm').addEventListener('submit', function(e) {
    let valid = true;
    const st = parseFloat(totalInput.value);
    if (isNaN(st) || st < 0 || st > 24) {
        screenTimeError.textContent = '❌ Masukkan screen time antara 0-24 jam.';
        screenTimeError.style.display = 'block'; valid = false;
    } else { screenTimeError.style.display = 'none'; }

    let sum = 0;
    activityInputs.forEach(i => { const v = parseFloat(i.value) || 0; if (v < 0) { valid = false; } sum += v; });
    if (sum > st && st > 0) {
        activityError.textContent = '❌ Total rincian tidak boleh melebihi screen time!';
        activityError.style.display = 'block'; valid = false;
    }

    if (!valid) { e.preventDefault(); window.scrollTo({top:0,behavior:'smooth'}); }
    else { document.getElementById('submitBtn').textContent = '⏳ Menyimpan...'; document.getElementById('submitBtn').disabled = true; }
});
</script>
@endpush
