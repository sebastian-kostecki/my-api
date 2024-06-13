<?php

namespace App\Lib\Apis;

use App\Lib\Apis\Qdrant\Request;
use App\Lib\Exceptions\ConnectionException;
use JsonException;

class Qdrant
{
    private Request $request;

    public function __construct()
    {
        $this->request = new Request();
    }

    /**
     * @return array<array{
     *   name: string
     * }>
     * @throws ConnectionException
     * @throws JsonException
     */
    public function listCollections(): array
    {
        $response = $this->request->call('GET', '/collections');
        return $response['result']['collections'];
    }

    /**
     * @param string $name
     * @return array
     * @throws ConnectionException
     * @throws JsonException
     */
    public function getCollection(string $name): array
    {
        $response = $this->request->call('GET', "/collections/{$name}");
        return $response['result'];
    }

    /**
     * @param string $name
     * @param int $size
     * @param string $distance
     * @return void
     * @throws ConnectionException
     * @throws JsonException
     */
    public function createCollection(string $name, int $size, string $distance = "Cosine"): void
    {
        $params = [
            'vectors' => [
                'size' => $size,
                'distance' => $distance
            ]
        ];
        $this->request->call('PUT', "/collections/{$name}", $params);
    }
}
