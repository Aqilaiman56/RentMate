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
            // Primary key as int(11)
            $table->integer('DepositID', true, true)->length(11);
            
            // Foreign key matching booking table - int(11)
            $table->integer('BookingID', false, true)->length(11);
            
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
            
            // Foreign key constraint
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