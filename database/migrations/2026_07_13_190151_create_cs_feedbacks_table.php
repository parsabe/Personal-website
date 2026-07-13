<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('cs_feedbacks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cs_student_id')->constrained('cs_students')->onDelete('cascade');
            $table->text('ideas');
            $table->text('feedback');
            $table->text('questions');
            $table->boolean('received_all_files')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cs_feedbacks');
    }
};
