<?php

namespace App\Console\Commands;

use App\Models\Action;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class SyncActions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:actions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info("Scanning files...");

        $integrations = Action::scan();
        $this->info("Found " . count($integrations) ." actions");

        $integrationsInDatabase = Action::get()->keyBy('type');

        foreach($integrations as $integrationClass) {
            $this->output->write($integrationClass::$slug . ": ");

            if ($this->isIntegrationInDatabase($integrationsInDatabase, $integrationClass)) {
                continue;
            }
            $this->createIntegration($integrationClass);
        }

        $this->removeOldRecords($integrationsInDatabase);
        $this->info("Finished synchronizing integrations.");
    }

    protected function isIntegrationInDatabase(Collection $integrationsInDatabase, string $integrationClass): bool
    {
        $integrationInDatabase = $integrationsInDatabase->get($integrationClass);
        if ($integrationInDatabase) {
            $this->info("already in database");
            $integrationsInDatabase->forget($integrationClass);
            return true;
        }
        return false;
    }

    protected function createIntegration(string $class): void
    {
        Action::create([
            'slug' => $class::$slug,
            'type' => $class,
        ]);
        $this->info("added to database");
    }

    protected function removeOldRecords(Collection $integrationsInDatabase): void
    {
        if ($oldRecords = count($integrationsInDatabase)) {
            $integrationsInDatabase->map->delete();
            $this->info("Removed {$oldRecords} old records from database");
        }
    }
}
