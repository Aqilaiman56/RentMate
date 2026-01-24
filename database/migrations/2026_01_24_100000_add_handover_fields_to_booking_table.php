<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('booking', function (Blueprint $table) {
            $table->boolean('OwnerHandoverConfirmed')->default(false)->after('ReturnConfirmed');
            $table->boolean('RenterHandoverConfirmed')->default(false)->after('OwnerHandoverConfirmed');
            $table->timestamp('HandoverConfirmedAt')->nullable()->after('RenterHandoverConfirmed');
        });
    }

    public function down(): void
    {
        Schema::table('booking', function (Blueprint $table) {
            $table->dropColumn(['OwnerHandoverConfirmed', 'RenterHandoverConfirmed', 'HandoverConfirmedAt']);
        });
    }
};
