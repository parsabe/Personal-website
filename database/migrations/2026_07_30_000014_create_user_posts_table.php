<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('user_posts')) {
            Schema::create('user_posts', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
                $table->text('content')->nullable();
                $table->string('media_url')->nullable();
                $table->enum('media_type', ['text', 'image', 'video'])->default('text');
                $table->unsignedInteger('likes_count')->default(0);
                $table->unsignedInteger('reposts_count')->default(0);
                $table->json('liked_by_users')->nullable();
                $table->enum('privacy', ['public', 'followers', 'private'])->default('public');
                $table->softDeletes();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('user_posts');
    }
};
