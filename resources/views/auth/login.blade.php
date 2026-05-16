@extends('layouts.guest')
@section('title', 'Masuk - ScreenWise')
@section('content')

<style>
    .login-container {
        display: flex;
        min-height: 100vh;
        background-color: #c0d8c2ff;
    }
    .login-left {
        flex: 1;
        background: linear-gradient(135deg, #c0d8c2ff 0%, #c0d8c2ff 100%);
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        position: relative;
        overflow: hidden;
        padding: 40px;
    }
    .login-right {
        flex: 1;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        padding: 40px;
        background: white;
        position: relative;
    }
    .auth-card-modern {
        width: 100%;
        max-width: 420px;
        background: white;
        padding: 40px;
        border-radius: 24px;
        box-shadow: 0 20px 40px rgba(0,0,0,0.04);
        border: 1px solid #c0d8c2ff;
        position: relative;
        z-index: 10;
    }
    .glass-shape {
        position: absolute;
        background: rgba(255, 255, 255, 0.4);
        backdrop-filter: blur(10px);
        border-radius: 50%;
        z-index: 1;
    }
    
    @media (max-width: 992px) {
        .login-left { display: none; }
        .login-right { background: #c0d8c2ff; }
    }
    
    @keyframes float1 {
        0% { transform: translateY(0px) rotate(0deg); }
        50% { transform: translateY(-15px) rotate(5deg); }
        100% { transform: translateY(0px) rotate(0deg); }
    }
    @keyframes float2 {
        0% { transform: translateY(0px) rotate(0deg); }
        50% { transform: translateY(-10px) rotate(-5deg); }
        100% { transform: translateY(0px) rotate(0deg); }
    }
</style>

<div class="login-container">
    <!-- Left Side: Visual -->
    <div class="login-left">
        <div class="glass-shape" style="width: 300px; height: 300px; top: -50px; left: -50px;"></div>
        <div class="glass-shape" style="width: 400px; height: 400px; bottom: -100px; right: -50px; background: rgba(34, 197, 94, 0.15);"></div>
        
        <div class="fade-up" style="position: relative; z-index: 10; text-align: center; max-width: 400px;">
            <div style="background: white; padding: 12px; border-radius: 28px; display: inline-block; margin-bottom: 32px; box-shadow: 0 20px 40px rgba(15, 23, 42, 0.1);">
                <img src="{{ asset('images/kid_screenwise.png') }}" alt="Illustration" style="width: 300px; height: auto; border-radius: 20px; display: block;">
            </div>
            <h2 style="font-size: 32px; font-weight: 900; color: #1e293b; margin-bottom: 16px; letter-spacing: -1px;">
                Mulai Kebiasaan<br>Digital Sehatmu!
            </h2>
            <p style="color: #64748b; font-size: 16px; line-height: 1.6; font-weight: 500;">
                Masuk untuk mencatat screen time, menyelesaikan tantangan harian, dan memantau kemajuanmu.
            </p>
        </div>
    </div>

    <!-- Right Side: Form -->
    <div class="login-right">
        <!-- Floating emojis for fun -->
        <div style="position: absolute; top: 15%; right: 15%; opacity: 0.5; animation: float1 4s ease-in-out infinite; color: var(--primary);"><i data-lucide="rocket" style="width:48px;height:48px;"></i></div>
        <div style="position: absolute; bottom: 15%; left: 15%; opacity: 0.5; animation: float2 5s ease-in-out infinite; color: var(--primary);"><i data-lucide="sparkles" style="width:40px;height:40px;"></i></div>

        <a href="{{ route('home') }}" style="position: absolute; top: 40px; left: 40px; display: flex; align-items: center; gap: 8px; color: #64748b; text-decoration: none; font-weight: 600; font-size: 14px; transition: color 0.2s;" onmouseover="this.style.color='#06900dff'" onmouseout="this.style.color='#64748b'">
            <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round"><path d="M15 19l-7-7 7-7"></path></svg>
            Kembali ke Beranda
        </a>

        <div class="auth-card-modern fade-up" style="animation-delay: 0.1s;">
            <div style="text-align: center; margin-bottom: 32px;">
                <div style="width: 56px; height: 56px; background: #dcfce7; border-radius: 16px; display: inline-flex; align-items: center; justify-content: center; margin-bottom: 16px; box-shadow: 0 8px 16px rgba(6, 144, 13, 0.1);">
                    <img src="{{ asset('images/logo.svg') }}" alt="ScreenWise" style="width: 32px; height: 32px; object-fit: contain;">
                </div>
                <h2 style="font-size: 26px; font-weight: 900; color: #1e293b; margin-bottom: 8px;">Selamat Datang!</h2>
                <p style="color: #64748b; font-size: 14px; font-weight: 600;">Masuk ke akun ScreenWise kamu</p>
            </div>

            @if($errors->any())
            <div style="background: #fee2e2; border: 1px solid #fca5a5; color: #ef4444; padding: 12px 16px; border-radius: 12px; font-size: 13px; font-weight: 600; margin-bottom: 24px;">
                @foreach($errors->all() as $e)
                    <div>• {{ $e }}</div>
                @endforeach
            </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf
                <div style="margin-bottom: 20px;">
                    <label style="display: block; font-size: 13px; font-weight: 800; color: #475569; margin-bottom: 8px;">Alamat Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" required autofocus
                           style="width: 100%; padding: 14px 16px; border-radius: 12px; border: 2px solid #e2e8f0; background: #f8fafc; font-size: 14px; font-weight: 500; color: #1e293b; transition: all 0.2s; outline: none; box-sizing: border-box;"
                           onfocus="this.style.borderColor='#06900d'; this.style.backgroundColor='white'; this.style.boxShadow='0 0 0 4px rgba(6, 144, 13, 0.1)'"
                           onblur="this.style.borderColor='#e2e8f0'; this.style.backgroundColor='#f8fafc'; this.style.boxShadow='none'"
                           placeholder="nama@sekolah.com">
                </div>
                <div style="margin-bottom: 20px;">
                    <label style="display: block; font-size: 13px; font-weight: 800; color: #475569; margin-bottom: 8px;">Password</label>
                    <input type="password" name="password" required
                           style="width: 100%; padding: 14px 16px; border-radius: 12px; border: 2px solid #e2e8f0; background: #f8fafc; font-size: 14px; font-weight: 500; color: #1e293b; transition: all 0.2s; outline: none; box-sizing: border-box;"
                           onfocus="this.style.borderColor='#06900d'; this.style.backgroundColor='white'; this.style.boxShadow='0 0 0 4px rgba(6, 144, 13, 0.1)'"
                           onblur="this.style.borderColor='#e2e8f0'; this.style.backgroundColor='#f8fafc'; this.style.boxShadow='none'"
                           placeholder="••••••••">
                </div>
                <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 32px;">
                    <label style="display:flex;align-items:center;gap:8px;cursor:pointer;font-size:13px;font-weight:600;color:#64748b;">
                        <input type="checkbox" name="remember" style="width:16px;height:16px;accent-color:#06900d; border-radius: 4px; cursor: pointer;">
                        Ingat saya
                    </label>
                </div>
                <button type="submit" style="width:100%; padding: 16px; background: linear-gradient(135deg, #057a0b 0%, #06900d 100%); color: white; border: none; border-radius: 14px; font-size: 15px; font-weight: 800; cursor: pointer; box-shadow: 0 10px 25px rgba(6, 144, 13, 0.3); transition: all 0.3s; margin-bottom: 24px;"
                        onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 15px 30px rgba(6, 144, 13, 0.4)'"
                        onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 10px 25px rgba(6, 144, 13, 0.3)'">
                    Masuk Sekarang →
                </button>
            </form>

            {{-- Divider --}}
            <div style="display: flex; align-items: center; gap: 12px; margin: 4px 0 20px;">
                <div style="flex: 1; height: 1px; background: #e2e8f0;"></div>
                <span style="font-size: 13px; font-weight: 700; color: #94a3b8;">atau</span>
                <div style="flex: 1; height: 1px; background: #e2e8f0;"></div>
            </div>

            {{-- Google Login Button --}}
            <a href="{{ route('google.login') }}"
               id="btn-google-login"
               style="width: 100%; display: flex; align-items: center; justify-content: center; gap: 10px; padding: 14px 16px; background: white; border: 2px solid #e2e8f0; border-radius: 14px; font-size: 14px; font-weight: 800; color: #1e293b; text-decoration: none; transition: all 0.2s; box-sizing: border-box; margin-bottom: 8px;"
               onmouseover="this.style.borderColor='#4285F4'; this.style.boxShadow='0 4px 16px rgba(66,133,244,0.15)'; this.style.background='#f8faff';"
               onmouseout="this.style.borderColor='#e2e8f0'; this.style.boxShadow='none'; this.style.background='white';">
                {{-- Google SVG Logo --}}
                <svg width="20" height="20" viewBox="0 0 48 48" xmlns="http://www.w3.org/2000/svg">
                    <path fill="#EA4335" d="M24 9.5c3.54 0 6.71 1.22 9.21 3.6l6.85-6.85C35.9 2.38 30.47 0 24 0 14.62 0 6.51 5.38 2.56 13.22l7.98 6.19C12.43 13.72 17.74 9.5 24 9.5z"/>
                    <path fill="#4285F4" d="M46.98 24.55c0-1.57-.15-3.09-.38-4.55H24v9.02h12.94c-.58 2.96-2.26 5.48-4.78 7.18l7.73 6c4.51-4.18 7.09-10.36 7.09-17.65z"/>
                    <path fill="#FBBC05" d="M10.53 28.59c-.48-1.45-.76-2.99-.76-4.59s.27-3.14.76-4.59l-7.98-6.19C.92 16.46 0 20.12 0 24c0 3.88.92 7.54 2.56 10.78l7.97-6.19z"/>
                    <path fill="#34A853" d="M24 48c6.48 0 11.93-2.13 15.89-5.81l-7.73-6c-2.15 1.45-4.92 2.3-8.16 2.3-6.26 0-11.57-4.22-13.47-9.91l-7.98 6.19C6.51 42.62 14.62 48 24 48z"/>
                    <path fill="none" d="M0 0h48v48H0z"/>
                </svg>
                Masuk dengan Google
            </a>

            <div style="text-align: center; margin-top: 24px; padding-top: 24px; border-top: 1px solid #e2e8f0;">
                <p style="font-size: 14px; color: #64748b; font-weight: 600;">
                    Belum punya akun?
                    <a href="{{ route('register') }}" style="color: #06900d; font-weight: 800; text-decoration: none; margin-left: 4px; transition: color 0.2s;" onmouseover="this.style.color='#057a0b'" onmouseout="this.style.color='#06900d'">
                        Daftar di sini
                    </a>
                </p>
            </div>
        </div>
    </div>
</div>
@endsection

