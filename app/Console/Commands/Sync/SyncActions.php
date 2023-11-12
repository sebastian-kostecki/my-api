<?php

namespace App\Console\Commands\Sync;

use App\Models\Action;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Collection;

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
    protected $description = 'Synchronize actions';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $this->info("Scanning files...");

        $actions = Action::scan();
        $this->info("Found " . count($actions) . " actions");

        $actionsInDatabase = Action::get()->keyBy('name');

        foreach ($actions as $actionClass) {
            $initData = $actionClass::getInitAction();

            $this->output->write($initData['name'] . ": ");
            if ($this->isIntegrationInDatabase($actionsInDatabase, $initData)) {
                $this->syncModel($initData);
                continue;
            }
            $this->createIntegration($initData);
        }

        $this->removeOldRecords($actionsInDatabase);
        $this->info("Finished synchronizing actions.");
    }

    /**
     * @param Collection $integrationsInDatabase
     * @param array $params
     * @return bool
     */
    protected function isIntegrationInDatabase(Collection $integrationsInDatabase, array $params): bool
    {
        if ($integrationsInDatabase->has($params['name'])) {
            $this->info("already in database");
            $integrationsInDatabase->forget($params['name']);
            return true;
        }
        return false;
    }

    /**
     * @param array $params
     * @return void
     */
    protected function syncModel(array $params): void
    {
        $action = Action::where('name', $params['name'])->first();
        try {
            $action->model;
        } catch (\Throwable $throwable) {
            $action->model = $params['model'];
            $action->save();
        }
    }

    /**
     * @param array $params
     * @return void
     */
    protected function createIntegration(array $params): void
    {
        Action::create($params);
        $this->info("added to database");
    }

    /**
     * @param Collection $integrationsInDatabase
     * @return void
     */
    protected function removeOldRecords(Collection $integrationsInDatabase): void
    {
        if ($oldRecords = count($integrationsInDatabase)) {
            $integrationsInDatabase->map->delete();
            $this->info("Removed {$oldRecords} old records from database");
        }
    }
}
