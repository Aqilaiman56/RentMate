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
        Schema::create('booking', function (Blueprint $table) {
            $table->id('BookingID');
            $table->unsignedBigInteger('UserID');
            $table->unsignedBigInteger('ItemID');
            $table->date('StartDate');
            $table->date('EndDate');
            $table->timestamp('BookingDate')->useCurrent();
            $table->decimal('ServiceFeeAmount', 10, 2)->nullable();
            $table->decimal('TotalPaid', 10, 2)->nullable();
            $table->string('Status', 50);
            $table->boolean('ReturnConfirmed')->default(false);

            $table->foreign('UserID')->references('UserID')->on('users')->onDelete('cascade');
            $table->foreign('ItemID')->references('ItemID')->on('items')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('booking');
    }
};
