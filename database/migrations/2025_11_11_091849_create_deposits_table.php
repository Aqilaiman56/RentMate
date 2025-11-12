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
            $table->unsignedBigInteger('BookingID');
            $table->decimal('DepositAmount', 10, 2);
            $table->string('Status', 50);
            $table->string('RefundMethod', 100)->nullable();
            $table->string('RefundReference', 255)->nullable();
            $table->date('DateCollected')->nullable();
            $table->date('RefundDate')->nullable();
            $table->text('Notes')->nullable();
            $table->timestamps();

            $table->foreign('BookingID')->references('BookingID')->on('booking')->onDelete('cascade');
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
