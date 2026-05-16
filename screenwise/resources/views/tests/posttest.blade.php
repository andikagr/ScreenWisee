@extends('layouts.app')
@section('title', 'Post-Test')
@section('page-title', 'Post-Test')
@section('content')
<div class="card fade-up" style="border:4px solid var(--accent-green);border-radius:32px;overflow:hidden">
    <div class="card-header" style="background:var(--accent-green);border-bottom:none;padding:24px">
        <h3 style="color:var(--dark-900);font-size:24px;text-align:center;width:100%">🏆 Misi Akhir: Buktikan Kehebatanmu!</h3>
    </div>
    <div class="card-body" style="padding:32px">
        @if($posttest)
        <div class="alert alert-warning" style="border-radius:20px;font-weight:700">⚠️ Wah, kamu udah ngisi form ini! Kalau disimpen lagi, data lamamu bakal ke-update ya.</div>
        @endif
        <form method="POST" action="{{ route('siswa.posttest.store') }}" id="posttestForm" novalidate>
            @csrf
            <div class="form-group" style="margin-bottom:24px">
                <label class="form-label" style="font-size:16px;color:var(--dark-800)">Sehari biasanya main HP berapa jam? (Setelah ikut program ini ya!) <span style="color:var(--danger)">*</span></label>
                <input type="number" name="avg_screen_time" id="avgScreenTime" class="form-input" step="0.5" min="0" max="24" value="{{ old('avg_screen_time', $posttest?->avg_screen_time) }}" required placeholder="Misalnya: 3" style="border-radius:20px;font-size:18px;padding:16px;text-align:center">
                <div class="form-error" id="stError" style="display:none;text-align:center;margin-top:8px"></div>
            </div>
            <div class="form-row" style="gap:24px;margin-bottom:24px">
                <div class="form-group" style="flex:1">
                    <label class="form-label" style="font-size:16px;color:var(--dark-800)">😴 Sekarang tidur jam berapa? <span style="color:var(--danger)">*</span></label>
                    <input type="time" name="sleep_time" id="sleepTime" class="form-input" value="{{ old('sleep_time', $posttest?->sleep_time) }}" required style="border-radius:20px;padding:16px;font-size:16px;text-align:center">
                    <div class="form-error" id="sleepError" style="display:none;text-align:center;margin-top:8px"></div>
                </div>
                <div class="form-group" style="flex:1">
                    <label class="form-label" style="font-size:16px;color:var(--dark-800)">🌅 Sekarang bangun jam berapa? <span style="color:var(--danger)">*</span></label>
                    <input type="time" name="wake_time" id="wakeTime" class="form-input" value="{{ old('wake_time', $posttest?->wake_time) }}" required style="border-radius:20px;padding:16px;font-size:16px;text-align:center">
                    <div class="form-error" id="wakeError" style="display:none;text-align:center;margin-top:8px"></div>
                </div>
            </div>
            <div class="form-group" style="margin-bottom:32px">
                <label class="form-label" style="font-size:16px;color:var(--dark-800)">Sekarang ngapain aja kalau main HP? (Ceklis yang masih kamu lakuin)</label>
                <div style="display:grid;grid-template-columns:1fr;gap:12px">
                    @php $habits = ['Main game sebelum tidur','Scroll sosial media pas baru bangun','Gunakan HP pas lagi makan','Belajar pakai HP/Laptop','Nonton Youtube/Tiktok berjam-jam','HP selalu ditaruh di sebelah kasur']; @endphp
                    @foreach($habits as $h)
                    <label class="form-check" style="background:var(--primary-50);border:3px solid var(--primary-100);padding:16px;border-radius:20px;cursor:pointer">
                        <input type="checkbox" name="gadget_habits[]" value="{{ $h }}" {{ in_array($h, old('gadget_habits', $posttest?->gadget_habits ?? [])) ? 'checked' : '' }} style="width:24px;height:24px">
                        <span style="font-size:15px;font-weight:600;color:var(--dark-800)">{{ $h }}</span>
                    </label>
                    @endforeach
                </div>
            </div>
            <div class="form-group" style="margin-bottom:32px">
                <label class="form-label" style="font-size:16px;color:var(--dark-800)">Gimana perasaanmu setelah ikut program ini?</label>
                <textarea name="notes" class="form-textarea" maxlength="1000" placeholder="Contoh: Aku jadi lebih gampang tidur dan nggak pusing lagi..." style="border-radius:20px;padding:16px;font-size:15px;min-height:120px">{{ old('notes', $posttest?->notes) }}</textarea>
                <div class="form-hint" id="notesCount" style="text-align:right">0/1000 huruf</div>
            </div>
            <button type="submit" class="btn btn-lg" id="submitBtn" style="background:var(--accent-green-dark);color:white;width:100%;justify-content:center;box-shadow:0 8px 0 #166534;padding:16px;font-size:20px">🌟 Selesaikan Misiku!</button>
        </form>
    </div>
</div>
@endsection
@push('scripts')
<script>
const form = document.getElementById('posttestForm');
const notes = document.querySelector('textarea[name="notes"]');
const notesCount = document.getElementById('notesCount');
notes.addEventListener('input', () => { notesCount.textContent = notes.value.length + '/1000 karakter'; });
if (notes.value) notesCount.textContent = notes.value.length + '/1000 karakter';

form.addEventListener('submit', function(e) {
    let valid = true;
    const st = parseFloat(document.getElementById('avgScreenTime').value);
    const sleep = document.getElementById('sleepTime').value;
    const wake = document.getElementById('wakeTime').value;

    if (isNaN(st) || st < 0 || st > 24) {
        document.getElementById('stError').textContent = '❌ Masukkan screen time antara 0-24 jam';
        document.getElementById('stError').style.display = 'block'; valid = false;
    } else { document.getElementById('stError').style.display = 'none'; }

    if (!sleep) {
        document.getElementById('sleepError').textContent = '❌ Jam tidur wajib diisi';
        document.getElementById('sleepError').style.display = 'block'; valid = false;
    } else { document.getElementById('sleepError').style.display = 'none'; }

    if (!wake) {
        document.getElementById('wakeError').textContent = '❌ Jam bangun wajib diisi';
        document.getElementById('wakeError').style.display = 'block'; valid = false;
    } else { document.getElementById('wakeError').style.display = 'none'; }

    if (!valid) { e.preventDefault(); window.scrollTo({top:0,behavior:'smooth'}); }
    else { document.getElementById('submitBtn').textContent = '⏳ Menyimpan...'; document.getElementById('submitBtn').disabled = true; }
});
</script>
@endpush
