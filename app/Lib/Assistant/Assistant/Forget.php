<?php

namespace App\Lib\Assistant\Assistant;

use App\Lib\Assistant\Assistant;
use App\Lib\Exceptions\ConnectionException;
use App\Models\Resource;
use JsonException;
use stdClass;

class Forget
{
    protected Assistant $assistant;

    protected stdClass $point;

    public function __construct(Assistant $assistant)
    {
        $this->assistant = $assistant;
    }

    /**
     * @return void
     * @throws ConnectionException
     * @throws JsonException
     */
    public function execute(): void
    {
        $this->assistant->query()->assignCategory();
        $this->getPoint();
        $this->deleteResource();
        $this->deleteFromDatabase();
        $this->assistant->setResponse('Zapomniano.');
    }

    /**
     * @return void
     * @throws ConnectionException
     * @throws JsonException
     */
    protected function getPoint(): void
    {
        $embedding = $this->assistant->api->embeddings()->create($this->assistant->query);
        $response = $this->assistant->vectorDatabase->points()->searchPoints($embedding, $this->assistant->category, 1);
        $this->point = collect($response)->first();
    }

    /**
     * @return void
     */
    protected function deleteResource(): void
    {
        Resource::where('uuid', $this->point->id)->delete();
    }

    /**
     * @return void
     * @throws ConnectionException
     * @throws JsonException
     */
    protected function deleteFromDatabase(): void
    {
        $this->assistant->vectorDatabase->points()->deletePoint($this->point->id);
    }
}
