<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Work;
use Illuminate\Support\Facades\DB;

class UpdateJune2026CustomIds extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'work:update-june-ids';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update custom_ids for Work models created in June 2026 to be continuous from May';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Finding works created in June 2026...');

        // Get works created in June 2026, ordered by ID so they are updated sequentially
        $works = Work::whereYear('created_at', 2026)
            ->whereMonth('created_at', 6)
            ->orderBy('id', 'asc')
            ->get();

        if ($works->isEmpty()) {
            $this->info('No works found for June 2026.');
            return;
        }

        $this->info('Found ' . $works->count() . ' works. Updating...');

        $prefix = 'JUN';
        $counters = []; // Keep track of the counter per valuer

        DB::transaction(function () use ($works, $prefix, &$counters) {
            foreach ($works as $work) {
                $valuer = $work->valuer ?? 'unknown';

                // Initialize the counter for this valuer based on their last work before June
                if (!isset($counters[$valuer])) {
                    $lastWork = Work::where('valuer', $valuer)
                        ->where('created_at', '<', '2026-06-01 00:00:00')
                        ->whereNotNull('custom_id')
                        ->where('custom_id', 'like', '%-%')
                        ->orderBy('id', 'desc')
                        ->first();

                    $startNumber = 0;
                    if ($lastWork && !empty($lastWork->custom_id)) {
                        $parts = explode('-', $lastWork->custom_id);
                        if (count($parts) === 2 && is_numeric($parts[1])) {
                            $startNumber = (int) $parts[1];
                        }
                    }
                    $counters[$valuer] = $startNumber;
                    $this->line("Initialized counter for Valuer '{$valuer}' at: {$startNumber}");
                }

                // Increment the counter for this valuer
                $counters[$valuer]++;

                $newCustomId = $prefix . '-' . str_pad($counters[$valuer], 2, '0', STR_PAD_LEFT);
                $oldCustomId = $work->custom_id;
                
                if ($oldCustomId !== $newCustomId) {
                    $work->custom_id = $newCustomId;
                    // save quietly so it doesn't trigger observers if any
                    $work->saveQuietly();
                    $this->line("Updated Work ID {$work->id} (Valuer: {$valuer}): {$oldCustomId} -> {$newCustomId}");
                } else {
                    $this->line("Unchanged Work ID {$work->id} (Valuer: {$valuer}): {$oldCustomId}");
                }
            }
        });

        $this->info('Successfully updated custom_ids for June 2026!');
    }
}
