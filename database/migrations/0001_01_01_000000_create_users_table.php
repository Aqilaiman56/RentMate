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
        Schema::create('users', function (Blueprint $table) {
            $table->id('UserID');
            $table->string('UserName');
            $table->string('Email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('PasswordHash');
            $table->string('PhoneNumber')->nullable();
            $table->string('Location')->nullable();
            $table->string('ProfileImage')->nullable();
            $table->string('UserType')->nullable();
            $table->boolean('IsAdmin')->default(0);
            $table->boolean('IsSuspended')->default(0);
            $table->timestamp('SuspendedUntil')->nullable();
            $table->text('SuspensionReason')->nullable();
            $table->unsignedBigInteger('SuspendedByAdminID')->nullable();
            $table->string('BankName')->nullable();
            $table->string('BankAccountNumber')->nullable();
            $table->string('BankAccountHolderName')->nullable();
            $table->string('role')->default('user');
            $table->rememberToken();
            $table->timestamp('CreatedAt')->useCurrent();
            $table->timestamp('UpdatedAt')->useCurrent()->useCurrentOnUpdate();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('Email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->unsignedBigInteger('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
