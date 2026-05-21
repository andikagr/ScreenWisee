<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'profile_photo_path',
        'role',
        'kelas',
        'guru_id',
        'google_id',
        'google_token',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Role helpers
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isGuru(): bool
    {
        return $this->role === 'guru';
    }

    public function isSiswa(): bool
    {
        return $this->role === 'siswa';
    }

    // Relationships
    public function guru(): BelongsTo
    {
        return $this->belongsTo(User::class, 'guru_id');
    }

    public function siswaList(): HasMany
    {
        return $this->hasMany(User::class, 'guru_id');
    }

    public function pretest(): HasOne
    {
        return $this->hasOne(Pretest::class);
    }

    public function posttest(): HasOne
    {
        return $this->hasOne(Posttest::class);
    }

    public function dailyTrackings(): HasMany
    {
        return $this->hasMany(DailyTracking::class);
    }

    public function getProfilePhotoUrlAttribute()
    {
        if (!$this->profile_photo_path) {
            return null;
        }

        $path = trim($this->profile_photo_path);

        // Jika path berupa base URL S3 saja (tanpa nama file), berarti upload sebelumnya gagal
        if (str_ends_with($path, '/')) {
            return null;
        }

        if (str_starts_with($path, 'http')) {
            if (str_contains($path, '/storage/v1/s3/')) {
                $path = str_replace('/storage/v1/s3/', '/storage/v1/object/public/', $path);
            }
            return $path;
        }

        return asset('storage/' . $path);
    }
}
