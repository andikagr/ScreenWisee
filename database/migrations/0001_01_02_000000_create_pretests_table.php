<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pretests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->float('avg_screen_time'); // jam per hari
            $table->string('sleep_time'); // misal "22:00"
            $table->string('wake_time'); // misal "06:00"
            $table->json('gadget_habits')->nullable(); // array kebiasaan
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pretests');
    }
};
