<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function edit()
    {
        return view('profile.edit', [
            'user' => auth()->user()
        ]);
    }

    public function update(Request $request)
    {
        $request->validate([
            'profile_photo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:10240'],
        ]);

        $user = auth()->user();

        if ($request->hasFile('profile_photo')) {
            $disk = env('FILESYSTEM_DISK', 'public'); // Gunakan default public, tapi akan diganti ke supabase di Vercel/Production
            
            // Delete old photo if exists
            if ($user->profile_photo_path && Storage::disk($disk)->exists($user->profile_photo_path)) {
                Storage::disk($disk)->delete($user->profile_photo_path);
            }

            $path = $request->file('profile_photo')->store('profiles', $disk);
            
            if ($path) {
                // Generate public URL jika menggunakan supabase
                if ($disk === 'supabase') {
                    $user->profile_photo_path = Storage::disk('supabase')->url($path);
                } else {
                    $user->profile_photo_path = $path;
                }
            } else {
                return back()->withInput()->withErrors(['profile_photo' => 'Gagal mengunggah foto. Pastikan bucket Supabase sudah dibuat dan namanya sesuai.']);
            }
            
            $user->save();
        }

        return back()->with('success', 'Foto profil berhasil diperbarui!');
    }
}
