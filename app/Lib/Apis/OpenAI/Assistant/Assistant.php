<?php

namespace App\Lib\Apis\OpenAI\Assistant;

class Assistant
{
    private \App\Lib\Apis\OpenAI\Assistant $assistant;

    public function __construct($assistant)
    {
        $this->assistant = $assistant;
    }

    /**
     * @return array
     */
    public function list(): array
    {
        $url = $this->assistant->api::BASEURL . 'assistants';
        $result = $this->assistant->request->get($url);
        return json_decode($result->body(), true);
    }
}
