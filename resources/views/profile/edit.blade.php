@extends('layouts.app')
@section('title', 'Pengaturan Profil')
@section('page-title', 'Pengaturan Profil')

@section('content')
<div class="card fade-up" style="max-width: 600px; margin: 0 auto; border: 4px solid var(--primary-100); border-radius: 32px; background: linear-gradient(135deg, #ffffff 0%, var(--primary-50) 100%); box-shadow: 0 10px 25px rgba(6, 144, 13, 0.05);">
    <div class="card-header" style="background: var(--primary-50); border-bottom: none;">
        <h3 class="text-primary" style="margin: 0; display: flex; align-items: center; gap: 8px;"><i data-lucide="image"></i> Ubah Foto Profil</h3>
    </div>
    <div class="card-body">
        
        @if(session('success'))
            <div style="background: var(--accent-green); color: var(--dark-900); padding: 16px; border-radius: 16px; margin-bottom: 24px; font-weight: bold; border: 2px solid #34d399;">
                <i data-lucide="check-circle" style="width:16px;height:16px;"></i> {{ session('success') }}
            </div>
        @endif

        <div style="text-align: center; margin-bottom: 32px;">
            <div style="width: 150px; height: 150px; border-radius: 50%; background: var(--accent-purple); color: white; display: flex; align-items: center; justify-content: center; font-size: 64px; font-weight: 900; margin: 0 auto; overflow: hidden; border: 6px solid var(--primary-100); box-shadow: 0 8px 0 var(--dark-100);">
                @if($user->profile_photo_path)
                    <img src="{{ $user->profile_photo_url }}" alt="Profile Photo" style="width: 100%; height: 100%; object-fit: cover;">
                @else
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                @endif
            </div>
            <h3 style="margin-top: 16px; color: var(--dark-800);">{{ $user->name }}</h3>
            <p style="color: var(--dark-500); font-weight: 600; text-transform: uppercase;">Role: {{ $user->role }}</p>
        </div>

        <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div style="margin-bottom: 24px;">
                <label style="display: block; font-weight: 900; color: var(--dark-700); margin-bottom: 8px;">Pilih Foto Baru:</label>
                
                <div style="position: relative;">
                    <input type="file" id="photoInput" name="profile_photo" class="form-input" accept="image/jpeg,image/png,image/jpg,image/gif" required style="background: white; padding: 12px; cursor: pointer;">
                    <div id="fileNameDisplay" style="margin-top: 12px; font-weight: bold; color: var(--primary-600); font-size: 14px; background: var(--primary-50); padding: 8px 12px; border-radius: 8px; border: 2px dashed var(--primary-200); display: none; align-items: center; gap: 6px;">
                        <i data-lucide="paperclip" style="width: 16px; height: 16px;"></i> <span id="fileNameText">Belum ada file terpilih</span>
                    </div>
                </div>

                @error('profile_photo')
                    <div style="color: var(--danger); font-size: 14px; margin-top: 8px; font-weight: bold;">{{ $message }}</div>
                @enderror
                <p style="font-size: 13px; color: var(--dark-400); margin-top: 8px;">Format yang didukung: JPG, PNG, GIF. Maksimal ukuran: 10MB.</p>
            </div>

            <div style="display: flex; gap: 16px; margin-top: 32px;">
                <button type="submit" class="btn btn-lg bounce-anim" style="flex: 1; justify-content: center; background: var(--primary); color: white; box-shadow: 0 6px 0 var(--primary-dark); display: flex; align-items: center; gap: 8px;"><i data-lucide="save"></i> Simpan Foto</button>
            </div>
        </form>

    </div>
</div>

<script>
    document.getElementById('photoInput').addEventListener('change', function(e) {
        const display = document.getElementById('fileNameDisplay');
        const text = document.getElementById('fileNameText');
        if (e.target.files.length > 0) {
            text.textContent = e.target.files[0].name;
            display.style.display = 'block';
        } else {
            display.style.display = 'none';
        }
    });
</script>
@endsection
