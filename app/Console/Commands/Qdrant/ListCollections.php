<?php

namespace App\Console\Commands\Qdrant;

use App\Lib\Apis\Qdrant;
use App\Lib\Exceptions\ConnectionException;
use Illuminate\Console\Command;
use JsonException;

class ListCollections extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'qdrant:list-collections';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'List Collections';

    /**
     * Execute the console command.
     *
     * @throws ConnectionException
     * @throws JsonException
     */
    public function handle(Qdrant $api): void
    {
        $result = $api->listCollections();
        $collections = collect($result)->map(function ($item, $key) {
            return [
                'No' => ++$key,
                'Name' => $item['name'],
            ];
        });
        $this->table(['No', 'Name'], $collections);
    }
}
