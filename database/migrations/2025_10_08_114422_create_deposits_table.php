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
        Schema::create('deposits', function (Blueprint $table) {
            $table->id('DepositID');
            
            // Match the exact type from booking table
            // Try unsignedInteger first (int), if booking table uses int
            $table->unsignedInteger('BookingID');
            
            $table->decimal('DepositAmount', 10, 2);
            $table->enum('Status', ['held', 'refunded', 'forfeited', 'partial'])->default('held');
            $table->date('DateCollected');
            $table->date('RefundDate')->nullable();
            $table->text('Notes')->nullable();
            $table->timestamps();

            // Indexes
            $table->index('BookingID');
            $table->index('Status');
            $table->index('DateCollected');
        });

        // Add foreign key in a separate statement
        Schema::table('deposits', function (Blueprint $table) {
            $table->foreign('BookingID')
                  ->references('BookingID')
                  ->on('booking')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('deposits');
    }
};