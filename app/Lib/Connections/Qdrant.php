<?php

namespace App\Lib\Connections;

use App\Lib\Apis\Qdrant as Api;
use App\Lib\Exceptions\ConnectionException;
use JsonException;

class Qdrant
{
    public Api $api;
    public string $databaseName;

    public function __construct()
    {
        $this->api = new Api();
        $this->databaseName = config('services.qdrant.database_name');
    }

    /**
     * @return Qdrant
     */
    public static function factory(): Qdrant
    {
        return new self();
    }

    /**
     * @param array $embeddings
     * @param array $payload
     * @return void
     * @throws ConnectionException
     * @throws JsonException
     */
    public function upsertPoint(array $embeddings, array $payload): void
    {
        $this->api->upsertPoint($this->databaseName, $embeddings, $payload);
    }
}
