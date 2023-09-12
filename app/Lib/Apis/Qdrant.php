<?php

namespace App\Lib\Apis;

use App\Lib\Curl;
use App\Lib\Exceptions\ConnectionException;
use JsonException;
use stdClass;

class Qdrant
{
    protected Curl $curl;

    public function __construct()
    {
        $this->curl = new Curl();
    }

    /**
     * @param string $method
     * @param string $endpoint
     * @param array $params
     * @return stdClass
     * @throws ConnectionException
     * @throws JsonException
     */
    public function call(string $method, string $endpoint, array $params = []): stdClass
    {
        $url = 'qdrant:6333/' . $endpoint;
        $response = $this->curl->call($method, $url, $params);
        return json_decode($response, false, 512, JSON_THROW_ON_ERROR);
    }
}
