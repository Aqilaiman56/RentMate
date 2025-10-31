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
        // Rename the table from 'taxes' to 'service_fees'
        Schema::rename('taxes', 'service_fees');

        // Rename columns in the service_fees table
        Schema::table('service_fees', function (Blueprint $table) {
            $table->renameColumn('TaxID', 'ServiceFeeID');
            $table->renameColumn('TaxAmount', 'ServiceFeeAmount');
            $table->renameColumn('TaxType', 'ServiceFeeType');
        });

        // Also rename the column in the booking table
        Schema::table('booking', function (Blueprint $table) {
            $table->renameColumn('TaxAmount', 'ServiceFeeAmount');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Rename columns back in the booking table
        Schema::table('booking', function (Blueprint $table) {
            $table->renameColumn('ServiceFeeAmount', 'TaxAmount');
        });

        // Rename columns back in the service_fees table
        Schema::table('service_fees', function (Blueprint $table) {
            $table->renameColumn('ServiceFeeID', 'TaxID');
            $table->renameColumn('ServiceFeeAmount', 'TaxAmount');
            $table->renameColumn('ServiceFeeType', 'TaxType');
        });

        // Rename the table back from 'service_fees' to 'taxes'
        Schema::rename('service_fees', 'taxes');
    }
};
