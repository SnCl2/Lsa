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
        if (!Schema::hasTable('bank_names')) {
            Schema::create('bank_names', function (Blueprint $table) {
                $table->id();
                $table->string('name')->unique();
                $table->timestamps();
            });
        }

        // Seed existing bank names from works table so existing data is immediately available
        if (Schema::hasTable('works')) {
            $existingBanks = DB::table('works')
                ->whereNotNull('bank_name')
                ->where('bank_name', '!=', '')
                ->selectRaw('TRIM(bank_name) as name')
                ->distinct()
                ->pluck('name');

            $now = now();
            $seen = [];
            $insertData = [];

            foreach ($existingBanks as $bName) {
                $trimmed = trim($bName);
                $lower = strtolower($trimmed);
                if ($trimmed !== '' && !isset($seen[$lower])) {
                    $seen[$lower] = true;
                    // Don't insert if already exists in table
                    if (!DB::table('bank_names')->where('name', $trimmed)->exists()) {
                        $insertData[] = [
                            'name' => $trimmed,
                            'created_at' => $now,
                            'updated_at' => $now,
                        ];
                    }
                }
            }

            if (!empty($insertData)) {
                DB::table('bank_names')->insert($insertData);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bank_names');
    }
};
