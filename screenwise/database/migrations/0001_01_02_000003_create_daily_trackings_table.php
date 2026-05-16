<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('daily_trackings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->date('tracking_date');
            $table->float('screen_time_hours');
            $table->json('activities')->nullable(); // {sosmed: 2, game: 1, belajar: 3, lainnya: 0.5}
            $table->json('challenge_checklist')->nullable(); // [true, false, true]
            $table->string('screenshot_path')->nullable();
            $table->timestamps();

            $table->unique(['user_id', 'tracking_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('daily_trackings');
    }
};
