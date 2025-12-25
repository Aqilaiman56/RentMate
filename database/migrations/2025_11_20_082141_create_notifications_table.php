<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->id('NotificationID');
            $table->unsignedBigInteger('UserID');
            $table->string('Type', 50);
            $table->string('Title', 255);
            $table->text('Content');
            $table->unsignedBigInteger('RelatedID')->nullable();
            $table->string('RelatedType', 50)->nullable();
            $table->boolean('IsRead')->default(false);
            $table->timestamp('CreatedAt')->useCurrent();

            $table->index('UserID');
            $table->index('IsRead');

            $table->foreign('UserID')->references('UserID')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};