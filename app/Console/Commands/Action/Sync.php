<?php

namespace App\Console\Commands\Action;

use App\Lib\Interfaces\ActionInterface;
use App\Models\Action;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Support\Collection;

class Sync extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'action:sync';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Synchronize actions';

    private Collection $actions;

    private EloquentCollection $actionsInDatabase;

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $this->getActions();
        $this->getActionsInDatabase();

        $this->actions->each(function ($actionClass) {
            /** @var class-string<ActionInterface> $actionClass */
            $this->output->write($actionClass::getName().': ');
            $this->createOrIgnore($actionClass);
        });

        $this->removeOldRecords();
        $this->info('Finished synchronizing actions.');
    }

    private function getActions(): void
    {
        $this->info('Scanning files...');
        $this->actions = Action::scan();
        $this->info('Found '.$this->actions->count().' actions');
    }

    private function getActionsInDatabase(): void
    {
        $this->actionsInDatabase = Action::all()->keyBy('type');
    }

    private function createOrIgnore(string $actionClass): void
    {
        if (! $this->isActionInDatabase($actionClass)) {
            $this->createAssistant($actionClass);
        }
    }

    private function isActionInDatabase(string $actionClass): bool
    {
        if ($this->actionsInDatabase->has($actionClass)) {
            $this->info('already in database');
            $this->actionsInDatabase->forget($actionClass);

            return true;
        }

        return false;
    }

    private function createAssistant(string $actionClass): void
    {
        /** @var ActionInterface $actionClass */
        Action::create([
            'type' => $actionClass,
            'name' => $actionClass::getName(),
            'icon' => $actionClass::getIcon(),
            'shortcut' => $actionClass::getShortcut(),
            'config' => $actionClass::getConfig(),
        ]);

        $this->info('added to database');
    }

    private function removeOldRecords(): void
    {
        if ($oldRecords = count($this->actionsInDatabase)) {
            $this->actionsInDatabase->map->delete();
            $this->info("Removed {$oldRecords} old records from database");
        }
    }
}
