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
            CREATE TABLE `notifications` (
                `NotificationID` int(11) NOT NULL AUTO_INCREMENT,
                `UserID` int(11) NOT NULL,
                `Type` varchar(50) NOT NULL,
                `Title` varchar(255) NOT NULL,
                `Content` text NOT NULL,
                `RelatedID` int(11) DEFAULT NULL,
                `RelatedType` varchar(50) DEFAULT NULL,
                `IsRead` tinyint(1) NOT NULL DEFAULT '0',
                `CreatedAt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
                PRIMARY KEY (`NotificationID`),
                KEY `UserID` (`UserID`),
                KEY `IsRead` (`IsRead`),
                CONSTRAINT `notifications_userid_foreign` FOREIGN KEY (`UserID`) REFERENCES `users` (`UserID`) ON DELETE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        ");
    }

    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};