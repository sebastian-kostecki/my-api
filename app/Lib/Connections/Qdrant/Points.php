<?php

namespace App\Lib\Connections\Qdrant;

use App\Lib\Connections\Qdrant;
use App\Lib\Exceptions\ConnectionException;
use JsonException;

class Points
{
    protected Qdrant $connection;

    public function __construct(Qdrant $qdrant)
    {
        $this->connection = $qdrant;
    }

    /**
     * @throws ConnectionException
     * @throws JsonException
     */
    public function getPoints(array $ids, bool $withPayload = false, bool $withVector = false): array
    {
        $endpoint = "collections/{$this->connection->databaseName}/points";
        $params = [
            'ids' => $ids,
            'with_payload' => $withPayload,
            'with_vector' => $withVector,
        ];

        return $this->connection->api->call('POST', $endpoint, $params)->result;
    }

    /**
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
                            'value' => $category,
                        ],
                    ],
                ],
            ],
            'limit' => $limit,
            'with_payload' => true,
        ];

        return $this->connection->api->call('POST', $endpoint, $params)->result;
    }

    /**
     * @param  array  $points  {
     *                         id: int, vector: array, payload: array
     *                         }@param
     *
     * @throws ConnectionException
     * @throws JsonException
     */
    public function upsertPoints(array $points): void
    {
        $endpoint = "collections/{$this->connection->databaseName}/points";
        $params = [
            'points' => $points,
        ];
        $this->connection->api->call('PUT', $endpoint, $params);
    }

    /**
     * @param  array  $point  {
     *                        id: int, vector: array, payload: array
     *                        }@param
     *
     * @throws ConnectionException
     * @throws JsonException
     */
    public function upsertPoint(array $point): void
    {
        $endpoint = "collections/{$this->connection->databaseName}/points";
        $params = [
            'points' => [$point],
        ];
        $this->connection->api->call('PUT', $endpoint, $params);
    }

    /**
     * @throws ConnectionException
     * @throws JsonException
     */
    public function deletePoint(string $id): void
    {
        $endpoint = "collections/{$this->connection->databaseName}/points/delete";
        $params = [
            'points' => [$id],
        ];
        $this->connection->api->call('POST', $endpoint, $params);
    }
}
