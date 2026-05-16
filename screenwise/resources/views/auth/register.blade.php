@extends('layouts.guest')
@section('title', 'Daftar Yuk!')
@section('content')
<div class="guest-layout">
    
    <!-- Floating Background Elements -->
    <div class="float-anim" style="position: absolute; top: 15%; right: 15%; font-size: 64px; opacity: 0.3;">🎉</div>
    <div class="float-anim" style="position: absolute; top: 25%; left: 10%; font-size: 80px; opacity: 0.3; animation-delay: 1s;">💡</div>
    <div class="float-anim" style="position: absolute; bottom: 20%; right: 10%; font-size: 72px; opacity: 0.3; animation-delay: 2s;">🏆</div>
    <div class="float-anim" style="position: absolute; bottom: 15%; left: 20%; font-size: 96px; opacity: 0.3; animation-delay: 1.5s;">🚀</div>

    <div class="auth-card fade-up">
        <div class="auth-logo float-anim" style="text-align: center; margin-bottom: 32px;">
            <img src="{{ asset('images/logo.svg') }}" alt="ScreenWise" style="width:140px;height:140px;object-fit:contain;margin-bottom:16px; filter: drop-shadow(0 8px 16px rgba(0,0,0,0.15));">
            <h2 style="font-size: 32px; color: var(--accent-pink-dark); margin-bottom: 8px;">Daftar Baru 🌟</h2>
            <p style="color: var(--dark-500); font-size: 16px; font-weight: 600;">Ayo gabung bareng teman-teman lainnya!</p>
        </div>

        @if($errors->any())
        <div class="alert alert-error" style="border-radius: 20px; text-align: center; border: 3px solid var(--danger); background: var(--danger-light);">
            @foreach($errors->all() as $e)<div style="font-weight: 700; color: var(--danger);">😅 Oops! {{ $e }}</div>@endforeach
        </div>
        @endif

        <form method="POST" action="{{ route('register') }}">
            @csrf
            <div class="form-group" style="margin-bottom: 20px;">
                <label class="form-label" style="font-size: 16px; color: var(--dark-800);">Nama Lengkapmu Siapa?</label>
                <input type="text" name="name" class="form-input" value="{{ old('name') }}" required placeholder="Ketik nama lengkapmu di sini" style="padding: 16px 20px; font-size: 16px; border-radius: 20px; border: 3px solid var(--primary-200); background: var(--primary-50);">
            </div>
            <div class="form-group" style="margin-bottom: 20px;">
                <label class="form-label" style="font-size: 16px; color: var(--dark-800);">Email Kamu</label>
                <input type="email" name="email" class="form-input" value="{{ old('email') }}" required placeholder="contoh: budi@sekolah.com" style="padding: 16px 20px; font-size: 16px; border-radius: 20px; border: 3px solid var(--primary-200); background: var(--primary-50);">
            </div>
            <div class="form-group" style="margin-bottom: 20px;">
                <label class="form-label" style="font-size: 16px; color: var(--dark-800);">Kelas Berapa?</label>
                <input type="text" name="kelas" class="form-input" value="{{ old('kelas') }}" required placeholder="Contoh: 7A, 8B" style="padding: 16px 20px; font-size: 16px; border-radius: 20px; border: 3px solid var(--primary-200); background: var(--primary-50);">
            </div>
            <div class="form-row" style="margin-bottom: 32px;">
                <div class="form-group" style="margin-bottom: 0;">
                    <label class="form-label" style="font-size: 16px; color: var(--dark-800);">Bikin Kata Sandi</label>
                    <input type="password" name="password" class="form-input" required placeholder="Min. 8 huruf/angka" style="padding: 16px 20px; font-size: 16px; border-radius: 20px; border: 3px solid var(--primary-200); background: var(--primary-50);">
                </div>
                <div class="form-group" style="margin-bottom: 0;">
                    <label class="form-label" style="font-size: 16px; color: var(--dark-800);">Ketik Ulang Sandi</label>
                    <input type="password" name="password_confirmation" class="form-input" required placeholder="Harus sama persis" style="padding: 16px 20px; font-size: 16px; border-radius: 20px; border: 3px solid var(--primary-200); background: var(--primary-50);">
                </div>
            </div>
            <button type="submit" class="btn btn-primary btn-lg pulse-ring" style="width:100%; justify-content:center; font-size: 20px; padding: 16px;">🎉 Yuk, Gabung!</button>
        </form>
        <p style="text-align:center;margin-top:32px;font-size:15px;color:var(--dark-500); font-weight: 600;">
            Sudah punya akun? <br><a href="{{ route('login') }}" class="bounce-anim" style="display:inline-block; margin-top:12px; color:var(--primary-600);font-weight:900;text-decoration:none; font-size:18px;">Masuk lewat sini aja! 🚀</a>
        </p>
    </div>
</div>
@endsection
