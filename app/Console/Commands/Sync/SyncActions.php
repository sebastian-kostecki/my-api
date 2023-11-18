<?php

namespace App\Console\Commands\Sync;

use App\Lib\Interfaces\AssistantInterface;
use App\Models\Action;
use App\Models\Assistant;
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
        $initActionData = array_map(static function ($value) {
            return $value['default'];
        }, $actionClass::getConfigFields());

        $this->output->write($initActionData['name'] . ": ");
        if ($this->isIntegrationInDatabase($actionClass)) {
            $this->syncModel($actionClass, $initActionData);
            return;
        }
        $this->createIntegration($actionClass, $initActionData);
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
     * @param string $actionClass
     * @param array $params
     * @return void
     */
    protected function syncModel(string $actionClass, array $params): void
    {
        $action = Action::where('type', $actionClass)->first();
        try {
            $action->model;
        } catch (\Throwable $throwable) {
            $action->model = $params['model'];
            $action->save();
        }
    }

    /**
     * @param string $actionClass
     * @param array $params
     * @return void
     */
    protected function createIntegration(string $actionClass, array $params): void
    {
        $newAction = Action::create([
            'type' => $actionClass,
            'name' => $params['name'],
            'icon' => $params['icon'] ?? null,
            'shortcut' => $params['shortcut'] ?? null,
            'instructions' => $params['instructions'] ?? null,
            'enabled' => true,
            'hidden' => $params['hidden'] ?? false
        ]);

        if ($this->isAssistant($actionClass)) {
            $assistant = new Assistant();
            $assistant->create($params);
            $newAction->assistant()->save($assistant);
            $newAction->save();
        }

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

    /**
     * @param string $actionClass
     * @return bool
     */
    protected function isAssistant(string $actionClass): bool
    {
        return in_array(AssistantInterface::class, class_implements($actionClass), true);
    }
}
