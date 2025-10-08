<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reports', function (Blueprint $table) {
            $table->id('ReportID');
            $table->unsignedBigInteger('ReportedByID'); // User who made the report
            $table->unsignedBigInteger('ReportedUserID'); // User being reported
            $table->unsignedBigInteger('BookingID')->nullable(); // Related booking if applicable
            $table->unsignedBigInteger('ItemID')->nullable(); // Related item if applicable
            $table->enum('ReportType', ['item-damage', 'late-return', 'dispute', 'fraud', 'harassment', 'other']);
            $table->enum('Priority', ['low', 'medium', 'high'])->default('medium');
            $table->string('Subject');
            $table->text('Description');
            $table->string('EvidencePath')->nullable();
            $table->enum('Status', ['pending', 'investigating', 'resolved', 'dismissed'])->default('pending');
            $table->unsignedBigInteger('ReviewedByAdminID')->nullable();
            $table->text('AdminNotes')->nullable();
            $table->date('DateReported');
            $table->date('DateResolved')->nullable();
            $table->timestamps();

            // Indexes
            $table->index('ReportedByID');
            $table->index('ReportedUserID');
            $table->index('Status');
            $table->index('Priority');
            $table->index('DateReported');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};