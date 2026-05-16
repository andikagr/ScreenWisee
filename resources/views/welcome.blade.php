@extends('layouts.guest')
@section('title', 'ScreenWise - Monitor Screen Time Harian')
@section('content')
    <script src="https://unpkg.com/lucide@latest"></script>

    <style>
        .grid-bg {
            background-color: #c0d8c2ff;
            background-image:
                radial-gradient(#cbd5e1 1px, transparent 1px),
                radial-gradient(#cbd5e1 1px, transparent 1px);
            background-size: 32px 32px;
            background-position: 0 0, 16px 16px;
        }

        .hero-text-gradient {
            background: linear-gradient(135deg, #214527ff 0%, #037614ff 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .floating-card-1 {
            animation: float1 6s ease-in-out infinite;
        }

        .floating-card-2 {
            animation: float2 8s ease-in-out infinite;
        }

        @keyframes float1 {
            0% {
                transform: translateY(0px) rotate(0deg);
            }

            50% {
                transform: translateY(-15px) rotate(1deg);
            }

            100% {
                transform: translateY(0px) rotate(0deg);
            }
        }

        @keyframes float2 {
            0% {
                transform: translateY(0px) rotate(0deg);
            }

            50% {
                transform: translateY(-10px) rotate(-1deg);
            }

            100% {
                transform: translateY(0px) rotate(0deg);
            }
        }

        .modern-btn {
            background: linear-gradient(135deg, #2e9438ff 0%, #087a1fff 100%);
            color: white !important;
            border: none;
            box-shadow: 0 10px 25px rgba(3, 77, 14, 0.3);
            transition: all 0.3s ease;
        }

        .modern-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 15px 30px rgba(3, 77, 14, 0.3);
        }

        @media (max-width: 992px) {
            .hero-layout {
                grid-template-columns: 1fr !important;
                gap: 60px !important;
                text-align: center;
            }

            .hero-layout>div:first-child {
                display: flex;
                flex-direction: column;
                align-items: center;
            }

            .hero-layout p {
                margin-left: auto;
                margin-right: auto;
            }

            .stat-badges {
                justify-content: center;
                flex-wrap: wrap;
            }
        }
    </style>

    <div class="grid-bg"
        style="min-height: 100vh; position: relative; overflow: hidden; display: flex; align-items: center;">

        {{-- Soft Glows --}}
        <div
            style="position: absolute; top: -10%; left: -10%; width: 50vw; height: 50vw; border-radius: 50%; background: radial-gradient(circle, rgba(99, 102, 241, 0.1) 0%, rgba(255,255,255,0) 70%); z-index: 0;">
        </div>
        <div
            style="position: absolute; bottom: -20%; right: -10%; width: 60vw; height: 60vw; border-radius: 50%; background: radial-gradient(circle, rgba(16, 185, 129, 0.08) 0%, rgba(255,255,255,0) 70%); z-index: 0;">
        </div>

        <nav
            style="position: absolute; top: 0; left: 0; right: 0; padding: 24px 40px; display: flex; justify-content: space-between; align-items: center; z-index: 50;">
            <div style="display: flex; align-items: center; gap: 12px;">
                <div
                    style="width: 48px; height: 48px; background: white; border-radius: 14px; display: flex; align-items: center; justify-content: center; box-shadow: 0 8px 16px rgba(0,0,0,0.06);">
                    <img src="{{ asset('images/logo.svg') }}" alt="Logo"
                        style="width: 32px; height: 32px; object-fit: contain;">
                </div>
                <span style="font-size: 24px; font-weight: 800; color: #1e293b; letter-spacing: -0.5px;">ScreenWise</span>
            </div>
            <div>
                @auth
                    @if(auth()->user()->isSiswa())
                        <a href="{{ route('siswa.dashboard') }}" class="btn modern-btn"
                            style="padding: 12px 24px; border-radius: 12px;">Dashboard Ku</a>
                    @elseif(auth()->user()->isGuru())
                        <a href="{{ route('guru.dashboard') }}" class="btn modern-btn"
                            style="padding: 12px 24px; border-radius: 12px;">Dashboard Guru</a>
                    @elseif(auth()->user()->isAdmin())
                        <a href="{{ route('admin.dashboard') }}" class="btn modern-btn"
                            style="padding: 12px 24px; border-radius: 12px;">Dashboard Admin</a>
                    @endif
                @else
                    <a href="{{ route('login') }}" class="btn"
                        style="background: white; color: #0c7518ff; border: 1px solid #e2e8f0; box-shadow: 0 4px 6px rgba(0,0,0,0.02); margin-right: 12px; padding: 12px 24px; border-radius: 12px; font-weight: 700;">Masuk</a>
                    <a href="{{ route('register') }}" class="btn modern-btn"
                        style="padding: 12px 24px; border-radius: 12px; font-weight: 700;">Daftar Sekarang</a>
                @endauth
            </div>
        </nav>

        <div class="hero-layout"
            style="max-width: 1300px; margin: 0 auto; width: 100%; padding: 120px 40px; display: grid; grid-template-columns: 1.1fr 0.9fr; gap: 80px; align-items: center; position: relative; z-index: 10;">
            <!-- Left text -->
            <div class="fade-up">
                <div
                    style="margin-bottom: 24px; display: inline-flex; align-items: center; gap: 8px; background: white; padding: 8px 20px; border-radius: 30px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); border: 1px solid #e2e8f0;">
                    <span
                        style="background: #e0e7ff; color: #09a034ff; padding: 4px 10px; border-radius: 12px; font-size: 12px; font-weight: 800; letter-spacing: 0.5px;">BARU</span>
                    <span style="font-size: 14px; font-weight: 700; color: #475569;">Platform Wellbeing Pelajar</span>
                </div>

                <h1 class="hero-text-gradient"
                    style="font-size: clamp(40px, 5vw, 64px); font-weight: 900; line-height: 1.1; margin-bottom: 24px; letter-spacing: -2px;">
                    Pantau & Kurangi<br>
                    Screen Time Harian.
                </h1>

                <p
                    style="font-size: 18px; line-height: 1.7; margin-bottom: 40px; color: #64748b; max-width: 540px; font-weight: 500;">
                    Catat aktivitas digitalmu, ikuti tantangan seru setiap hari, dan lihat perkembangan nyata. Jadilah lebih
                    produktif tanpa harus pusing!
                </p>

                <div style="display: flex; gap: 20px; flex-wrap: wrap;">
                    <a href="{{ route('register') }}" class="btn modern-btn btn-lg"
                        style="padding: 16px 36px; font-size: 16px; border-radius: 16px; font-weight: 700;">Mulai
                        Petualangan </a>
                </div>

                <div class="stat-badges" style="display: flex; gap: 40px; margin-top: 60px;">
                    <div style="display: flex; align-items: center; gap: 16px;">
                        <div
                            style="width: 56px; height: 56px; background: white; border: 1px solid #dcfce7; box-shadow: 0 8px 16px rgba(6, 144, 13, 0.1); border-radius: 16px; display: flex; align-items: center; justify-content: center; font-size: 24px; color: #06900d;">
                            <i data-lucide="users"></i>
                        </div>
                        <div>
                            <div style="font-size: 24px; font-weight: 900; color: #1e293b;">7 Hari</div>
                            <div
                                style="font-size: 13px; color: #64748b; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px;">
                                Tracking Data</div>
                        </div>
                    </div>
                    <div style="display: flex; align-items: center; gap: 16px;">
                        <div
                            style="width: 56px; height: 56px; background: white; border: 1px solid #d1fae5; box-shadow: 0 8px 16px rgba(16, 185, 129, 0.1); border-radius: 16px; display: flex; align-items: center; justify-content: center; font-size: 24px; color: #059669;">
                            <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2.5"
                                viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                            </svg>
                        </div>
                        <div>
                            <div style="font-size: 24px; font-weight: 900; color: #1e293b;">100%</div>
                            <div
                                style="font-size: 13px; color: #64748b; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px;">
                                Real-time</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Composition (Illustration + Floating UI) -->
            <div class="fade-up"
                style="position: relative; animation-delay: 0.2s; display: flex; justify-content: center; align-items: center; height: 100%;">
                <!-- Background decorative shape -->
                <div
                    style="position: absolute; top: 0; right: 0; bottom: 0; left: 0; background: linear-gradient(135deg, #a8e6c0 0%, #c0d8c2 100%); border-radius: 36px; transform: rotate(6deg) scale(0.95); z-index: 0;">
                </div>

                <!-- Main Illustration Image -->
                <div class="floating-card-1"
                    style="position: relative; z-index: 1; border-radius: 28px; overflow: hidden; box-shadow: 0 25px 50px rgba(15, 23, 42, 0.2); border: 6px solid white; max-width: 380px; width: 100%; background: white;">
                    <img src="{{ asset('images/kid_screenwise.png') }}" alt="ScreenWise Illustration"
                        style="width: 100%; height: auto; display: block; border-radius: 22px;">
                </div>

                <!-- Overlapping Floating Card 1 -->
                <div class="floating-card-2"
                    style="position: absolute; bottom: 40px; left: 5%; background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(12px); padding: 16px 20px; border-radius: 20px; box-shadow: 0 15px 35px rgba(0,0,0,0.15); border: 1px solid rgba(255,255,255,0.8); display: flex; align-items: center; gap: 16px; z-index: 2; width: 260px;">
                    <div
                        style="width: 44px; height: 44px; background: #83d767ff; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 22px;">
                        <i data-lucide="target" style="color: #04821dff;"></i>
                    </div>
                    <div style="flex: 1;">
                        <div style="font-weight: 800; font-size: 14px; color: #1e293b;">Tantangan Selesai</div>
                        <div
                            style="width: 100%; height: 6px; background: #f1f5f9; border-radius: 3px; margin-top: 6px; overflow: hidden;">
                            <div style="width: 66%; height: 100%; background: #04821dff; border-radius: 3px;"></div>
                        </div>
                    </div>
                    <div style="font-weight: 900; font-size: 14px; color: #04821dff;">2/3</div>
                </div>

                <!-- Overlapping Floating Stats -->
                <div class="bounce-anim"
                    style="position: absolute; top: 60px; right: -5%; background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(12px); padding: 14px 18px; border-radius: 18px; box-shadow: 0 10px 25px rgba(0,0,0,0.15); border: 1px solid rgba(255,255,255,0.8); display: flex; align-items: center; gap: 12px; z-index: 2;">
                    <div
                        style="width: 36px; height: 36px; background: #d1fae5; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: #059669; font-size: 18px;">
                        ↓
                    </div>
                    <div>
                        <div style="font-weight: 800; font-size: 16px; color: #1e293b; line-height: 1;">18%</div>
                        <div
                            style="font-size: 11px; color: #64748b; font-weight: 700; margin-top: 4px; text-transform: uppercase; letter-spacing: 0.5px;">
                            Turun</div>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <script>
        lucide.createIcons();
    </script>
@endsection