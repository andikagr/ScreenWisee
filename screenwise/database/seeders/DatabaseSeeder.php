<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Challenge;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        // Admin
        $admin = User::create([
            'name' => 'Admin ScreenWise',
            'email' => 'admin@screenwise.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // Guru
        $guru1 = User::create([
            'name' => 'guru1',
            'email' => 'guru1@screenwise.com',
            'password' => Hash::make('password'),
            'role' => 'guru',
        ]);

        $guru2 = User::create([
            'name' => 'guru2',
            'email' => 'guru2@screenwise.com',
            'password' => Hash::make('password'),
            'role' => 'guru',
        ]);

        // Siswa tidak di-seed karena mereka akan mendaftar sendiri (Register) di halaman depan.

        // Challenges (7 hari)
        $challenges = [
 
 
            [1, 'Digital Detox Pagi', 'Tidak menggunakan gadget selama 1 jam pertama setelah bangun tidur. Gunakan waktu untuk sarapan dan persiapan sekolah.'],
            [2, 'No Screen Before Bed', 'Matikan semua gadget minimal 30 menit sebelum tidur. Ganti dengan membaca buku atau jurnal.'],
            [3, 'Productive Screen Time', 'Gunakan gadget hanya untuk kegiatan produktif: belajar online, membaca artikel edukatif, atau skill building.'],
            [4, 'Social Media Break', 'Tidak membuka sosial media selama satu hari penuh. Fokus pada interaksi langsung dengan teman dan keluarga.'],
            [5, 'Active Hour', 'Ganti 1 jam screen time dengan aktivitas fisik: olahraga, jalan kaki, atau bermain di luar rumah.'],
            [6, 'Mindful Usage', 'Setiap kali ingin membuka HP, tanyakan dulu: Apakah ini penting? Berapa lama saya akan pakai? Catat setiap penggunaan.'],
            [7, 'Reflection Day', 'Review seluruh perjalanan selama program. Tulis 3 hal yang berubah dan 3 kebiasaan baru yang ingin dipertahankan.'],
        ];

        foreach ($challenges as $c) {
            Challenge::create([
                'day_number' => $c[0],
                'title' => $c[1],
                'description' => $c[2],
                'created_by' => $admin->id,
            ]);
        }
    }
}
