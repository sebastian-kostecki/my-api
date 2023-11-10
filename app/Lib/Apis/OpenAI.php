<?php

namespace App\Lib\Apis;

use App\Lib\Apis\OpenAI\Assistant;
use App\Lib\Apis\OpenAI\Chat;
use App\Lib\Apis\OpenAI\Embedding;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;

class OpenAI
{
    public const BASEURL = 'https://api.openai.com/v1/';

    public PendingRequest $request;

    public function __construct()
    {
        $this->request = Http::withToken(config('openai.api_key'));
    }

    /**
     * @return Chat
     */
    public function chat(): Chat
    {
        return new Chat($this);
    }

    /**
     * @return Embedding
     */
    public function embeddings(): Embedding
    {
        return new Embedding($this);
    }

    /**
     * @return Assistant
     */
    public function assistant(): Assistant
    {
        return new Assistant($this);
    }
}
