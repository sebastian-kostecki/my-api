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
}
