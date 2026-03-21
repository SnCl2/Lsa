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
        // Modify the enum column to include c and d

        DB::statement("ALTER TABLE works MODIFY COLUMN valuer ENUM('a', 'b', 'c', 'd') NULL");

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert back to original enum values

        DB::statement("ALTER TABLE works MODIFY COLUMN valuer ENUM('a', 'b') NULL");

    }
};
