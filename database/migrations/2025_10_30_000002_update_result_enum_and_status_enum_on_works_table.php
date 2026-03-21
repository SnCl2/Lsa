<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('works', function (Blueprint $table) {
            // Expand result options to include Hold and Canceled
            $table->enum('result', ['+ve', '-ve', 'Hold', 'Canceled'])->nullable()->change();

            // Optional: shrink status options by removing Hold/Canceled (keep for legacy rows if DB enforces)
            // If using ENUM and you want to strictly drop values, uncomment the next line,
            // but note: this may fail if rows still have these values. Consider data migration first.
            // $table->enum('status', ['New File', 'Surveying', 'Reporting', 'Checking', 'Completed'])->default('New File')->change();
        });
    }

    public function down(): void
    {
        Schema::table('works', function (Blueprint $table) {
            $table->enum('result', ['+ve', '-ve'])->nullable()->change();
            // $table->enum('status', ['New File', 'Surveying', 'Reporting', 'Checking', 'Completed', 'Hold', 'Canceled'])->default('New File')->change();
        });
    }
};


