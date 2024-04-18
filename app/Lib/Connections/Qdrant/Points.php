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
        return $this->connection->api->call('POST', $endpoint, $params)->result;
    }

    /**
     * @param array $embedding
     * @param string $category
     * @param int $limit
     * @return array
     * @throws ConnectionException
     * @throws JsonException
     */
    public function searchPoints(array $embedding, string $category, int $limit = 3): array
    {
        $endpoint = "collections/{$this->connection->databaseName}/points/search";
        $params = [
            'vector' => $embedding,
            'filter' => [
                'must' => [
                    [
                        'key' => 'category',
                        'match' => [
                            'value' => $category
                        ]
                    ]
                ]
            ],
            'limit' => $limit,
            'with_payload' => true
        ];
        return $this->connection->api->call('POST', $endpoint, $params)->result;
    }

    /**
     * @param array $points {
     *     id: int, vector: array, payload: array
     * }@param
     * @return void
     * @throws ConnectionException
     * @throws JsonException
     */
    public function upsertPoints(array $points): void
    {
        $endpoint = "collections/{$this->connection->databaseName}/points";
        $params = [
            'points' => $points
        ];
        $this->connection->api->call('PUT', $endpoint, $params);
    }

    /**
     * @param array $point {
     *     id: int, vector: array, payload: array
     * }@param
     * @return void
     * @throws ConnectionException
     * @throws JsonException
     */
    public function upsertPoint(array $point): void
    {
        $endpoint = "collections/{$this->connection->databaseName}/points";
        $params = [
            'points' => [$point]
        ];
        $this->connection->api->call('PUT', $endpoint, $params);
    }

    /**
     * @param string $id
     * @return void
     * @throws ConnectionException
     * @throws JsonException
     */
    public function deletePoint(string $id): void
    {
        $endpoint = "collections/{$this->connection->databaseName}/points/delete";
        $params = [
            'points' => [$id]
        ];
        $this->connection->api->call('POST', $endpoint, $params);
    }





    public function search(string $databaseName, array $embedding): array
    {
        $endpoint = "collections/{$databaseName}/points/search";
        $params = [
            'vector' => $embedding,
            'limit' => 2,
            'with_payload' => true
        ];
        return $this->connection->api->call('POST', $endpoint, $params)->result;
    }

    public function create(string $databaseName, array $point): void
    {
        $endpoint = "collections/{$databaseName}/points";
        $params = [
            'points' => [$point]
        ];
        $this->connection->api->call('PUT', $endpoint, $params);
    }
}
