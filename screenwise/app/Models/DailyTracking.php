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
}
