<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('taxes', function (Blueprint $table) {
            $table->id('TaxID');
            $table->integer('BookingID');
            $table->unsignedBigInteger('UserID');
            $table->decimal('TaxAmount', 10, 2)->default(1.00);
            $table->date('DateCollected');
            $table->string('TaxType')->default('Booking Tax');
            $table->text('Description')->nullable();
            $table->timestamps();

            // Indexes for faster queries
            $table->index('BookingID');
            $table->index('UserID');
            $table->index('DateCollected');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('taxes');
    }
};