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

        $counter = 1;
        $prefix = 'APR';

        DB::transaction(function () use ($works, $prefix, &$counter) {
            foreach ($works as $work) {
                $newCustomId = $prefix . '-' . str_pad($counter, 2, '0', STR_PAD_LEFT);
                $oldCustomId = $work->custom_id;
                
                $work->custom_id = $newCustomId;
                
                // save quietly so it doesn't trigger observers if any
                $work->saveQuietly();

                $this->line("Updated Work ID {$work->id}: {$oldCustomId} -> {$newCustomId}");
                $counter++;
            }
        });

        $this->info('Successfully updated custom_ids for April 2026!');
    }
}
