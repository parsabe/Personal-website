<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sandika_user_ranks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->integer('xp')->default(0);
            $table->string('rank_title')->default('Novice Operative');
            $table->integer('level')->default(1);
            $table->integer('voice_logs_analyzed')->default(0);
            $table->integer('files_processed')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sandika_user_ranks');
    }
};
