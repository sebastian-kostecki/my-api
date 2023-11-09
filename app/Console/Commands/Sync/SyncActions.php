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
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $this->info("Scanning files...");

        $actions = Action::scan();
        $this->info("Found " . count($actions) . " actions");

        $actionsInDatabase = Action::get()->keyBy('type');

        foreach ($actions as $actionClass) {
            $initData = $actionClass::getInitAction();

            $this->output->write($initData['name'] . ": ");
            if ($this->isIntegrationInDatabase($actionsInDatabase, $actionClass)) {
                $this->syncModel($actionClass, $initData);
                continue;
            }
            $this->createIntegration($actionClass, $initData);
        }

        $this->removeOldRecords($actionsInDatabase);
        $this->info("Finished synchronizing actions.");
    }

    /**
     * @param Collection $integrationsInDatabase
     * @param string $integrationClass
     * @return bool
     */
    protected function isIntegrationInDatabase(Collection $integrationsInDatabase, string $integrationClass): bool
    {
        if ($integrationsInDatabase->has($integrationClass)) {
            $this->info("already in database");
            $integrationsInDatabase->forget($integrationClass);
            return true;
        }
        return false;
    }

    /**
     * @param string $actionClass
     * @param array $data
     * @return void
     */
    protected function syncModel(string $actionClass, array $data): void
    {
        $action = Action::where('type', $actionClass)->first();
        try {
            $action->model;
        } catch (\Throwable $throwable) {
            $action->model = $data['model'];
            $action->save();
        }
    }

    /**
     * @param string $class
     * @param array $data
     * @return void
     */
    protected function createIntegration(string $class, array $data): void
    {
        Action::create([
            'name' => $data['name'],
            'type' => $class,
            'icon' => $data['icon'],
            'shortcut' => $data['shortcut'],
            'model' => $data['model'],
            'system_prompt' => $data['system_prompt'] ?? null,
            'enabled' => true
        ]);
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
