<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->integer('PaymentID', true);
            $table->integer('BookingID');
            $table->string('BillCode', 50)->nullable();
            $table->decimal('Amount', 10, 2);
            $table->string('PaymentMethod', 50)->nullable();
            $table->enum('Status', ['pending', 'successful', 'failed', 'refunded'])->default('pending');
            $table->string('TransactionID', 100)->nullable();
            $table->text('PaymentResponse')->nullable();
            $table->timestamp('PaymentDate')->nullable();
            $table->timestamp('CreatedAt')->useCurrent();
            
            $table->index('BookingID');
            $table->index('BillCode');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};