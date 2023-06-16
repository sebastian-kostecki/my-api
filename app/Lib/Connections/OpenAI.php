<?php

namespace App\Lib\Connections;

use OpenAI\Laravel\Facades\OpenAI as Client;
use OpenAI\Responses\Chat\CreateResponse;

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

    public function describeIntention(string $prompt)
    {
        $response = Client::chat()->create([
            'model' => 'gpt-3.5-turbo',
            'temperature' => 0,
            'messages' => [
                [
                    'role' => 'user',
                    'content' => "Describe my intention from message below with JSON. Focus on the beginning of it. Always return JSON and nothing more. \ntypes: action|query|memory\nnothing more. types: actionlquerylmemory" .
                        "Example: Napisz wiadomość. {\"type\": \"action\"} Zapisz notatkę {\"type\": \"action\"} Are you Ed? {\"type\": \"query\"} Remember that I'm programmer. {\"type\": \"memory\"} Dodaj task o fixie do notifications. {\"type\": \"action\"}###message\n{$prompt}"
                ],
            ],
        ]);
        return $response->choices[0]->message->content;
    }


    /**
     * @param string $text
     * @return string
     */
    public function getJson(string $text): string
    {
        $response = Client::chat()->create([
            'model' => 'gpt-3.5-turbo',
            'temperature' => 0,
            'messages' => [
                [
                    'role' => 'user',
                    'content' => $text
                ],
            ],
        ]);
        return $response->choices[0]->message->content;
    }

    /**
     * @param string $text
     * @return string
     */
    public function translateToPolish(string $text): string
    {
        $response = Client::chat()->create([
            'model' => 'gpt-3.5-turbo',
            'temperature' => 0.8,
            'messages' => [
                [
                    'role' => 'system',
                    'content' => 'Translate the following text into Polish, observing all grammatical, stylistic and orthographic regularities appropriate to the type of text:'
                ],
                [
                    'role' => 'user',
                    'content' => $text
                ],
            ],
        ]);
        return $response->choices[0]->message->content;
    }

    public function generateTags(string $text)
    {
        $response = Client::chat()->create([
            'model' => 'gpt-3.5-turbo',
            'temperature' => 0.1,
            'messages' => [
                [
                    'role' => 'system',
                    'content' => "Based on the text below, generate a list of tags to describe the text with JSON. Always return JSON and nothing more. Example: {\"tags\" [\"HTTP\",\"status code\",\"422\",\"Unprocessable Content\",\"server\", \"content type\",\"request entity\",\"syntax\",\"instructions\"]}"
                ],
                [
                    'role' => 'user',
                    'content' => $text
                ],
            ],
        ]);
        return $response->choices[0]->message->content;
    }
}
