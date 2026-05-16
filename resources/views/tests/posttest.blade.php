@extends('layouts.app')
@section('title', 'Post-Test')
@section('page-title', 'Post-Test — Setelah Program')
@section('content')
<div class="card fade-up" style="border:4px solid var(--accent-green);border-radius:32px;overflow:hidden">
    <div class="card-header" style="background:var(--accent-green);border-bottom:none;padding:24px">
        <h3 style="color:var(--dark-900);font-size:24px;text-align:center;width:100%;display:flex;align-items:center;justify-content:center;gap:8px;"><i data-lucide="award" style="width:28px;height:28px;"></i> Misi Akhir: Buktikan Kehebatanmu!</h3>
        <p style="color:var(--dark-800);font-size:14px;font-weight:700;text-align:center;margin-top:8px">Setelah 7 hari program, sekarang jawab lagi ya! Bandingkan dengan kebiasaanmu dulu <i data-lucide="refresh-cw" style="width:14px;height:14px;display:inline-block;vertical-align:-2px;"></i></p>
    </div>
    <div class="card-body" style="padding:32px">
        @if($posttest)
        <div class="alert alert-warning" style="border-radius:20px;font-weight:700;display:flex;align-items:center;gap:8px;"><i data-lucide="alert-triangle" style="width:20px;height:20px;"></i> Wah, kamu udah ngisi form ini! Kalau disimpen lagi, data lamamu bakal ke-update ya.</div>
        @endif
        <form method="POST" action="{{ route('siswa.posttest.store') }}" id="posttestForm" novalidate>
            @csrf

            {{-- Section 1: Screen Time (Auto-calculated) --}}
            <div style="background:var(--primary-50);border:3px dashed var(--primary-200);border-radius:24px;padding:24px;margin-bottom:28px">
                <h4 style="color:var(--primary-700);margin-bottom:4px;font-size:17px;display:flex;align-items:center;gap:6px;"><i data-lucide="smartphone" style="width:20px;height:20px;"></i> Sekarang berapa lama kamu main HP sehari?</h4>
                <p class="form-hint" style="margin-bottom:16px">Isi rincian di bawah → total akan dihitung otomatis. Bandingkan dengan sebelum program ya!</p>

                <div class="form-row" style="gap:12px;margin-bottom:16px">
                    <div style="background:white;padding:16px;border-radius:20px;border:3px solid var(--dark-100);text-align:center">
                        <label class="form-label" style="font-size:14px;display:flex;flex-direction:column;align-items:center;gap:4px;"><i data-lucide="smartphone" style="width:20px;height:20px;"></i> Media Sosial<br><span style="font-weight:600;color:var(--dark-500);font-size:12px">(Instagram, TikTok, dll)</span></label>
                        <input type="number" id="post_sosmed" class="form-input post-activity" step="0.5" min="0" max="24" placeholder="0" style="text-align:center;font-size:18px;font-weight:900">
                    </div>
                    <div style="background:white;padding:16px;border-radius:20px;border:3px solid var(--dark-100);text-align:center">
                        <label class="form-label" style="font-size:14px;display:flex;flex-direction:column;align-items:center;gap:4px;"><i data-lucide="gamepad-2" style="width:20px;height:20px;"></i> Main Game<br><span style="font-weight:600;color:var(--dark-500);font-size:12px">(ML, PUBG, dll)</span></label>
                        <input type="number" id="post_game" class="form-input post-activity" step="0.5" min="0" max="24" placeholder="0" style="text-align:center;font-size:18px;font-weight:900">
                    </div>
                    <div style="background:white;padding:16px;border-radius:20px;border:3px solid var(--dark-100);text-align:center">
                        <label class="form-label" style="font-size:14px;display:flex;flex-direction:column;align-items:center;gap:4px;"><i data-lucide="tv" style="width:20px;height:20px;"></i> Nonton Video<br><span style="font-weight:600;color:var(--dark-500);font-size:12px">(YouTube, Netflix, dll)</span></label>
                        <input type="number" id="post_video" class="form-input post-activity" step="0.5" min="0" max="24" placeholder="0" style="text-align:center;font-size:18px;font-weight:900">
                    </div>
                    <div style="background:white;padding:16px;border-radius:20px;border:3px solid var(--dark-100);text-align:center">
                        <label class="form-label" style="font-size:14px;display:flex;flex-direction:column;align-items:center;gap:4px;"><i data-lucide="book" style="width:20px;height:20px;"></i> Belajar<br><span style="font-weight:600;color:var(--dark-500);font-size:12px">(Quipper, Ruangguru, dll)</span></label>
                        <input type="number" id="post_belajar" class="form-input post-activity" step="0.5" min="0" max="24" placeholder="0" style="text-align:center;font-size:18px;font-weight:900">
                    </div>
                </div>

                <div class="form-group" style="margin-bottom:0">
                    <label class="form-label" style="font-size:16px;display:flex;align-items:center;gap:6px;">
                        <i data-lucide="clock" style="width:20px;height:20px;"></i> Total Screen Time per Hari SEKARANG (Jam) <span style="color:var(--danger)">*</span>
                        <span id="autoCalcBadge" style="background:var(--accent-green);color:var(--dark-900);font-size:12px;padding:3px 10px;border-radius:20px;margin-left:8px;display:none;align-items:center;gap:4px;"><i data-lucide="hash" style="width:12px;height:12px;"></i> Dihitung otomatis</span>
                    </label>
                    <input type="number" name="avg_screen_time" id="avgScreenTime" class="form-input" step="0.5" min="0" max="24"
                        value="{{ old('avg_screen_time', $posttest?->avg_screen_time) }}" required placeholder="Contoh: 3"
                        style="border-radius:20px;font-size:24px;padding:16px;text-align:center;font-weight:900;border-color:var(--accent-green-dark)">
                    <div class="form-hint" style="text-align:center">Rata-rata jam pemakaian HP per hari SETELAH ikut program</div>
                    <div class="form-error" id="stError" style="display:none;text-align:center;margin-top:8px"></div>
                </div>
            </div>

            {{-- Section 2: Sleep Schedule --}}
            <div style="background:var(--primary-50);border:3px dashed var(--primary-200);border-radius:24px;padding:24px;margin-bottom:28px">
                <h4 style="color:var(--primary-700);margin-bottom:4px;font-size:17px;display:flex;align-items:center;gap:6px;"><i data-lucide="moon" style="width:20px;height:20px;"></i> Jam tidur & bangunmu sekarang</h4>
                <p class="form-hint" style="margin-bottom:16px">Apakah jam tidurmu sudah berubah setelah ikut program? Isi jujur ya!</p>
                <div class="form-row" style="gap:24px">
                    <div class="form-group" style="flex:1;margin-bottom:0">
                        <label class="form-label" style="font-size:16px;display:flex;align-items:center;gap:6px;"><i data-lucide="moon" style="width:18px;height:18px;"></i> Sekarang tidur jam berapa? <span style="color:var(--danger)">*</span></label>
                        <input type="time" name="sleep_time" id="sleepTime" class="form-input" value="{{ old('sleep_time', $posttest?->sleep_time) }}" required style="border-radius:20px;padding:16px;font-size:16px;text-align:center">
                        <div class="form-hint">Contoh: tidur jam 10 malam → 22:00</div>
                        <div class="form-error" id="sleepError" style="display:none;text-align:center;margin-top:8px"></div>
                    </div>
                    <div class="form-group" style="flex:1;margin-bottom:0">
                        <label class="form-label" style="font-size:16px;display:flex;align-items:center;gap:6px;"><i data-lucide="sunrise" style="width:18px;height:18px;"></i> Sekarang bangun jam berapa? <span style="color:var(--danger)">*</span></label>
                        <input type="time" name="wake_time" id="wakeTime" class="form-input" value="{{ old('wake_time', $posttest?->wake_time) }}" required style="border-radius:20px;padding:16px;font-size:16px;text-align:center">
                        <div class="form-hint">Contoh: bangun jam 5.30 pagi → 05:30</div>
                        <div class="form-error" id="wakeError" style="display:none;text-align:center;margin-top:8px"></div>
                    </div>
                </div>
            </div>

            {{-- Section 3: Gadget Habits (After Program) --}}
            <div style="background:var(--primary-50);border:3px dashed var(--primary-200);border-radius:24px;padding:24px;margin-bottom:28px">
                <h4 style="color:var(--primary-700);margin-bottom:4px;font-size:17px;display:flex;align-items:center;gap:6px;"><i data-lucide="smartphone-nfc" style="width:20px;height:20px;"></i> Kebiasaan digitalmu SEKARANG (setelah program)</h4>
                <p class="form-hint" style="margin-bottom:16px">Pilih kebiasaan yang MASIH kamu lakukan setelah ikut program. Apa yang sudah berubah? <i data-lucide="dumbbell" style="width:14px;height:14px;display:inline-block;vertical-align:-2px;"></i></p>
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
                            {{ in_array($h, old('gadget_habits', $posttest?->gadget_habits ?? [])) ? 'checked' : '' }}
                            style="width:24px;height:24px;flex-shrink:0">
                        <span style="font-size:15px;font-weight:600;display:flex;align-items:center;gap:6px;">{!! $h !!}</span>
                    </label>
                    @endforeach
                </div>
            </div>

            {{-- Section 4: Reflection --}}
            <div class="form-group" style="margin-bottom:32px">
                <label class="form-label" style="font-size:16px;display:flex;align-items:center;gap:6px;"><i data-lucide="message-circle" style="width:20px;height:20px;"></i> Gimana perasaanmu setelah ikut program ini? (Opsional)</label>
                <p class="form-hint" style="margin-bottom:8px">Ceritakan perubahan yang kamu rasakan — sekecil apapun itu tetap berarti! <i data-lucide="star" style="width:14px;height:14px;display:inline-block;vertical-align:-2px;"></i></p>
                <textarea name="notes" class="form-textarea" maxlength="1000"
                    placeholder="Contoh: Aku jadi lebih gampang tidur dan tidak pusing lagi. Screen time-ku turun dari 7 jam jadi 3 jam sehari..."
                    style="border-radius:20px;padding:16px;font-size:15px;min-height:120px">{{ old('notes', $posttest?->notes) }}</textarea>
                <div class="form-hint" id="notesCount" style="text-align:right">0/1000 karakter</div>
            </div>

            <button type="submit" class="btn btn-lg" id="submitBtn"
                style="background:var(--primary);color:white;width:100%;justify-content:center;box-shadow:0 8px 0 var(--primary-dark);padding:16px;font-size:20px;display:flex;align-items:center;gap:8px;">
                <i data-lucide="star" style="width:24px;height:24px;"></i> Selesaikan Misiku!
            </button>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Auto-calculate screen time from activity inputs
const postActivities = document.querySelectorAll('.post-activity');
const avgInput = document.getElementById('avgScreenTime');
const autoCalcBadge = document.getElementById('autoCalcBadge');

function calcTotal() {
    let sum = 0;
    postActivities.forEach(i => sum += parseFloat(i.value) || 0);
    if (sum > 0) {
        avgInput.value = sum.toFixed(1);
        autoCalcBadge.style.display = 'inline';
    }
}
postActivities.forEach(i => i.addEventListener('input', calcTotal));

// Character counter for notes
const notes = document.querySelector('textarea[name="notes"]');
const notesCount = document.getElementById('notesCount');
notes.addEventListener('input', () => { notesCount.textContent = notes.value.length + '/1000 karakter'; });
if (notes.value) notesCount.textContent = notes.value.length + '/1000 karakter';

// Form validation
document.getElementById('posttestForm').addEventListener('submit', function(e) {
    let valid = true;
    const st = parseFloat(document.getElementById('avgScreenTime').value);
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
