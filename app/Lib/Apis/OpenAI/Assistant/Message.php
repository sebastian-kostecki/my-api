<?php

namespace App\Lib\Apis\OpenAI\Assistant;

class Message
{
    private \App\Lib\Apis\OpenAI\Assistant $assistant;

    public function __construct($assistant)
    {
        $this->assistant = $assistant;
    }

    public function list(string $threadId)
    {
        $url = $this->assistant->api::BASEURL.'threads/'.$threadId.'/messages';
        $response = $this->assistant->request->get($url);

        return json_decode($response, true);
    }

    public function create(string $threadId, string $query)
    {
        $url = $this->assistant->api::BASEURL.'threads/'.$threadId.'/messages';
        $params = [
            'role' => 'user',
            'content' => $query,
        ];

        $response = $this->assistant->request->post($url, $params);

        return json_decode($response, true);
    }
}
