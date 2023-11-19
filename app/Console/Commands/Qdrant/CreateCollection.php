<?php

namespace App\Console\Commands\Qdrant;

use App\Lib\Connections\Qdrant;
use Illuminate\Console\Command;

class CreateCollection extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'qdrant:create-collection {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create new collection';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $collectionName = $this->argument('name');
        $client = new Qdrant($collectionName);
        $client->collections()->create(1536);
        $this->info('Created collection');
    }
}
