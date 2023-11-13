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

    private \Illuminate\Support\Collection $actions;
    private Collection $actionsInDatabase;


    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $this->getActions();
        $this->getActionsInDatabase();

        $this->actions->each(function ($action) {
            $this->createOrIgnore($action);
        });
        $this->removeOldRecords();
        $this->info("Finished synchronizing actions.");
    }

    /**
     * @return void
     */
    protected function getActions(): void
    {
        $this->info("Scanning files...");
        $this->actions = Action::scan();
        $this->info("Found " . $this->actions->count() . " actions");
    }

    /**
     * @return void
     */
    protected function getActionsInDatabase(): void
    {
        $this->actionsInDatabase = Action::get()->keyBy('type');
    }

    /**
     * @param string $actionClass
     * @return void
     */
    protected function createOrIgnore(string $actionClass): void
    {
        $initActionData = $actionClass::getInitAction();
        $this->output->write($initActionData['name'] . ": ");
        if ($this->isIntegrationInDatabase($actionClass)) {
            $this->syncModel($initActionData);
            return;
        }
        $this->createIntegration($initActionData);
    }


    /**
     * @param string $actionClass
     * @return bool
     */
    protected function isIntegrationInDatabase(string $actionClass): bool
    {
        if ($this->actionsInDatabase->has($actionClass)) {
            $this->info("already in database");
            $this->actionsInDatabase->forget($actionClass);
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
        $action = Action::where('type', $params['type'])->first();
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
     * @return void
     */
    protected function removeOldRecords(): void
    {
        if ($oldRecords = count($this->actionsInDatabase)) {
            $this->actionsInDatabase->map->delete();
            $this->info("Removed {$oldRecords} old records from database");
        }
    }
}
