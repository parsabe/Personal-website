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
        Schema::table('cs_students', function (Blueprint $table) {
            $table->boolean('downloaded_cert')->default(false)->after('email');
            $table->boolean('downloaded_zip')->default(false)->after('downloaded_cert');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cs_students', function (Blueprint $table) {
            $table->dropColumn(['downloaded_cert', 'downloaded_zip']);
        });
    }
};
