<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'deleted_at')) {
                $table->softDeletes();
            }
            if (!Schema::hasColumn('users', 'deleted_reason')) {
                $table->string('deleted_reason')->nullable();
            }
            if (!Schema::hasColumn('users', 'deleted_custom_reason')) {
                $table->text('deleted_custom_reason')->nullable();
            }
        });

        Schema::table('blog_posts', function (Blueprint $table) {
            if (!Schema::hasColumn('blog_posts', 'deleted_at')) {
                $table->softDeletes();
            }
            if (!Schema::hasColumn('blog_posts', 'deleted_reason')) {
                $table->string('deleted_reason')->nullable();
            }
            if (!Schema::hasColumn('blog_posts', 'deleted_custom_reason')) {
                $table->text('deleted_custom_reason')->nullable();
            }
            if (!Schema::hasColumn('blog_posts', 'deleted_by_admin')) {
                $table->boolean('deleted_by_admin')->default(false);
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropSoftDeletes();
            $table->dropColumn(['deleted_reason', 'deleted_custom_reason']);
        });

        Schema::table('blog_posts', function (Blueprint $table) {
            $table->dropSoftDeletes();
            $table->dropColumn(['deleted_reason', 'deleted_custom_reason', 'deleted_by_admin']);
        });
    }
};
