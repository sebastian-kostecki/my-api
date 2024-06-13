<?php

namespace App\Console\Commands\Qdrant;

use App\Lib\Apis\Qdrant;
use App\Lib\Exceptions\ConnectionException;
use Illuminate\Console\Command;
use Illuminate\Support\Arr;
use JsonException;

class GetCollection extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'qdrant:get-collection {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'List Collections';

    /**
     * Execute the console command.
     * @param Qdrant $api
     * @throws ConnectionException
     * @throws JsonException
     */
    public function handle(Qdrant $api): void
    {
        $name = $this->argument('name');
        $result = $api->getCollection($name);
        $data = Arr::dot($result);

        foreach ($data as $key => $value) if (!empty($value)){
            $this->line($key . ': ' . $value);
        }
    }
}
