<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['email_verified_at', 'remember_token']);
            $table->string('email', 255)->nullable(true)->change();
            $table->string('token')->after('password')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->integer('email_verified_at')->nullable();
            $table->string('remember_token', 127)->nullable();
            $table->string('email', 255)->nullable(false)->change();
        });
    }
};
