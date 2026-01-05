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
            if (!Schema::hasColumn('users', 'PhoneNumber')) {
                $table->string('PhoneNumber', 20)->nullable()->after('Email');
            }
            if (!Schema::hasColumn('users', 'Location')) {
                $table->string('Location', 255)->nullable()->after('PhoneNumber');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['PhoneNumber', 'Location']);
        });
    }
};