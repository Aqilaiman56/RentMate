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
        Schema::create('penalty', function (Blueprint $table) {
            $table->id('PenaltyID');
            $table->unsignedBigInteger('ReportID')->nullable();
            $table->unsignedBigInteger('ReportedByID');
            $table->unsignedBigInteger('ReportedUserID');
            $table->unsignedBigInteger('BookingID')->nullable();
            $table->unsignedBigInteger('ItemID')->nullable();
            $table->unsignedBigInteger('ApprovedByAdminID')->nullable();
            $table->text('Description');
            $table->string('EvidencePath', 500)->nullable();
            $table->decimal('PenaltyAmount', 10, 2)->nullable();
            $table->boolean('ResolvedStatus')->default(false);
            $table->timestamp('DateReported')->useCurrent();

            $table->foreign('ReportID')->references('ReportID')->on('reports')->onDelete('cascade');
            $table->foreign('ReportedByID')->references('UserID')->on('users')->onDelete('cascade');
            $table->foreign('ReportedUserID')->references('UserID')->on('users')->onDelete('cascade');
            $table->foreign('BookingID')->references('BookingID')->on('booking')->onDelete('set null');
            $table->foreign('ItemID')->references('ItemID')->on('items')->onDelete('set null');
            $table->foreign('ApprovedByAdminID')->references('UserID')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penalty');
    }
};
