<?php

namespace App\Lib\Apis\OpenAI;

use App\Lib\Apis\OpenAI;
use Illuminate\Http\Client\PendingRequest;
use JsonException;
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
        $this->url = $api::BASEURL.self::BASEURL;
        $this->request = $api->request;
    }

    /**
     * @throws JsonException
     */
    public function create(string $input): array
    {
        $params = [
            'model' => self::MODEL,
            'input' => $input,
        ];

        $response = $this->request->post($this->url, $params);
        $result = json_decode($response->body(), false, 512, JSON_THROW_ON_ERROR);

        return $result->data[0]->embedding;
    }
}
