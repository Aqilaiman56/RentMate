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
        Schema::create('review', function (Blueprint $table) {
            $table->id('ReviewID');
            $table->unsignedBigInteger('UserID');
            $table->unsignedBigInteger('ItemID');
            $table->integer('Rating');
            $table->text('Comment')->nullable();
            $table->string('ReviewImage', 500)->nullable();
            $table->timestamp('DatePosted')->useCurrent();
            $table->boolean('IsReported')->default(false);

            $table->foreign('UserID')->references('UserID')->on('users')->onDelete('cascade');
            $table->foreign('ItemID')->references('ItemID')->on('items')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('review');
    }
};
