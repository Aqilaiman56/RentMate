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
            if (!Schema::hasColumn('users', 'IsSuspended')) {
                $table->boolean('IsSuspended')->default(false);
            }
            if (!Schema::hasColumn('users', 'SuspendedUntil')) {
                $table->timestamp('SuspendedUntil')->nullable();
            }
            if (!Schema::hasColumn('users', 'SuspensionReason')) {
                $table->text('SuspensionReason')->nullable();
            }
            if (!Schema::hasColumn('users', 'SuspendedByAdminID')) {
                $table->unsignedBigInteger('SuspendedByAdminID')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['IsSuspended', 'SuspendedUntil', 'SuspensionReason', 'SuspendedByAdminID']);
        });
    }
};
