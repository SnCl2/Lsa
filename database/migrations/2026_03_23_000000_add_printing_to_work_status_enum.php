<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Add 'Printing' and 'On Delivery' to the status enum
        // Existing: 'New File', 'Surveying', 'Reporting', 'Checking', 'Completed', 'Hold', 'Canceled'
        DB::statement("ALTER TABLE works MODIFY COLUMN status ENUM('New File', 'Surveying', 'Reporting', 'Checking', 'Printing', 'Completed', 'Hold', 'Canceled', 'On Delivery') NOT NULL DEFAULT 'New File'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert to previous enum values
        DB::statement("ALTER TABLE works MODIFY COLUMN status ENUM('New File', 'Surveying', 'Reporting', 'Checking', 'Completed', 'Hold', 'Canceled') NOT NULL DEFAULT 'New File'");
    }
};
