<?php

namespace App\Console\Commands\Qdrant;

use App\Lib\Apis\Qdrant;
use App\Lib\Exceptions\ConnectionException;
use Illuminate\Console\Command;
use JsonException;

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
     * @param Qdrant $api
     * @throws ConnectionException
     * @throws JsonException
     */
    public function handle(Qdrant $api): void
    {
        $collectionName = $this->argument('name');
        $api->createCollection($collectionName, 1536);
        $this->info('Created collection');
    }
}
