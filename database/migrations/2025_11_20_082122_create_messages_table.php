<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('messages', function (Blueprint $table) {
            $table->id('MessageID');
            $table->unsignedBigInteger('SenderID');
            $table->unsignedBigInteger('ReceiverID');
            $table->unsignedBigInteger('ItemID')->nullable();
            $table->text('MessageContent');
            $table->boolean('IsRead')->default(false);
            $table->timestamp('SentAt')->useCurrent();

            $table->index('SenderID');
            $table->index('ReceiverID');
            $table->index('ItemID');

            $table->foreign('SenderID')->references('UserID')->on('users')->onDelete('cascade');
            $table->foreign('ReceiverID')->references('UserID')->on('users')->onDelete('cascade');
            $table->foreign('ItemID')->references('ItemID')->on('items')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('messages');
    }
};