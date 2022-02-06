<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Console\ConfirmableTrait;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schema;

class Setup extends Command
{
    use ConfirmableTrait;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'setup
                            {--force : Force the operation to run when in production.}
                            {--skip-storage-link : Skip storage link create.}';

    /**
     * The console command description.
     *
     * @var string|null
     */
    protected $description = 'Install or update the application, and run migrations after a new release';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        if ($this->confirmToProceed()) {
            // Clear or rebuild all cache
            if (config('cache.default') != 'database' || Schema::hasTable(config('cache.stores.database.table'))) {
                $this->artisan('✓ Resetting application cache', 'cache:clear');
            }

            if ($this->getLaravel()->environment() == 'production') {
                // @codeCoverageIgnoreStart
                $this->artisan('✓ Clear config cache', 'config:clear');
                $this->artisan('✓ Resetting route cache', 'route:cache');
                $this->artisan('✓ Resetting view cache', 'view:clear');
                // @codeCoverageIgnoreEnd
            } else {
                $this->artisan('✓ Clear config cache', 'config:clear');
                $this->artisan('✓ Clear route cache', 'route:clear');
                $this->artisan('✓ Clear view cache', 'view:clear');
            }

            if ($this->option('skip-storage-link') !== true
                && $this->getLaravel()->environment() != 'testing'
                && ! file_exists(public_path('storage'))) {
                $this->artisan('✓ Symlink the storage folder', 'storage:link'); // @codeCoverageIgnore
            }

            $this->artisan('✓ Performing migrations', 'migrate', ['--force' => true]);

            // Cache config
            if ($this->getLaravel()->environment() == 'production'
                && (config('cache.default') != 'database' || Schema::hasTable(config('cache.stores.database.table')))) {
                $this->artisan('✓ Cache configuraton', 'config:cache'); // @codeCoverageIgnore
            }
        }
    }

    private function artisan(string $message, string $command, array $options = [])
    {
        $this->info($message);
        Artisan::call($command, $options);
    }
}
