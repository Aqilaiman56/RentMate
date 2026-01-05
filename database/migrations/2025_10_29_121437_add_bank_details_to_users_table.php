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
            // These columns now added in create_users_table migration
            if (!Schema::hasColumn('users', 'BankName')) {
                $table->string('BankName', 100)->nullable()->after('PhoneNumber');
            }
            if (!Schema::hasColumn('users', 'BankAccountNumber')) {
                $table->string('BankAccountNumber', 50)->nullable()->after('BankName');
            }
            if (!Schema::hasColumn('users', 'BankAccountHolderName')) {
                $table->string('BankAccountHolderName', 100)->nullable()->after('BankAccountNumber');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['BankName', 'BankAccountNumber', 'BankAccountHolderName']);
        });
    }
};
