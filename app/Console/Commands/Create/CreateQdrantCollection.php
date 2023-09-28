<?php

namespace App\Console\Commands\Create;

use App\Lib\Connections\Qdrant;
use Illuminate\Console\Command;

class CreateQdrantCollection extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'create:qdrant-collection {name}';

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
        $collectionName = $this->argument('name');
        $client = new Qdrant($collectionName);
        $client->collections()->create(1536);
        $this->info('Created collection');
    }
}
