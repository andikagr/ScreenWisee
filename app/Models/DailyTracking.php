<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DailyTracking extends Model
{
    protected $fillable = [
        'user_id',
        'tracking_date',
        'screen_time_hours',
        'activities',
        'challenge_checklist',
        'screenshot_path',
    ];

    protected function casts(): array
    {
        return [
            'tracking_date' => 'date',
            'activities' => 'array',
            'challenge_checklist' => 'array',
            'screen_time_hours' => 'float',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getScreenshotUrlAttribute()
    {
        if (!$this->screenshot_path) {
            return null;
        }

        $path = trim($this->screenshot_path);

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
