<?php

namespace App\Lib\Apis;

use App\Lib\Apis\OpenAI\Assistant;
use App\Lib\Apis\OpenAI\Chat;
use App\Lib\Apis\OpenAI\Completion;
use App\Lib\Apis\OpenAI\Embedding;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;
use stdClass;

class OpenAI
{
    public const BASEURL = 'https://api.openai.com/v1/';

    public PendingRequest $request;

    public function __construct()
    {
        $this->request = Http::withToken(config('services.open_ai.api_key'));
    }

    /**
     * @return OpenAI
     */
    public static function factory(): OpenAI
    {
        return new self();
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

    /**
     * @return Completion
     */
    public function completion(): Completion
    {
        return new Completion($this);
    }
}
