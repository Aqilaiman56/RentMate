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
        Schema::create('items', function (Blueprint $table) {
            $table->id('ItemID');
            $table->unsignedBigInteger('UserID');
            $table->string('ItemName', 255);
            $table->text('Description');
            $table->unsignedBigInteger('CategoryID');
            $table->unsignedBigInteger('LocationID');
            $table->boolean('Availability')->default(true);
            $table->integer('Quantity')->default(1);
            $table->integer('AvailableQuantity')->default(1);
            $table->decimal('DepositAmount', 10, 2);
            $table->timestamp('DateAdded')->useCurrent();
            $table->decimal('PricePerDay', 10, 2);

            $table->foreign('UserID')->references('UserID')->on('users')->onDelete('cascade');
            $table->foreign('CategoryID')->references('CategoryID')->on('category')->onDelete('restrict');
            $table->foreign('LocationID')->references('LocationID')->on('location')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};
