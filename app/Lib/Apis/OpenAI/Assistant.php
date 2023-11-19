<?php

namespace App\Lib\Apis\OpenAI;

use App\Lib\Apis\OpenAI;
use Illuminate\Http\Client\PendingRequest;

class Assistant
{
    public OpenAI $api;
    public PendingRequest $request;

    /**
     * @param OpenAI $api
     */
    public function __construct(OpenAI $api)
    {
        $this->api = $api;
        $this->request = $api->request->withHeaders([
            'Content-Type' => 'application/json',
            'OpenAI-Beta' => 'assistants=v1'
        ]);
    }

    /**
     * @return Assistant\Assistant
     */
    public function assistant(): Assistant\Assistant
    {
        return new OpenAI\Assistant\Assistant($this);
    }

    public function thread()
    {
        return new OpenAI\Assistant\Thread($this);
    }

    public function message()
    {
        return new OpenAI\Assistant\Message($this);
    }

    public function run()
    {
        return new OpenAI\Assistant\Run($this);
    }
}
