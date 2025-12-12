<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class DeleteScheduledUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'users:delete-scheduled';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Soft deletes users scheduled for deletion';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // First update the status
        User::where('status', 'deactivated')
            ->where('scheduled_for_deletion', '<=', now())
            ->update(['status' => 'deleted']);

        // Then soft delete in batches
        $count = User::where('status', 'deleted')
            ->whereNull('deleted_at')
            ->where('scheduled_for_deletion', '<=', now())
            ->chunkById(200, function ($users) {
                foreach ($users as $user) {
                    $user->delete();
                }
            });

        $this->info("Soft deleted {$count} scheduled users.");
    }
}
