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
        Schema::table('booking', function (Blueprint $table) {
            // Add missing columns
            $table->decimal('TotalAmount', 10, 2)->nullable()->after('EndDate');
            $table->decimal('DepositAmount', 10, 2)->nullable()->after('TotalAmount');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('booking', function (Blueprint $table) {
            $table->dropColumn(['TotalAmount', 'DepositAmount']);
        });
    }
};
