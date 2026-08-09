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
        Schema::table('users', function (Blueprint $table) {
            $table->string('google2fa_secret')->nullable()->after('password');
        });

        Schema::table('cs_feedbacks', function (Blueprint $table) {
            $table->text('reply')->nullable()->after('received_all_files');
            $table->timestamp('replied_at')->nullable()->after('reply');
        });

        Schema::table('contacts', function (Blueprint $table) {
            $table->text('reply')->nullable()->after('message');
            $table->timestamp('replied_at')->nullable()->after('reply');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('google2fa_secret');
        });

        Schema::table('cs_feedbacks', function (Blueprint $table) {
            $table->dropColumn(['reply', 'replied_at']);
        });

        Schema::table('contacts', function (Blueprint $table) {
            $table->dropColumn(['reply', 'replied_at']);
        });
    }
};
