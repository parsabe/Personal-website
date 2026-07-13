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
        Schema::table('cs_feedbacks', function (Blueprint $table) {
            $table->unsignedBigInteger('cs_student_id')->nullable()->change();
            $table->string('email')->nullable()->after('cs_student_id');
            $table->text('ideas')->nullable()->change();
            $table->text('feedback')->nullable()->change();
            $table->text('questions')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cs_feedbacks', function (Blueprint $table) {
            $table->unsignedBigInteger('cs_student_id')->nullable(false)->change();
            $table->dropColumn('email');
            $table->text('ideas')->nullable(false)->change();
            $table->text('feedback')->nullable(false)->change();
            $table->text('questions')->nullable(false)->change();
        });
    }
};
