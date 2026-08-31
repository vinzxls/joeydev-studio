<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Additive-only 2FA columns for the users table.
     *
     * All columns are nullable / defaulted so existing rows are unaffected and
     * 2FA stays OPTIONAL (non-blocking) until an admin explicitly enables it.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('google2fa_secret')->nullable()->after('password');
            $table->boolean('google2fa_enabled')->default(false)->after('google2fa_secret');
            // JSON array of hashed, one-time-use recovery codes (never plaintext).
            $table->json('google2fa_recovery_codes')->nullable()->after('google2fa_enabled');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['google2fa_secret', 'google2fa_enabled', 'google2fa_recovery_codes']);
        });
    }
};
