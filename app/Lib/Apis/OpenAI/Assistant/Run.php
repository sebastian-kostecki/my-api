<?php

namespace App\Lib\Apis\OpenAI\Assistant;

class Run
{
    private \App\Lib\Apis\OpenAI\Assistant $assistant;

    public function __construct($assistant)
    {
        $this->assistant = $assistant;
    }

    public function create(string $threadId, string $assistantId): array
    {
        $url = $this->assistant->api::BASEURL.'threads/'.$threadId.'/runs';
        $params = [
            'assistant_id' => $assistantId,
        ];

        $response = $this->assistant->request->post($url, $params);

        return json_decode($response, true);
    }

    public function retrieve(string $threadId, string $runId): array
    {
        $url = $this->assistant->api::BASEURL.'threads/'.$threadId.'/runs/'.$runId;
        $response = $this->assistant->request->get($url);

        return json_decode($response, true);
    }
}
