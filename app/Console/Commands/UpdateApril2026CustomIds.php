<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Work;
use Illuminate\Support\Facades\DB;

class UpdateApril2026CustomIds extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'work:update-april-ids';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update custom_ids for Work models created in April 2026';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Finding works created in April 2026...');

        // Get works created in April 2026, ordered by ID so they are updated sequentially
        $works = Work::whereYear('created_at', 2026)
            ->whereMonth('created_at', 4)
            ->orderBy('id', 'asc')
            ->get();

        if ($works->isEmpty()) {
            $this->info('No works found for April 2026.');
            return;
        }

        $this->info('Found ' . $works->count() . ' works. Updating...');

        $prefix = 'APR';
        $counters = []; // Keep track of the counter per valuer

        DB::transaction(function () use ($works, $prefix, &$counters) {
            foreach ($works as $work) {
                $valuer = $work->valuer ?? 'unknown';
                if (!isset($counters[$valuer])) {
                    $counters[$valuer] = 1;
                }

                $newCustomId = $prefix . '-' . str_pad($counters[$valuer], 2, '0', STR_PAD_LEFT);
                $oldCustomId = $work->custom_id;
                
                $work->custom_id = $newCustomId;
                
                // save quietly so it doesn't trigger observers if any
                $work->saveQuietly();

                $this->line("Updated Work ID {$work->id} (Valuer: {$valuer}): {$oldCustomId} -> {$newCustomId}");
                $counters[$valuer]++;
            }
        });

        $this->info('Successfully updated custom_ids for April 2026!');
    }
}
