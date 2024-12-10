<?php

namespace App\Console\Commands\Assistants;

use App\Lib\Interfaces\AssistantInterface;
use App\Models\Assistant;
use App\Models\Model;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Support\Collection;

class AssistantSync extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'assistant:sync';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Synchronizing assistants';

    private Collection $assistants;

    private EloquentCollection $assistantsInDatabase;

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $this->getAssistants();
        $this->getAssistantsInDatabase();

        $this->assistants->each(function ($assistantClass) {
            /** @var class-string<AssistantInterface> $assistantClass */
            $this->output->write($assistantClass::getName().' ('.$assistantClass::getDescription().'): ');
            $this->createOrIgnore($assistantClass);
        });

        $this->removeOldRecords();
        $this->info('Finished synchronizing actions.');
    }

    private function getAssistants(): void
    {
        $this->info('Scanning files...');
        $this->assistants = Assistant::scan();
        $this->info('Found '.$this->assistants->count().' actions');
    }

    private function getAssistantsInDatabase(): void
    {
        $this->assistantsInDatabase = Assistant::all()->keyBy('type');
    }

    private function createOrIgnore(string $assistantClass): void
    {
        if (! $this->isAssistantInDatabase($assistantClass)) {
            $this->createAssistant($assistantClass);
        }
    }

    private function isAssistantInDatabase(string $assistantClass): bool
    {
        if ($this->assistantsInDatabase->has($assistantClass)) {
            $this->info('already in database');
            $this->assistantsInDatabase->forget($assistantClass);

            return true;
        }

        return false;
    }

    private function createAssistant(string $assistantClass): void
    {
        $firstModel = Model::first();

        /** @var AssistantInterface $assistantClass */
        Assistant::create([
            'model_id' => $firstModel->id,
            'type' => $assistantClass,
            'name' => $assistantClass::getName(),
            'description' => $assistantClass::getDescription(),
            'instructions' => $assistantClass::getInstructions(),
        ]);

        $this->info('added to database');
    }

    private function removeOldRecords(): void
    {
        if ($oldRecords = count($this->assistantsInDatabase)) {
            $this->assistantsInDatabase->map->delete();
            $this->info("Removed {$oldRecords} old records from database");
        }
    }
}
