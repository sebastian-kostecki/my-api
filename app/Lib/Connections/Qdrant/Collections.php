<?php

namespace App\Lib\Connections\Qdrant;

use App\Lib\Connections\Qdrant;
use App\Lib\Exceptions\ConnectionException;
use JsonException;
use stdClass;

class Collections
{
    protected Qdrant $connection;

    public function __construct(Qdrant $qdrant)
    {
        $this->connection = $qdrant;
    }

    /**
     * @return array
     * @throws ConnectionException
     * @throws JsonException
     */
    public function list(): array
    {
        $endpoint = "collections";
        $response = $this->connection->api->call('GET', $endpoint);
        return $response->result->collections;
    }

    /**
     * @param string $name
     * @return mixed
     * @throws ConnectionException
     * @throws JsonException
     */
    public function getInfo(string $name): stdClass
    {
        $endpoint = "collections/{$name}";
        return $this->connection->api->call('GET', $endpoint)->result;
    }

    /**
     * @param int $size
     * @param string $distance
     * @return void
     * @throws ConnectionException
     * @throws JsonException
     */
    public function create(int $size, string $distance = "Cosine"): void
    {
        $endpoint = "collections/{$this->connection->databaseName}";
        $params = [
            'vectors' => [
                'size' => $size,
                'distance' => $distance
            ]
        ];
        $this->connection->api->call('PUT', $endpoint, $params);
    }
}
