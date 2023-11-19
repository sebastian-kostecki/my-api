<?php

namespace App\Lib\Apis\OpenAI\Assistant;

use App\Lib\Apis\OpenAI\Assistant as Assistant;

class Thread
{
    private Assistant $assistant;

    public function __construct($assistant)
    {
        $this->assistant = $assistant;
    }

    public function create(array $messages = [])
    {
        $url = $this->assistant->api::BASEURL . 'threads';
        $params['messages'] = $messages;

        $response = $this->assistant->request->post($url, $params);
        return json_decode($response, true);
    }

    public function retrieve(string $id)
    {
        $url = $this->assistant->api::BASEURL . 'threads/' . $id;
        $response = $this->assistant->request->get($url);
        return json_decode($response, true);
    }
}
