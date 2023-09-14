<?php

namespace App\Lib\Connections\Qdrant;

use App\Lib\Connections\Qdrant;
use App\Lib\Exceptions\ConnectionException;
use JsonException;
use stdClass;

class Points
{
    protected Qdrant $connection;

    public function __construct(Qdrant $qdrant)
    {
        $this->connection = $qdrant;
    }

    /**
     * @param array $ids
     * @param bool $withPayload
     * @param bool $withVector
     * @return array
     * @throws ConnectionException
     * @throws JsonException
     */
    public function getPoints(array $ids, bool $withPayload = false, bool $withVector = false): array
    {
        $endpoint = "collections/{$this->connection->databaseName}/points";
        $params = [
            'ids' => $ids,
            'with_payload' => $withPayload,
            'with_vector' => $withVector
        ];
        $response = $this->connection->api->call('POST', $endpoint, $params);
        return $response->result;
    }
}
