@extends('layouts.app')
@section('title', 'Pre-Test')
@section('page-title', 'Pre-Test — Sebelum Program')
@section('content')
<div class="card fade-up" style="border:4px solid var(--accent-pink);border-radius:32px;overflow:hidden">
    <div class="card-header" style="background:var(--accent-pink);border-bottom:none;padding:24px">
        <h3 style="color:var(--dark-900);font-size:24px;text-align:center;width:100%;display:flex;align-items:center;justify-content:center;gap:8px;"><i data-lucide="clipboard-list" style="width:28px;height:28px;"></i> Misi Awal: Ceritain Kebiasaanmu!</h3>
        <p style="color:var(--dark-800);font-size:14px;font-weight:700;text-align:center;margin-top:8px">Jawab jujur ya! Ini buat ngukur kebiasaanmu SEBELUM ikut program ScreenWise <i data-lucide="target" style="width:14px;height:14px;display:inline-block;vertical-align:-2px;"></i></p>
    </div>
    <div class="card-body" style="padding:32px">
        @if($pretest)
        <div class="alert alert-warning" style="border-radius:20px;font-weight:700;display:flex;align-items:center;gap:8px;"><i data-lucide="alert-triangle" style="width:20px;height:20px;"></i> Wah, kamu udah ngisi form ini! Kalau disimpen lagi, data lamamu bakal ke-update ya.</div>
        @endif
        <form method="POST" action="{{ route('siswa.pretest.store') }}" id="pretestForm" novalidate>
            @csrf

            {{-- Section 1: Screen Time (Auto-calculated) --}}
            <div style="background:var(--primary-50);border:3px dashed var(--primary-200);border-radius:24px;padding:24px;margin-bottom:28px">
                <h4 style="color:var(--primary-700);margin-bottom:4px;font-size:17px;display:flex;align-items:center;gap:6px;"><i data-lucide="smartphone" style="width:20px;height:20px;"></i> Berapa total waktu main HP kamu sehari?</h4>
                <p class="form-hint" style="margin-bottom:16px">Isi rincian di bawah → total akan dihitung otomatis. Atau langsung isi total jika tidak ingat detailnya.</p>

                <div class="form-row" style="gap:12px;margin-bottom:16px">
                    <div style="background:white;padding:16px;border-radius:20px;border:3px solid var(--dark-100);text-align:center">
                        <label class="form-label" style="font-size:14px;display:flex;flex-direction:column;align-items:center;gap:4px;"><i data-lucide="smartphone" style="width:20px;height:20px;"></i> Media Sosial<br><span style="font-weight:600;color:var(--dark-500);font-size:12px">(Instagram, TikTok, dll)</span></label>
                        <input type="number" id="pre_sosmed" class="form-input pre-activity" step="0.5" min="0" max="24" placeholder="0" style="text-align:center;font-size:18px;font-weight:900">
                    </div>
                    <div style="background:white;padding:16px;border-radius:20px;border:3px solid var(--dark-100);text-align:center">
                        <label class="form-label" style="font-size:14px;display:flex;flex-direction:column;align-items:center;gap:4px;"><i data-lucide="gamepad-2" style="width:20px;height:20px;"></i> Main Game<br><span style="font-weight:600;color:var(--dark-500);font-size:12px">(ML, PUBG, dll)</span></label>
                        <input type="number" id="pre_game" class="form-input pre-activity" step="0.5" min="0" max="24" placeholder="0" style="text-align:center;font-size:18px;font-weight:900">
                    </div>
                    <div style="background:white;padding:16px;border-radius:20px;border:3px solid var(--dark-100);text-align:center">
                        <label class="form-label" style="font-size:14px;display:flex;flex-direction:column;align-items:center;gap:4px;"><i data-lucide="tv" style="width:20px;height:20px;"></i> Nonton Video<br><span style="font-weight:600;color:var(--dark-500);font-size:12px">(YouTube, Netflix, dll)</span></label>
                        <input type="number" id="pre_video" class="form-input pre-activity" step="0.5" min="0" max="24" placeholder="0" style="text-align:center;font-size:18px;font-weight:900">
                    </div>
                    <div style="background:white;padding:16px;border-radius:20px;border:3px solid var(--dark-100);text-align:center">
                        <label class="form-label" style="font-size:14px;display:flex;flex-direction:column;align-items:center;gap:4px;"><i data-lucide="book" style="width:20px;height:20px;"></i> Belajar<br><span style="font-weight:600;color:var(--dark-500);font-size:12px">(Quipper, Ruangguru, dll)</span></label>
                        <input type="number" id="pre_belajar" class="form-input pre-activity" step="0.5" min="0" max="24" placeholder="0" style="text-align:center;font-size:18px;font-weight:900">
                    </div>
                </div>

                <div class="form-group" style="margin-bottom:0">
                    <label class="form-label" style="font-size:16px;display:flex;align-items:center;gap:6px;">
                        <i data-lucide="clock" style="width:20px;height:20px;"></i> Total Screen Time per Hari (Jam) <span style="color:var(--danger)">*</span>
                        <span id="autoCalcBadge" style="background:var(--accent-green);color:var(--dark-900);font-size:12px;padding:3px 10px;border-radius:20px;margin-left:8px;display:none;align-items:center;gap:4px;"><i data-lucide="hash" style="width:12px;height:12px;"></i> Dihitung otomatis</span>
                    </label>
                    <input type="number" name="avg_screen_time" id="avgScreenTime" class="form-input" step="0.5" min="0" max="24"
                        value="{{ old('avg_screen_time', $pretest?->avg_screen_time) }}" required placeholder="Contoh: 5"
                        style="border-radius:20px;font-size:24px;padding:16px;text-align:center;font-weight:900;border-color:var(--primary-300)">
                    <div class="form-hint" style="text-align:center">Rata-rata jam pemakaian HP per hari SEBELUM ikut program</div>
                    <div class="form-error" id="stError" style="display:none;text-align:center;margin-top:8px"></div>
                </div>
            </div>

            {{-- Section 2: Sleep Schedule --}}
            <div style="background:var(--primary-50);border:3px dashed var(--primary-200);border-radius:24px;padding:24px;margin-bottom:28px">
                <h4 style="color:var(--primary-700);margin-bottom:4px;font-size:17px;display:flex;align-items:center;gap:6px;"><i data-lucide="moon" style="width:20px;height:20px;"></i> Jam tidur & bangunmu sehari-hari</h4>
                <p class="form-hint" style="margin-bottom:16px">Rata-rata jam tidur dan bangun kamu pada hari sekolah (sebelum program ini)</p>
                <div class="form-row" style="gap:24px">
                    <div class="form-group" style="flex:1;margin-bottom:0">
                        <label class="form-label" style="font-size:16px;display:flex;align-items:center;gap:6px;"><i data-lucide="moon" style="width:18px;height:18px;"></i> Biasanya tidur jam berapa? <span style="color:var(--danger)">*</span></label>
                        <input type="time" name="sleep_time" id="sleepTime" class="form-input" value="{{ old('sleep_time', $pretest?->sleep_time) }}" required style="border-radius:20px;padding:16px;font-size:16px;text-align:center">
                        <div class="form-hint">Contoh: tidur jam 11 malam → 23:00</div>
                        <div class="form-error" id="sleepError" style="display:none;text-align:center;margin-top:8px"></div>
                    </div>
                    <div class="form-group" style="flex:1;margin-bottom:0">
                        <label class="form-label" style="font-size:16px;display:flex;align-items:center;gap:6px;"><i data-lucide="sunrise" style="width:18px;height:18px;"></i> Biasanya bangun jam berapa? <span style="color:var(--danger)">*</span></label>
                        <input type="time" name="wake_time" id="wakeTime" class="form-input" value="{{ old('wake_time', $pretest?->wake_time) }}" required style="border-radius:20px;padding:16px;font-size:16px;text-align:center">
                        <div class="form-hint">Contoh: bangun jam 6 pagi → 06:00</div>
                        <div class="form-error" id="wakeError" style="display:none;text-align:center;margin-top:8px"></div>
                    </div>
                </div>
            </div>

            {{-- Section 3: Gadget Habits --}}
            <div style="background:var(--primary-50);border:3px dashed var(--primary-200);border-radius:24px;padding:24px;margin-bottom:28px">
                <h4 style="color:var(--primary-700);margin-bottom:4px;font-size:17px;display:flex;align-items:center;gap:6px;"><i data-lucide="smartphone-nfc" style="width:20px;height:20px;"></i> Kebiasaan digitalmu selama ini</h4>
                <p class="form-hint" style="margin-bottom:16px">Pilih kebiasaan yang sering kamu lakukan. Jujur ya, ini rahasia! <i data-lucide="smile" style="width:14px;height:14px;display:inline-block;vertical-align:-2px;"></i></p>
                <div style="display:grid;grid-template-columns:1fr;gap:12px">
                    @php $habits = [
                        '<i data-lucide="moon" style="display:inline-block;vertical-align:-2px;width:16px;height:16px;"></i> Main HP/game sebelum tidur sampai larut malam',
                        '<i data-lucide="smartphone" style="display:inline-block;vertical-align:-2px;width:16px;height:16px;"></i> Langsung buka sosmed begitu bangun tidur',
                        '<i data-lucide="utensils" style="display:inline-block;vertical-align:-2px;width:16px;height:16px;"></i> Pakai HP saat makan bersama keluarga',
                        '<i data-lucide="book" style="display:inline-block;vertical-align:-2px;width:16px;height:16px;"></i> Susah fokus belajar tanpa lihat HP dulu',
                        '<i data-lucide="film" style="display:inline-block;vertical-align:-2px;width:16px;height:16px;"></i> Nonton YouTube/TikTok lebih dari 3 jam sehari',
                        '<i data-lucide="bed" style="display:inline-block;vertical-align:-2px;width:16px;height:16px;"></i> HP selalu ada di dekat kasur saat tidur',
                        '<i data-lucide="message-circle" style="display:inline-block;vertical-align:-2px;width:16px;height:16px;"></i> Merasa gelisah kalau tidak pegang HP',
                        '<i data-lucide="clock" style="display:inline-block;vertical-align:-2px;width:16px;height:16px;"></i> Sering begadang karena main HP',
                    ]; @endphp
                    @foreach($habits as $h)
                    <label class="form-check" style="background:white;border:3px solid var(--primary-100);padding:16px;border-radius:20px;cursor:pointer">
                        <input type="checkbox" name="gadget_habits[]" value="{{ $h }}"
                            {{ in_array($h, old('gadget_habits', $pretest?->gadget_habits ?? [])) ? 'checked' : '' }}
                            style="width:24px;height:24px;flex-shrink:0">
                        <span style="font-size:15px;font-weight:600;display:flex;align-items:center;gap:6px;">{!! $h !!}</span>
                    </label>
                    @endforeach
                </div>
            </div>

            {{-- Section 4: Notes --}}
            <div class="form-group" style="margin-bottom:32px">
                <label class="form-label" style="font-size:16px;display:flex;align-items:center;gap:6px;"><i data-lucide="message-circle" style="width:20px;height:20px;"></i> Ada cerita lain soal kebiasaan digitalmu? (Opsional)</label>
                <textarea name="notes" class="form-textarea" maxlength="1000"
                    placeholder="Contoh: Aku sering susah tidur karena terlalu lama scroll TikTok. Mata jadi perih tapi susah berhenti..."
                    style="border-radius:20px;padding:16px;font-size:15px;min-height:120px">{{ old('notes', $pretest?->notes) }}</textarea>
                <div class="form-hint" id="notesCount" style="text-align:right">0/1000 karakter</div>
            </div>

            <button type="submit" class="btn btn-lg" id="submitBtn"
                style="background:var(--primary);color:white;width:100%;justify-content:center;box-shadow:0 8px 0 var(--primary-dark);padding:16px;font-size:20px;display:flex;align-items:center;gap:8px;">
                <i data-lucide="rocket" style="width:24px;height:24px;"></i> Simpan Ceritaku!
            </button>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Auto-calculate screen time from activity inputs
const preActivities = document.querySelectorAll('.pre-activity');
const avgInput = document.getElementById('avgScreenTime');
const autoCalcBadge = document.getElementById('autoCalcBadge');

function calcTotal() {
    let sum = 0;
    preActivities.forEach(i => sum += parseFloat(i.value) || 0);
    if (sum > 0) {
        avgInput.value = sum.toFixed(1);
        autoCalcBadge.style.display = 'inline';
    }
}
preActivities.forEach(i => i.addEventListener('input', calcTotal));

// Character counter for notes
const notes = document.querySelector('textarea[name="notes"]');
const notesCount = document.getElementById('notesCount');
notes.addEventListener('input', () => { notesCount.textContent = notes.value.length + '/1000 karakter'; });
if (notes.value) notesCount.textContent = notes.value.length + '/1000 karakter';

// Form validation
document.getElementById('pretestForm').addEventListener('submit', function(e) {
    let valid = true;
    const st = parseFloat(avgInput.value);
    const sleep = document.getElementById('sleepTime').value;
    const wake = document.getElementById('wakeTime').value;

    if (isNaN(st) || st < 0 || st > 24) {
        document.getElementById('stError').textContent = 'Masukkan screen time antara 0-24 jam';
        document.getElementById('stError').style.display = 'block'; valid = false;
    } else { document.getElementById('stError').style.display = 'none'; }

    if (!sleep) {
        document.getElementById('sleepError').textContent = 'Jam tidur wajib diisi';
        document.getElementById('sleepError').style.display = 'block'; valid = false;
    } else { document.getElementById('sleepError').style.display = 'none'; }

    if (!wake) {
        document.getElementById('wakeError').textContent = 'Jam bangun wajib diisi';
        document.getElementById('wakeError').style.display = 'block'; valid = false;
    } else { document.getElementById('wakeError').style.display = 'none'; }

    if (!valid) { e.preventDefault(); window.scrollTo({top:0,behavior:'smooth'}); }
    else { document.getElementById('submitBtn').textContent = '⏳ Menyimpan...'; document.getElementById('submitBtn').disabled = true; }
});
</script>
@endpush
