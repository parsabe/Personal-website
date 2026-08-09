<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Add privacy and settings columns to users table
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'account_privacy')) {
                $table->enum('account_privacy', ['public', 'private'])->default('public')->after('profile_theme_color');
            }
            if (!Schema::hasColumn('users', 'post_privacy')) {
                $table->enum('post_privacy', ['public', 'followers', 'private'])->default('public')->after('account_privacy');
            }
            if (!Schema::hasColumn('users', 'story_privacy')) {
                $table->enum('story_privacy', ['public', 'followers', 'private'])->default('public')->after('post_privacy');
            }
        });

        // 2. Create user_follows table for Followers & Following system
        if (!Schema::hasTable('user_follows')) {
            Schema::create('user_follows', function (Blueprint $table) {
                $table->id();
                $table->foreignId('follower_id')->constrained('users')->onDelete('cascade');
                $table->foreignId('following_id')->constrained('users')->onDelete('cascade');
                $table->timestamps();
                $table->unique(['follower_id', 'following_id']);
            });
        }

        // 3. Add Instagram story countdown, highlights, and archiving to chat_stories table
        Schema::table('chat_stories', function (Blueprint $table) {
            if (!Schema::hasColumn('chat_stories', 'is_archived')) {
                $table->boolean('is_archived')->default(false)->after('expires_at');
            }
            if (!Schema::hasColumn('chat_stories', 'is_highlight')) {
                $table->boolean('is_highlight')->default(false)->after('is_archived');
            }
            if (!Schema::hasColumn('chat_stories', 'countdown_target_at')) {
                $table->timestamp('countdown_target_at')->nullable()->after('is_highlight');
            }
            if (!Schema::hasColumn('chat_stories', 'privacy')) {
                $table->enum('privacy', ['public', 'followers', 'private'])->default('public')->after('countdown_target_at');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['account_privacy', 'post_privacy', 'story_privacy']);
        });

        Schema::dropIfExists('user_follows');

        Schema::table('chat_stories', function (Blueprint $table) {
            $table->dropColumn(['is_archived', 'is_highlight', 'countdown_target_at', 'privacy']);
        });
    }
};
