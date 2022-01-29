<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class RemoveAccounts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:remove-accounts';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remove accounts created more than 24h ago';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        User::where('created_at', '<', now()->subDay())
            ->chunkById(200, function ($users) {
                foreach ($users as $user) {
                    $this->info("Delete {$user->name} {$user->email}");
                    $user->delete();
                }
            });
    }
}
