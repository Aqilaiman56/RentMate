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
        Schema::create('refund_queue', function (Blueprint $table) {
            $table->id('RefundQueueID');
            $table->unsignedBigInteger('DepositID');
            $table->unsignedInteger('BookingID');
            $table->unsignedBigInteger('UserID');
            $table->decimal('RefundAmount', 10, 2);
            $table->enum('Status', ['pending', 'processing', 'completed', 'failed'])->default('pending');
            $table->string('BankName', 100);
            $table->string('BankAccountNumber', 50);
            $table->string('BankAccountHolderName', 100);
            $table->string('RefundReference', 100)->nullable();
            $table->text('Notes')->nullable();
            $table->timestamp('ProcessedAt')->nullable();
            $table->unsignedBigInteger('ProcessedBy')->nullable(); // Admin UserID
            $table->string('ProofOfTransfer', 255)->nullable(); // Receipt upload path
            $table->timestamps();

            // Indexes for performance (no foreign keys due to mixed column types)
            $table->index('DepositID');
            $table->index('BookingID');
            $table->index('UserID');
            $table->index('Status');
            $table->index('ProcessedBy');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('refund_queue');
    }
};
