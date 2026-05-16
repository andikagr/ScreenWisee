@extends('layouts.guest')
@section('title', 'ScreenWise - Petualangan Digitalmu!')
@section('content')
<style>
    @keyframes floatHero {
        0%, 100% { transform: translateY(0) rotate(-3deg); }
        50% { transform: translateY(-20px) rotate(3deg); }
    }
    .hero-logo-anim {
        animation: floatHero 3s ease-in-out infinite;
    }
    
    .feature-card {
        transition: transform 0.4s cubic-bezier(0.34, 1.56, 0.64, 1), box-shadow 0.4s cubic-bezier(0.34, 1.56, 0.64, 1) !important;
    }
    .feature-card:nth-child(1):hover {
        transform: translateY(-20px) scale(1.05) rotate(-2deg);
        box-shadow: 0 24px 0 var(--primary-300) !important;
    }
    .feature-card:nth-child(2):hover {
        transform: translateY(-20px) scale(1.05) rotate(2deg);
        box-shadow: 0 24px 0 var(--accent-yellow-dark) !important;
    }
    .feature-card:nth-child(3):hover {
        transform: translateY(-20px) scale(1.05) rotate(-2deg);
        box-shadow: 0 24px 0 var(--accent-pink-dark) !important;
    }
</style>
<div class="landing" style="background: linear-gradient(135deg, var(--primary-300), var(--primary-500)); min-height: 100vh; overflow: hidden; position: relative;">
    <!-- Floating Background Elements -->
    <div class="float-anim" style="position: absolute; top: 10%; left: 10%; font-size: 64px; opacity: 0.2;">⭐</div>
    <div class="float-anim" style="position: absolute; top: 20%; right: 15%; font-size: 80px; opacity: 0.2; animation-delay: 1s;">📱</div>
    <div class="float-anim" style="position: absolute; bottom: 15%; left: 20%; font-size: 72px; opacity: 0.2; animation-delay: 2s;">🎮</div>
    <div class="float-anim" style="position: absolute; bottom: 25%; right: 10%; font-size: 96px; opacity: 0.2; animation-delay: 1.5s;">🚀</div>

    <!-- Background Image Layer -->
    <img src="{{ asset('images/kid_screenwise.png') }}" style="position: absolute; inset: 0; width: 100%; height: 100%; object-fit: cover; opacity: 0.2; z-index: 1; pointer-events: none; mix-blend-mode: luminosity;" alt="Background">

    <div class="landing-hero" style="position: relative; z-index: 10; padding-top: 80px; text-align: center;">
        <div class="hero-content fade-up" style="max-width: 800px; margin: 0 auto; padding: 0 20px;">
            <div class="hero-logo-anim" style="display: inline-block; margin-bottom: 24px;">
                <img src="{{ asset('images/logo.svg') }}" alt="ScreenWise Logo" style="width:240px;height:240px;object-fit:contain; filter: drop-shadow(0 12px 24px rgba(0,0,0,0.2));">
            </div>

            <div>
                <h1 style="font-size: 56px; font-weight: 900; color: white; text-shadow: 0 4px 12px rgba(0,0,0,0.15); margin-bottom: 24px; line-height: 1.2;">
                    Halo Teman-Teman!<br>Selamat Datang di ScreenWise!!! 🎉
                </h1>
                <p style="font-size: 20px; color: rgba(255,255,255,0.9); margin-bottom: 40px; font-weight: 600;">
                    Yuk mulai petualangan seru membangun kebiasaan digital yang sehat. Ada banyak tantangan harian dan lencana keren yang bisa kamu kumpulkan lho!
                </p>
                <div class="hero-buttons" style="display: flex; gap: 20px; justify-content: center;">
                    <a href="{{ route('login') }}" class="btn btn-secondary btn-lg" style="font-size: 20px; padding: 16px 40px; border-radius: 32px;">🔑 Masuk Yuk!</a>
                    <a href="{{ route('register') }}" class="btn btn-lg" style="background: var(--accent-yellow); color: var(--dark-900); font-size: 20px; padding: 16px 40px; border-radius: 32px; box-shadow: 0 8px 0 var(--accent-yellow-dark);">🚀 Mulai Main!</a>
                </div>
            </div>
        </div>
    </div>

    <div class="landing-features" style="position: relative; z-index: 10; padding: 80px 20px; max-width: 1200px; margin: 0 auto;">
        <div class="features-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 40px;">
            <div class="feature-card fade-up" style="background: white; padding: 48px 32px; border-radius: 40px; text-align: center; box-shadow: 0 16px 0 var(--primary-200); border: 6px solid var(--primary-100);">
                <div class="feature-icon float-anim" style="font-size: 64px; margin-bottom: 24px; display: inline-flex; width: 120px; height: 120px; background: var(--primary-50); border-radius: 50%; align-items: center; justify-content: center; border: 4px solid var(--primary-200); box-shadow: 0 8px 0 var(--primary-200);">📊</div>
                <h3 style="font-size: 28px; font-weight: 900; color: var(--primary-600); margin-bottom: 16px;">Jurnal Harian</h3>
                <p style="color: var(--dark-600); font-size: 16px; font-weight: 700; line-height: 1.6;">Catat waktu main HP kamu tiap hari. Jangan lupa upload screenshot screen time-nya ya!</p>
            </div>
            <div class="feature-card fade-up" style="background: white; padding: 48px 32px; border-radius: 40px; text-align: center; box-shadow: 0 16px 0 var(--accent-yellow-dark); border: 6px solid var(--accent-yellow); animation-delay: 0.2s;">
                <div class="feature-icon bounce-anim" style="font-size: 64px; margin-bottom: 24px; display: inline-flex; width: 120px; height: 120px; background: #FEF3C7; border-radius: 50%; align-items: center; justify-content: center; border: 4px solid var(--accent-yellow); box-shadow: 0 8px 0 var(--accent-yellow-dark);">🎯</div>
                <h3 style="font-size: 28px; font-weight: 900; color: var(--accent-yellow-dark); margin-bottom: 16px;">Tantangan Seru</h3>
                <p style="color: var(--dark-600); font-size: 16px; font-weight: 700; line-height: 1.6;">Ikuti misi harian buat kurangin main HP. Kumpulin poin dan lencana kerennya!</p>
            </div>
            <div class="feature-card fade-up" style="background: white; padding: 48px 32px; border-radius: 40px; text-align: center; box-shadow: 0 16px 0 var(--accent-pink-dark); border: 6px solid var(--accent-pink); animation-delay: 0.4s;">
                <div class="feature-icon pulse-ring" style="font-size: 64px; margin-bottom: 24px; display: inline-flex; width: 120px; height: 120px; background: #FCE7F3; border-radius: 50%; align-items: center; justify-content: center; border: 4px solid var(--accent-pink); box-shadow: 0 8px 0 var(--accent-pink-dark);">🏆</div>
                <h3 style="font-size: 28px; font-weight: 900; color: var(--accent-pink-dark); margin-bottom: 16px;">Jadi Jagoan!</h3>
                <p style="color: var(--dark-600); font-size: 16px; font-weight: 700; line-height: 1.6;">Lihat progresmu dari hari ke hari. Pertahankan rekor streak-mu biar makin hebat!</p>
            </div>
        </div>
    </div>
</div>
@endsection
