<?php

namespace App\Console\Commands\Model;

use App\Models\Model;
use Illuminate\Console\Command;

class DeleteModel extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'model:delete';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete AI model';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $databaseModels = $this->getDatabaseModels();

        $modelName = $this->choice(
            'Which model you want to delete?',
            $databaseModels
        );

        Model::where('name', $modelName)->delete();
        $this->info("Model {$modelName} has been deleted");
    }

    private function getDatabaseModels(): array
    {
        return Model::all()->map(function (Model $model) {
            return $model->name;
        })->values()->toArray();
    }
}
