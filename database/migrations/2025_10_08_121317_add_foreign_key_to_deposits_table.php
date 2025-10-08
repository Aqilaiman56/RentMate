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
        Schema::table('deposits', function (Blueprint $table) {
            // First, make sure the column types match exactly
            // Check if BookingID needs to be modified
            $table->integer('BookingID', false, true)->length(11)->change();
            
            // Add the foreign key constraint
            $table->foreign('BookingID', 'deposits_bookingid_fk')
                  ->references('BookingID')
                  ->on('booking')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('deposits', function (Blueprint $table) {
            $table->dropForeign('deposits_bookingid_fk');
        });
    }
};