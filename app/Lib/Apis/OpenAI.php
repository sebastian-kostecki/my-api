<?php

namespace App\Lib\Apis;

use App\Lib\Apis\OpenAI\Chat;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;

class OpenAI
{
    public const BASEURL = 'https://api.openai.com/v1/';

    public PendingRequest $request;
    protected Chat $chat;

    public function __construct()
    {
        $this->request = Http::withToken(config('openai.api_key'));
        $this->chat = new Chat($this);
    }

    /**
     * @return Chat
     */
    public function chat(): Chat
    {
        if (!$this->chat) {
            return new Chat($this);
        }
        return $this->chat;
    }

    /**
     * @param array $params
     * @return string
     */
//    public function chat(array $params): string
//    {
//        $this->request->setModel($params['model']);
//        if (isset($params['temperature'])) {
//            $this->request->setTemperature($params['temperature']);
//        }
//        $this->request->setMessages($params['messages']);
//        $this->request->chat();
//        return $this->request->getContent();
//    }

    /**
     * @param string $input
     * @return array
     */
    public function createEmbedding(string $input): array
    {
        $this->request->setModel('text-embedding-ada-002');
        $this->request->setInput($input);
        $this->request->embedding();
        return $this->request->getEmbedding();
    }




}
