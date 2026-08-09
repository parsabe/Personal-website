<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('sandika_arkham_solves')) {
            Schema::create('sandika_arkham_solves', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
                $table->integer('spirit_id');
                $table->text('user_answer');
                $table->integer('cp_awarded')->default(20);
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('sandika_arkham_solves');
    }
};
