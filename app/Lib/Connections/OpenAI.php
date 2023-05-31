<?php

namespace App\Lib\Connections;

use OpenAI\Laravel\Facades\OpenAI as Client;

class OpenAI
{
    public function chat(string $prompt): string
    {
        $response = Client::chat()->create([
            'model' => 'gpt-3.5-turbo',
            'messages' => [
                ['role' => 'user', 'content' => $prompt],
            ],
        ]);
        return $response->choices[0]->message->content;
    }

    public function createEmbedding(string $input)
    {
        $response = Client::embeddings()->create([
            'model' => 'text-embedding-ada-002',
            'input' => $input,
        ]);
        return $response->toArray()['data'][0]['embedding'];
    }

    public function chatConversation(string $prompt)
    {
        return Client::chat()->createStreamed([
            'model' => 'gpt-3.5-turbo',
            'messages' => [
                ['role' => 'user', 'content' => $prompt],
            ],
        ]);
    }
}
