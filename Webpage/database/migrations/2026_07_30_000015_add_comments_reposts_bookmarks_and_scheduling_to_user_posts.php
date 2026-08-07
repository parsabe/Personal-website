<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Add scheduling and bookmark/repost metadata to user_posts table
        Schema::table('user_posts', function (Blueprint $table) {
            if (!Schema::hasColumn('user_posts', 'scheduled_at')) {
                $table->timestamp('scheduled_at')->nullable()->after('privacy');
            }
            if (!Schema::hasColumn('user_posts', 'bookmarks_count')) {
                $table->unsignedInteger('bookmarks_count')->default(0)->after('reposts_count');
            }
            if (!Schema::hasColumn('user_posts', 'bookmarked_by_users')) {
                $table->json('bookmarked_by_users')->nullable()->after('liked_by_users');
            }
            if (!Schema::hasColumn('user_posts', 'reposted_by_users')) {
                $table->json('reposted_by_users')->nullable()->after('bookmarked_by_users');
            }
        });

        // 2. Create post_comments table for comments on all user posts
        if (!Schema::hasTable('post_comments')) {
            Schema::create('post_comments', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_post_id')->constrained('user_posts')->onDelete('cascade');
                $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
                $table->text('comment');
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::table('user_posts', function (Blueprint $table) {
            $table->dropColumn(['scheduled_at', 'bookmarks_count', 'bookmarked_by_users', 'reposted_by_users']);
        });

        Schema::dropIfExists('post_comments');
    }
};
