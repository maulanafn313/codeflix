<?php

namespace App\Console\Commands;

use Illuminate\Bus\Batch;
use Illuminate\Console\Command;
use App\Jobs\CheckMembershipStatus;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Log;

class CheckMemberships extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'memberships:check';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check and deactivate expired memberships';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // CheckMembershipStatus::dispatch();
        Bus::batch([
            new CheckMembershipStatus(),
        ])->then(function (Batch $batch) {
            // This callback is called when the batch has been processed
            Log::info('Membership check batch completed successfully.');
        })->catch(function (Batch $batch, $e) {
            // This callback is called if any job in the batch fails
            Log::error('An error occurred while processing the membership check: ' . $e->getMessage());
        })->finally(function (Batch $batch) {
            // This callback is called after the batch has been processed, regardless of success or failure
            Log::info('Membership check batch has finished.');
        })->dispatch();
    }
}
