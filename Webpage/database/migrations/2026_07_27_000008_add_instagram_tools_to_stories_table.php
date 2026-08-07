<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('chat_stories')) {
            Schema::table('chat_stories', function (Blueprint $table) {
                if (!Schema::hasColumn('chat_stories', 'story_type')) {
                    $table->string('story_type')->default('standard')->after('media_url');
                }
                if (!Schema::hasColumn('chat_stories', 'poll_options')) {
                    $table->json('poll_options')->nullable()->after('story_type');
                }
                if (!Schema::hasColumn('chat_stories', 'mentions')) {
                    $table->json('mentions')->nullable()->after('poll_options');
                }
                if (!Schema::hasColumn('chat_stories', 'sticker_data')) {
                    $table->json('sticker_data')->nullable()->after('mentions');
                }
            });
        }

        if (Schema::hasTable('sandika_stories')) {
            Schema::table('sandika_stories', function (Blueprint $table) {
                if (!Schema::hasColumn('sandika_stories', 'media_url')) {
                    $table->string('media_url')->nullable()->after('content');
                }
                if (!Schema::hasColumn('sandika_stories', 'story_type')) {
                    $table->string('story_type')->default('standard')->after('media_url');
                }
                if (!Schema::hasColumn('sandika_stories', 'poll_options')) {
                    $table->json('poll_options')->nullable()->after('story_type');
                }
                if (!Schema::hasColumn('sandika_stories', 'mentions')) {
                    $table->json('mentions')->nullable()->after('poll_options');
                }
                if (!Schema::hasColumn('sandika_stories', 'sticker_data')) {
                    $table->json('sticker_data')->nullable()->after('mentions');
                }
            });
        }
    }

    public function down(): void
    {
        // Rollback helper if needed
    }
};
