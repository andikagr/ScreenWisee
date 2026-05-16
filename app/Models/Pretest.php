<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pretest extends Model
{
    protected $fillable = [
        'user_id',
        'avg_screen_time',
        'sleep_time',
        'wake_time',
        'gadget_habits',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'gadget_habits' => 'array',
            'avg_screen_time' => 'float',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
