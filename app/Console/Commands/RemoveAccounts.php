<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Console\ConfirmableTrait;

class RemoveAccounts extends Command
{
    use ConfirmableTrait;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:remove-accounts
                            {--force : Force the operation to run when in production.}';

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
        if ($this->confirmToProceed()) {
            User::where('created_at', '<', now()->subDay())
                ->chunkById(200, function ($users) {
                    foreach ($users as $user) {
                        $this->info("Delete {$user->name} {$user->email}");
                        $user->delete();
                    }
                });
        }
    }
}
