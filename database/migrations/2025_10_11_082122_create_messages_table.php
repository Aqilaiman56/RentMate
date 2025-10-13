<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("
            CREATE TABLE `messages` (
                `MessageID` int(11) NOT NULL AUTO_INCREMENT,
                `SenderID` int(11) NOT NULL,
                `ReceiverID` int(11) NOT NULL,
                `ItemID` int(11) DEFAULT NULL,
                `MessageContent` text NOT NULL,
                `IsRead` tinyint(1) NOT NULL DEFAULT '0',
                `SentAt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
                PRIMARY KEY (`MessageID`),
                KEY `SenderID` (`SenderID`),
                KEY `ReceiverID` (`ReceiverID`),
                KEY `ItemID` (`ItemID`),
                CONSTRAINT `messages_senderid_foreign` FOREIGN KEY (`SenderID`) REFERENCES `users` (`UserID`) ON DELETE CASCADE,
                CONSTRAINT `messages_receiverid_foreign` FOREIGN KEY (`ReceiverID`) REFERENCES `users` (`UserID`) ON DELETE CASCADE,
                CONSTRAINT `messages_itemid_foreign` FOREIGN KEY (`ItemID`) REFERENCES `items` (`ItemID`) ON DELETE SET NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        ");
    }

    public function down(): void
    {
        Schema::dropIfExists('messages');
    }
};