<?php

namespace App\Console\Commands\Qdrant;

use App\Lib\Connections\Qdrant;
use Illuminate\Console\Command;

class ListCollection extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'qdrant:collection {collection?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'List of qdrant collections';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $collection = $this->argument('collection');

        if ($collection) {
            $result = Qdrant::factory()->collections()->getInfo($collection);
            dd($result);
        }

        $result = Qdrant::factory()->collections()->list();
        $this->info('List of collections:');
        foreach ($result as $item) {
            $this->line($item->name);
        }
    }
}
