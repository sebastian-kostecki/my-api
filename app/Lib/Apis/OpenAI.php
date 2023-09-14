<?php

namespace App\Lib\Apis;

use App\Lib\Apis\OpenAI\Chat;
use App\Lib\Apis\OpenAI\Embedding;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;

class OpenAI
{
    public const BASEURL = 'https://api.openai.com/v1/';

    public PendingRequest $request;
    protected Chat $chat;
    protected Embedding $embedding;

    public function __construct()
    {
        $this->request = Http::withToken(config('openai.api_key'));
        $this->chat = new Chat($this);
        $this->embedding = new Embedding($this);
    }

    /**
     * @return Chat
     */
    public function chat(): Chat
    {
        if ($this->chat) {
            return $this->chat;
        }
        return new Chat($this);
    }

    /**
     * @return Embedding
     */
    public function embeddings(): Embedding
    {
        if ($this->embedding) {
            return $this->embedding;
        }
        return new Embedding($this);
    }
}
