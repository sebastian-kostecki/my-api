<?php

namespace App\Lib\Apis\OpenAI;

use App\Lib\Apis\OpenAI;
use Illuminate\Http\Client\PendingRequest;
use stdClass;

class Embedding
{
    private string $url;
    private PendingRequest $request;
    private stdClass $response;


    protected const BASEURL = 'embeddings';
    protected const MODEL = 'text-embedding-ada-002';

    public function __construct(OpenAI $api)
    {
        $this->url = $api::BASEURL . self::BASEURL;
        $this->request = $api->request;
    }

    public function create(string $input)
    {
        $params = [
            'model' => self::MODEL,
            'input' => $input
        ];

        $result = $this->request->post($this->url, $params);
        $this->response = json_decode($result->body(), false, 512, JSON_THROW_ON_ERROR);
    }

    public function getEmbedding()
    {
        return $this->response->data[0]->embedding;
    }
}
