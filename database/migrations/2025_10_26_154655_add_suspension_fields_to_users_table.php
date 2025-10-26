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
            $table->boolean('IsSuspended')->default(false)->after('IsAdmin');
            $table->timestamp('SuspendedUntil')->nullable()->after('IsSuspended');
            $table->text('SuspensionReason')->nullable()->after('SuspendedUntil');
            $table->unsignedBigInteger('SuspendedByAdminID')->nullable()->after('SuspensionReason');
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
