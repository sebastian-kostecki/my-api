<?php

namespace App\Lib\Apis;

use App\Lib\Apis\Qdrant\Request;
use App\Lib\Exceptions\ConnectionException;
use Illuminate\Support\Str;
use JsonException;

class Qdrant
{
    private Request $request;

    public function __construct()
    {
        $this->request = new Request;
    }

    /**
     * @return array<array{
     *   name: string
     * }>
     *
     * @throws ConnectionException
     * @throws JsonException
     */
    public function listCollections(): array
    {
        $response = $this->request->call('GET', '/collections');

        return $response['result']['collections'];
    }

    /**
     * @throws ConnectionException
     * @throws JsonException
     */
    public function getCollection(string $databaseName): array
    {
        $response = $this->request->call('GET', "/collections/{$databaseName}");

        return $response['result'];
    }

    /**
     * @throws ConnectionException
     * @throws JsonException
     */
    public function createCollection(string $databaseName, int $size, string $distance = 'Cosine'): void
    {
        $params = [
            'vectors' => [
                'size' => $size,
                'distance' => $distance,
            ],
        ];
        $this->request->call('PUT', "/collections/{$databaseName}", $params);
    }

    /**
     * @throws ConnectionException
     * @throws JsonException
     */
    public function upsertPoint(string $databaseName, array $embeddings, array $payload): void
    {
        $params = [
            'points' => [
                [
                    'id' => Str::uuid()->toString(),
                    'vector' => $embeddings,
                    'payload' => $payload,
                ],
            ],
        ];
        $this->request->call('PUT', "/collections/{$databaseName}/points", $params);
    }
}
