<?php

namespace App\Console\Commands\Model;

use App\Lib\Connections\ArtificialIntelligence\OpenAI;
use App\Lib\Interfaces\Connections\ArtificialIntelligenceInterface;
use App\Models\Model;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;

class AddModel extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'model:add';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add AI model';

    private Collection $models;

    private array $databaseModels;

    private array $artificialIntelligenceClasses = [
        OpenAI::class
    ];

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $this->getDatabaseModels();
        $this->getModels();

        $modelName = $this->choice(
            'Which model you want to add?',
            $this->models->map(function (array $model) {
                return $model['name'];
            })->toArray()
        );

        $this->createModel($modelName);
        $this->info("Model {$modelName} has been added");
    }

    /**
     * @return void
     */
    private function getDatabaseModels(): void
    {
        $this->databaseModels = Model::all()->map(function (Model $model) {
            return $model->name;
        })->toArray();
    }

    /**
     * @return void
     */
    private function getModels(): void
    {
        $models = collect();
        foreach ($this->artificialIntelligenceClasses as $class) {
            /** @var class-string<ArtificialIntelligenceInterface> $class */
            $classModels = $class::factory()->getModels();
            $selectedModels = collect($classModels)->filter(function ($model) {
                if (in_array($model['name'], $this->databaseModels, true)) {
                    return false;
                }
                return true;
            })->values()->toArray();
            $models = $models->merge($selectedModels);
        }
        $this->models = $models;
    }

    /**
     * @param string $modelName
     * @return void
     */
    private function createModel(string $modelName): void
    {
        $selectedModel = $this->models->where('name', $modelName)->first();

        Model::create([
            'name' => $modelName,
            'type' => $selectedModel['type']
        ]);
    }
}
