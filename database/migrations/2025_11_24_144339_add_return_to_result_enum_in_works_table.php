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
        // Modify the enum column to include Return
        // Check if database uses +ve/-ve or Positive/Negative
        // Adding Return to both possible enum structures
        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE works MODIFY COLUMN result ENUM('+ve', '-ve', 'Positive', 'Negative', 'Hold', 'Canceled', 'Return') NULL");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert back to previous enum values
        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE works MODIFY COLUMN result ENUM('+ve', '-ve', 'Hold', 'Canceled') NULL");
        }
    }
};
