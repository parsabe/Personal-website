<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Sandika Stories Table
        if (!Schema::hasTable('sandika_stories')) {
            Schema::create('sandika_stories', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
                $table->string('title');
                $table->text('content');
                $table->integer('cp_awarded')->default(10);
                $table->timestamps();
            });
        }

        // Sandika Dictionary (English / German) Table
        if (!Schema::hasTable('sandika_dictionary')) {
            Schema::create('sandika_dictionary', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
                $table->enum('language', ['en', 'de'])->default('en');
                $table->string('word');
                $table->text('definition');
                $table->integer('cp_awarded')->default(10);
                $table->timestamps();
            });
        }

        // Sandika Git Insights Table
        if (!Schema::hasTable('sandika_git_insights')) {
            Schema::create('sandika_git_insights', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
                $table->string('repo_url');
                $table->text('description');
                $table->boolean('is_github_verified')->default(false);
                $table->integer('cp_awarded')->default(5);
                $table->timestamps();
            });
        }

        // Nigma User Solves Table
        if (!Schema::hasTable('nigma_user_solves')) {
            Schema::create('nigma_user_solves', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
                $table->unsignedBigInteger('riddle_id');
                $table->string('solution');
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('sandika_stories');
        Schema::dropIfExists('sandika_dictionary');
        Schema::dropIfExists('sandika_git_insights');
        Schema::dropIfExists('nigma_user_solves');
    }
};
