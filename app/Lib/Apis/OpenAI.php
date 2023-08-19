<?php

namespace App\Lib\Apis;

use App\Lib\Apis\OpenAI\Request;

class OpenAI
{
    protected Request $request;

    public function __construct()
    {
        $this->request = new Request();
    }

    /**
     * @param array $params
     * @return string
     */
    public function chat(array $params): string
    {
        $this->request->setModel($params['model']);
        $this->request->setTemperature($params['temperature']);
        $this->request->setMessages($params['messages']);
        $this->request->chat();
        return $this->request->getContent();
    }
}
