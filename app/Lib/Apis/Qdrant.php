<?php

namespace App\Lib\Apis;

use App\Lib\Curl;
use App\Lib\Exceptions\ConnectionException;
use Exception;
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
     * @throws Exception
     */
    public function call(string $method, string $endpoint, array $params = []): stdClass
    {
        $url = 'qdrant:6333/' . $endpoint;
        $options = [
            CURLOPT_HTTPHEADER => [
                "Content-Type: application/json"
            ],
        ];
        $response = $this->curl->call($method, $url, json_encode($params, JSON_THROW_ON_ERROR), $options);
        $result = json_decode($response, false, 512, JSON_THROW_ON_ERROR);

        if (isset($result->status->error) && $result->status->error) {
            throw new Exception("Qdrant Error:" . $result->status->error);
        }
        return $result;
    }
}
