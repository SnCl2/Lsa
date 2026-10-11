<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('sources:setup', function () {
    if (!\Illuminate\Support\Facades\Schema::hasTable('sources')) {
        \Illuminate\Support\Facades\Schema::create('sources', function (\Illuminate\Database\Schema\Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->timestamps();
        });
        $this->info("Table 'sources' created successfully.");
    } else {
        $this->info("Table 'sources' already exists.");
    }

    if (\Illuminate\Support\Facades\Schema::hasTable('works')) {
        $existingSources = \Illuminate\Support\Facades\DB::table('works')
            ->whereNotNull('source')
            ->where('source', '!=', '')
            ->selectRaw('TRIM(source) as name')
            ->distinct()
            ->pluck('name');

        $now = now();
        $seen = [];
        $insertData = [];

        foreach ($existingSources as $sName) {
            $trimmed = trim($sName);
            $lower = strtolower($trimmed);
            if ($trimmed !== '' && !isset($seen[$lower])) {
                $seen[$lower] = true;
                if (!\Illuminate\Support\Facades\DB::table('sources')->where('name', $trimmed)->exists()) {
                    $insertData[] = [
                        'name' => $trimmed,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ];
                }
            }
        }

        if (!empty($insertData)) {
            foreach (array_chunk($insertData, 100) as $chunk) {
                \Illuminate\Support\Facades\DB::table('sources')->insert($chunk);
            }
            $this->info("Seeded " . count($insertData) . " sources into 'sources' table.");
        } else {
            $this->info("No new sources to seed.");
        }
    }
    $this->info("Total sources count: " . \Illuminate\Support\Facades\DB::table('sources')->count());
})->purpose('Create sources table and seed legacy sources from works table');

