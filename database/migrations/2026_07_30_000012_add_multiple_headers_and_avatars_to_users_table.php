<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'header_banner')) {
                $table->string('header_banner')->nullable()->after('avatar');
            }
            if (!Schema::hasColumn('users', 'avatars_gallery')) {
                $table->json('avatars_gallery')->nullable()->after('header_banner');
            }
            if (!Schema::hasColumn('users', 'headers_gallery')) {
                $table->json('headers_gallery')->nullable()->after('avatars_gallery');
            }
            if (!Schema::hasColumn('users', 'profile_theme_color')) {
                $table->string('profile_theme_color')->default('sapphire')->after('headers_gallery');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['header_banner', 'avatars_gallery', 'headers_gallery', 'profile_theme_color']);
        });
    }
};
