<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // RefundMethod and RefundReference columns already exist in the create_deposits_table migration
        // This migration is kept for historical purposes but does nothing
    }

    public function down(): void
    {
        // Nothing to rollback since columns already exist in base migration
    }
};