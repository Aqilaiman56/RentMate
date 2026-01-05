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
        Schema::create('forfeit_queue', function (Blueprint $table) {
            $table->id('ForfeitQueueID');
            $table->unsignedBigInteger('DepositID');
            $table->unsignedInteger('BookingID');
            $table->unsignedBigInteger('OwnerUserID'); // Item owner who receives the forfeit amount
            $table->unsignedBigInteger('RenterUserID'); // Renter who forfeited
            $table->decimal('ForfeitAmount', 10, 2);
            $table->enum('Status', ['pending', 'processing', 'completed', 'failed'])->default('pending');
            $table->string('BankName', 100);
            $table->string('BankAccountNumber', 50);
            $table->string('BankAccountHolderName', 100);
            $table->string('ForfeitReference', 100)->nullable();
            $table->text('Reason')->nullable();
            $table->text('Notes')->nullable();
            $table->timestamp('ProcessedAt')->nullable();
            $table->unsignedBigInteger('ProcessedBy')->nullable(); // Admin UserID
            $table->string('ProofOfTransfer', 255)->nullable(); // Receipt upload path
            $table->timestamps();

            // Indexes for performance
            $table->index('DepositID');
            $table->index('BookingID');
            $table->index('OwnerUserID');
            $table->index('RenterUserID');
            $table->index('Status');
            $table->index('ProcessedBy');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('forfeit_queue');
    }
};
