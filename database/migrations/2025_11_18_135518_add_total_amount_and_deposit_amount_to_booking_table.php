<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('booking', function (Blueprint $table) {
            $table->decimal('TotalAmount', 10, 2)->default(0)->after('EndDate');
            $table->decimal('DepositAmount', 10, 2)->default(0)->after('TotalAmount');
        });

        // Update existing bookings with calculated amounts
        DB::statement('
            UPDATE booking b
            JOIN items i ON b.ItemID = i.ItemID
            SET
                b.TotalAmount = i.PricePerDay * DATEDIFF(b.EndDate, b.StartDate),
                b.DepositAmount = i.DepositAmount
        ');
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
