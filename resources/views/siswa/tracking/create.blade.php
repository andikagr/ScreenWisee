@extends('layouts.app')
@section('title', 'Tracking Harian')
@section('page-title', 'Tracking Harian')
@section('content')

@if($existingTracking)
<div class="alert alert-warning" style="border-radius:24px;display:flex;align-items:center;gap:8px;"><i data-lucide="alert-triangle" style="width:20px;height:20px;"></i> Wah, kamu udah ngisi jurnal hari ini lho! Data bakal ke-update kalau kamu simpan lagi.</div>
@endif

<form method="POST" action="{{ route('siswa.tracking.store') }}" enctype="multipart/form-data" id="trackingForm" novalidate>
    @csrf

    {{-- Step 1: Screen Time (Auto-calculated) --}}
    <div class="card fade-up" style="margin-bottom:32px; border:4px solid var(--primary-100); border-radius:32px">
        <div class="card-header" style="background:var(--primary-50); border-bottom:none">
            <h3 style="color:var(--primary-600); font-size:20px; display:flex; align-items:center; gap:8px;"><i data-lucide="smartphone" style="width:24px;height:24px;"></i> Waktu Main HP Hari Ini</h3>
            <span class="badge" style="background:var(--primary-200); color:var(--dark-800)">Misi 1/3</span>
        </div>
        <div class="card-body">
            {{-- Activity breakdown (drives auto-total) --}}
            <div class="form-group">
                <label class="form-label" style="font-size:16px">Rincian Aktivitas Hari Ini <span style="font-size:13px;color:var(--dark-500);font-weight:600">(isi ini → total dihitung otomatis)</span></label>
                <div class="form-row" style="gap:16px">
                    <div style="background:white; padding:16px; border-radius:20px; border:3px solid var(--dark-100); text-align:center">
                        <label class="form-label" style="font-size:15px;display:flex;flex-direction:column;align-items:center;gap:4px;"><i data-lucide="smartphone" style="width:20px;height:20px;"></i> Sosmed<br><span style="font-size:12px;color:var(--dark-500);font-weight:600">Instagram, TikTok, dll</span></label>
                        <input type="number" name="sosmed" class="form-input activity-input" step="0.5" min="0" max="24"
                            value="{{ old('sosmed', $existingTracking?->activities['sosmed'] ?? '') }}" placeholder="0" style="text-align:center;font-size:18px;font-weight:900">
                    </div>
                    <div style="background:white; padding:16px; border-radius:20px; border:3px solid var(--dark-100); text-align:center">
                        <label class="form-label" style="font-size:15px;display:flex;flex-direction:column;align-items:center;gap:4px;"><i data-lucide="gamepad-2" style="width:20px;height:20px;"></i> Game<br><span style="font-size:12px;color:var(--dark-500);font-weight:600">ML, PUBG, dll</span></label>
                        <input type="number" name="game" class="form-input activity-input" step="0.5" min="0" max="24"
                            value="{{ old('game', $existingTracking?->activities['game'] ?? '') }}" placeholder="0" style="text-align:center;font-size:18px;font-weight:900">
                    </div>
                    <div style="background:white; padding:16px; border-radius:20px; border:3px solid var(--dark-100); text-align:center">
                        <label class="form-label" style="font-size:15px;display:flex;flex-direction:column;align-items:center;gap:4px;"><i data-lucide="book" style="width:20px;height:20px;"></i> Belajar<br><span style="font-size:12px;color:var(--dark-500);font-weight:600">Quipper, Ruangguru, dll</span></label>
                        <input type="number" name="belajar" class="form-input activity-input" step="0.5" min="0" max="24"
                            value="{{ old('belajar', $existingTracking?->activities['belajar'] ?? '') }}" placeholder="0" style="text-align:center;font-size:18px;font-weight:900">
                    </div>
                    <div style="background:white; padding:16px; border-radius:20px; border:3px solid var(--dark-100); text-align:center">
                        <label class="form-label" style="font-size:15px;display:flex;flex-direction:column;align-items:center;gap:4px;"><i data-lucide="package" style="width:20px;height:20px;"></i> Lainnya<br><span style="font-size:12px;color:var(--dark-500);font-weight:600">YouTube, Netflix, dll</span></label>
                        <input type="number" name="lainnya" class="form-input activity-input" step="0.5" min="0" max="24"
                            value="{{ old('lainnya', $existingTracking?->activities['lainnya'] ?? '') }}" placeholder="0" style="text-align:center;font-size:18px;font-weight:900">
                    </div>
                </div>
                <div id="activitySum" style="margin-top:16px;font-size:15px;color:var(--dark-500);text-align:center">Total rincian: <strong style="font-size:20px">0</strong> jam</div>
                <div class="form-error" id="activityError" style="display:none;text-align:center"></div>
            </div>

            {{-- Auto-calculated total --}}
            <div class="form-group" style="margin-top:24px;padding:20px;background:var(--primary-50);border-radius:20px;border:3px dashed var(--primary-300)">
                <label class="form-label" style="font-size:16px;display:flex;align-items:center;gap:6px;"><i data-lucide="clock" style="width:20px;height:20px;"></i> Total Screen Time Hari Ini (Jam) <span style="color:var(--danger)">*</span>
                    <span id="autoCalcBadge" style="background:var(--accent-green);color:var(--dark-900);font-size:12px;padding:3px 10px;border-radius:20px;margin-left:8px;display:none;align-items:center;gap:4px;"><i data-lucide="hash" style="width:12px;height:12px;"></i> Dihitung otomatis</span>
                </label>
                <input type="number" name="screen_time_hours" id="totalScreenTime" class="form-input" step="0.5" min="0" max="24"
                    value="{{ old('screen_time_hours', $existingTracking?->screen_time_hours) }}" required placeholder="Contoh: 4.5"
                    style="font-size:24px; font-weight:900; text-align:center; border-color:var(--primary-400)">
                <div class="form-hint" style="text-align:center">Total jam pemakaian gadget kamu hari ini (dihitung otomatis dari rincian di atas, atau isi manual)</div>
                <div class="form-error" id="screenTimeError" style="display:none;text-align:center"></div>
            </div>
        </div>
    </div>

    {{-- Step 2: Challenge Hari Ini --}}
    @if($challenges->count())
    @php $todayChallenge = $challenges->first(); @endphp
    <div class="card fade-up" style="margin-bottom:32px; border:4px solid var(--accent-yellow); border-radius:32px; animation-delay: 0.1s">
        <div class="card-header" style="background:var(--accent-yellow); border-bottom:none">
            <h3 style="color:var(--dark-900); font-size:20px; display:flex; align-items:center; gap:8px;"><i data-lucide="target" style="width:24px;height:24px;"></i> Tantangan Hari Ini</h3>
            <span class="badge" style="background:white; color:var(--dark-900)">Misi 2/3</span>
        </div>
        <div class="card-body">
            {{-- Info: 1 challenge per day --}}
            <div style="background:white;border:3px solid var(--accent-yellow);border-radius:20px;padding:20px;margin-bottom:20px">
                <div style="display:flex;align-items:flex-start;gap:16px">
                    <div style="flex-shrink:0"><i data-lucide="award" style="width:40px;height:40px;color:var(--accent-yellow-dark);"></i></div>
                    <div>
                        <div style="font-weight:900;font-size:18px;margin-bottom:6px">{{ $todayChallenge->title }}</div>
                        <div style="font-size:15px;font-weight:600;color:var(--dark-500);line-height:1.6">{{ $todayChallenge->description }}</div>
                    </div>
                </div>
            </div>
            <label class="form-check" style="border:3px solid var(--accent-yellow-dark);border-radius:20px;padding:20px;background:var(--primary-50);cursor:pointer">
                <input type="checkbox" name="challenge_checklist[]" value="{{ $todayChallenge->id }}" class="challenge-check" style="width:32px;height:32px;accent-color:var(--accent-yellow-dark)"
                    {{ isset($existingTracking) && isset($existingTracking->challenge_checklist[$todayChallenge->id]) && $existingTracking->challenge_checklist[$todayChallenge->id] ? 'checked' : '' }}>
                <div>
                    <div style="font-weight:900;font-size:17px;display:flex;align-items:center;gap:6px;"><i data-lucide="check-circle-2" style="width:20px;height:20px;color:var(--accent-green-dark);"></i> Saya sudah menyelesaikan tantangan ini hari ini!</div>
                    <div style="font-size:13px;font-weight:600;color:var(--dark-500);margin-top:4px;display:flex;align-items:center;gap:4px;">Centang kalau kamu beneran sudah melakukannya ya, jangan bohong! <i data-lucide="smile" style="width:14px;height:14px;"></i></div>
                </div>
            </label>
            <div class="form-hint" style="text-align:center;margin-top:12px">
                <i data-lucide="calendar" style="width:14px;height:14px;display:inline-block;vertical-align:-2px;"></i> Tantangan ini khusus untuk hari ini — tantangan berbeda akan muncul setiap hari!
            </div>
        </div>
    </div>
    @else
    <div class="card fade-up" style="margin-bottom:32px;border:4px dashed var(--dark-200);border-radius:32px;animation-delay:0.1s">
        <div class="card-body" style="text-align:center;padding:32px">
            <div style="margin-bottom:12px;display:flex;justify-content:center;"><i data-lucide="calendar-x" style="width:48px;height:48px;color:var(--dark-300);"></i></div>
            <div style="font-weight:800;font-size:17px;color:var(--dark-600)">Tidak ada tantangan khusus hari ini</div>
            <div style="font-size:14px;color:var(--dark-400);font-weight:600;margin-top:8px">Tetap isi jurnal harian dan upload screenshotmu ya!</div>
        </div>
    </div>
    @endif

    {{-- Step 3: Screenshot --}}
    <div class="card fade-up" style="margin-bottom:32px; border:4px solid var(--accent-pink); border-radius:32px; animation-delay: 0.2s">
        <div class="card-header" style="background:var(--accent-pink); border-bottom:none">
            <h3 style="color:var(--dark-900); font-size:20px; display:flex; align-items:center; gap:8px;"><i data-lucide="camera" style="width:24px;height:24px;"></i> Upload Bukti Screenshot</h3>
            <span class="badge" style="background:white; color:var(--dark-900)">Misi 3/3</span>
        </div>
        <div class="card-body">
            {{-- Clarification notice --}}
            <div style="background:#fef9c3;border:3px solid #fde047;border-radius:20px;padding:16px;margin-bottom:20px">
                <p style="font-weight:800;font-size:15px;color:#713f12;margin:0;display:flex;align-items:center;gap:6px;"><i data-lucide="lightbulb" style="width:18px;height:18px;"></i> Petunjuk Upload Bukti:</p>
                <ul style="margin:8px 0 0 20px;color:#92400e;font-weight:600;font-size:14px">
                    <li>Upload <strong>1 screenshot</strong> sebagai bukti aktivitas digital kamu hari ini</li>
                    <li>Bisa screenshot dari screen time HP (pengaturan → screen time), atau bukti kamu selesaikan salah satu tantangan</li>
                    <li>Contoh: screenshot layar screen time dari iPhone/Android, atau foto kondisi kamu detoks digital</li>
                    <li>Upload bersifat opsional, tapi membantu guru memverifikasi progressmu <i data-lucide="bar-chart-2" style="width:14px;height:14px;display:inline-block;vertical-align:-2px;"></i></li>
                </ul>
            </div>
            <div class="file-upload" id="dropZone" onclick="document.getElementById('screenshotInput').click()" style="border-color:var(--accent-pink); border-width:4px">
                <div class="file-upload-icon float-anim" style="display:flex;justify-content:center;margin-bottom:12px;"><i data-lucide="image" style="width:64px;height:64px;color:var(--accent-pink-dark);"></i></div>
                <p style="font-size:18px; font-weight:800; color:var(--dark-800)">Klik atau tarik foto screenshot ke sini</p>
                <p style="font-size:14px;color:var(--dark-500);font-weight:600">Format JPG atau PNG — Maksimal 2MB</p>
                <div class="file-preview" id="filePreview"></div>
            </div>
            <input type="file" name="screenshot" id="screenshotInput" accept="image/jpeg,image/png" style="display:none">
            <div class="form-error" id="fileError" style="display:none; text-align:center; font-size:16px; margin-top:16px"></div>
        </div>
    </div>

    <div class="btn-group" style="margin-top:16px; display:flex; gap:16px">
        <button type="submit" class="btn btn-lg" id="submitBtn" style="flex:2; background:var(--primary-500); color:white; justify-content:center; box-shadow:0 8px 0 var(--primary-700);display:flex;align-items:center;gap:8px;"><i data-lucide="save" style="width:20px;height:20px;"></i> Simpan Jurnalnya!</button>
        <a href="{{ route('siswa.dashboard') }}" class="btn btn-secondary btn-lg" style="flex:1; justify-content:center; box-shadow:0 8px 0 var(--dark-300)">Batal</a>
    </div>
</form>

@endsection
@push('scripts')
<script>
// Activity inputs → auto-calculate total
const activityInputs = document.querySelectorAll('.activity-input');
const totalInput = document.getElementById('totalScreenTime');
const sumDisplay = document.getElementById('activitySum');
const activityError = document.getElementById('activityError');
const screenTimeError = document.getElementById('screenTimeError');
const autoCalcBadge = document.getElementById('autoCalcBadge');

function updateActivitySum() {
    let sum = 0;
    activityInputs.forEach(i => sum += parseFloat(i.value) || 0);
    sumDisplay.innerHTML = 'Total rincian: <strong>' + sum.toFixed(1) + '</strong> jam';
    // Auto-fill total if activities are filled
    if (sum > 0) {
        totalInput.value = sum.toFixed(1);
        if (autoCalcBadge) autoCalcBadge.style.display = 'inline';
        sumDisplay.style.color = 'var(--primary-600)';
        activityError.style.display = 'none';
    } else {
        sumDisplay.style.color = 'var(--dark-500)';
        if (autoCalcBadge) autoCalcBadge.style.display = 'none';
    }
}
// Allow manual override of total (clears badge)
totalInput.addEventListener('input', function() {
    if (autoCalcBadge) autoCalcBadge.style.display = 'none';
});
activityInputs.forEach(i => i.addEventListener('input', updateActivitySum));
updateActivitySum();

// Single challenge check — highlight when done
const challengeChecks = document.querySelectorAll('.challenge-check');
challengeChecks.forEach(c => {
    c.addEventListener('change', function() {
        const label = this.closest('label');
        if (this.checked) {
            label.style.background = '#dcfce7';
            label.style.borderColor = 'var(--accent-green-dark)';
        } else {
            label.style.background = 'var(--primary-50)';
            label.style.borderColor = 'var(--accent-yellow-dark)';
        }
    });
    // Init state on load
    if (c.checked) {
        const label = c.closest('label');
        label.style.background = '#dcfce7';
        label.style.borderColor = 'var(--accent-green-dark)';
    }
});

// File upload with drag & drop + validation
const input = document.getElementById('screenshotInput');
const preview = document.getElementById('filePreview');
const dropZone = document.getElementById('dropZone');
const fileError = document.getElementById('fileError');

function validateFile(file) {
    if (!['image/jpeg','image/png'].includes(file.type)) {
        fileError.textContent = 'Format tidak valid. Gunakan JPG atau PNG.';
        fileError.style.display = 'block'; return false;
    }
    if (file.size > 2 * 1024 * 1024) {
        fileError.textContent = 'File terlalu besar. Maksimal 2MB.';
        fileError.style.display = 'block'; return false;
    }
    fileError.style.display = 'none'; return true;
}

function showPreview(file) {
    if (!validateFile(file)) { preview.innerHTML = ''; return; }
    const reader = new FileReader();
    reader.onload = e => { preview.innerHTML = '<img src="' + e.target.result + '" alt="Preview"><p style="font-size:12px;color:var(--primary-600);margin-top:6px">' + file.name + ' (' + (file.size/1024).toFixed(0) + 'KB)</p>'; };
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
        screenTimeError.textContent = 'Masukkan screen time antara 0-24 jam.';
        screenTimeError.style.display = 'block'; valid = false;
    } else { screenTimeError.style.display = 'none'; }

    let sum = 0;
    activityInputs.forEach(i => { const v = parseFloat(i.value) || 0; if (v < 0) { valid = false; } sum += v; });
    if (sum > st && st > 0) {
        activityError.textContent = 'Total rincian tidak boleh melebihi screen time!';
        activityError.style.display = 'block'; valid = false;
    }

    if (!valid) { e.preventDefault(); window.scrollTo({top:0,behavior:'smooth'}); }
    else { document.getElementById('submitBtn').textContent = '⏳ Menyimpan...'; document.getElementById('submitBtn').disabled = true; }
});
</script>
@endpush
