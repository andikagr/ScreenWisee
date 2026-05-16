@extends('layouts.guest')
@section('title', 'Login - Masuk Yuk!')
@section('content')
<div class="guest-layout">
    
    <!-- Floating Background Elements -->
    <div class="float-anim" style="position: absolute; top: 15%; left: 15%; font-size: 64px; opacity: 0.3;">🎮</div>
    <div class="float-anim" style="position: absolute; top: 25%; right: 10%; font-size: 80px; opacity: 0.3; animation-delay: 1s;">🌟</div>
    <div class="float-anim" style="position: absolute; bottom: 20%; left: 10%; font-size: 72px; opacity: 0.3; animation-delay: 2s;">🚀</div>
    <div class="float-anim" style="position: absolute; bottom: 15%; right: 20%; font-size: 96px; opacity: 0.3; animation-delay: 1.5s;">📱</div>

    <div class="auth-card fade-up">
        <div class="auth-logo bounce-anim" style="text-align: center; margin-bottom: 32px;">
            <img src="{{ asset('images/logo.svg') }}" alt="ScreenWise" style="width:140px;height:140px;object-fit:contain;margin-bottom:16px; filter: drop-shadow(0 8px 16px rgba(0,0,0,0.15));">
            <h2 style="font-size: 32px; color: var(--primary-600); margin-bottom: 8px;">Yuk, Masuk! 🚀</h2>
            <p style="color: var(--dark-500); font-size: 16px; font-weight: 600;">Lanjutin petualangan digital sehatmu hari ini</p>
        </div>

        @if($errors->any())
        <div class="alert alert-error" style="border-radius: 20px; text-align: center; border: 3px solid var(--danger); background: var(--danger-light);">
            @foreach($errors->all() as $e)<div style="font-weight: 700; color: var(--danger);">😅 Oops! {{ $e }}</div>@endforeach
        </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div class="form-group" style="margin-bottom: 24px;">
                <label class="form-label" style="font-size: 16px; color: var(--dark-800);">Email Kamu</label>
                <input type="email" name="email" class="form-input" value="{{ old('email') }}" required autofocus placeholder="contoh: budi@sekolah.com" style="padding: 16px 20px; font-size: 16px; border-radius: 20px; border: 3px solid var(--primary-200); background: var(--primary-50);">
            </div>
            <div class="form-group" style="margin-bottom: 24px;">
                <label class="form-label" style="font-size: 16px; color: var(--dark-800);">Kata Sandi (Password)</label>
                <input type="password" name="password" class="form-input" required placeholder="Ketik kata sandi rahasiamu" style="padding: 16px 20px; font-size: 16px; border-radius: 20px; border: 3px solid var(--primary-200); background: var(--primary-50);">
            </div>
            <div class="form-group" style="margin-bottom: 32px;">
                <label class="form-check" style="background:transparent;padding:0; border: none; cursor: pointer;">
                    <input type="checkbox" name="remember" style="width:20px;height:20px;"> <span style="font-size:15px;color:var(--dark-600); font-weight: 700;">Ingat aku terus ya!</span>
                </label>
            </div>
            <button type="submit" class="btn btn-primary btn-lg" style="width:100%;justify-content:center; font-size: 20px; padding: 16px;">🎮 Ayo Mulai Main!</button>
        </form>
        <p style="text-align:center;margin-top:32px;font-size:15px;color:var(--dark-500); font-weight: 600;">
            Belum punya akun? <br><a href="{{ route('register') }}" class="pulse-ring" style="display:inline-block; margin-top:12px; color:var(--primary-600);font-weight:900;text-decoration:none; font-size:18px;">Daftar di sini dong! ⭐</a>
        </p>
    </div>
</div>
@endsection
