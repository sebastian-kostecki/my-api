<?php

namespace App\Lib\Assistant;

use OpenAI\Laravel\Facades\OpenAI as Client;

class Assistant
{
    /**
     * @param string $prompt
     * @return string
     */
    public function describeIntention(string $prompt): string
    {
        $content = "Describe my intention from message below with JSON";
        $content .= "Focus on the beginning of it. Always return JSON and nothing more. \n";
        $content .= "types: action|query|memory\n";
        $content .= "Example: Napisz wiadomość. {\"type\": \"action\"}";
        $content .= "Zapisz notatkę {\"type\": \"action\"}";
        $content .= "Are you Ed? {\"type\": \"query\"}";
        $content .= "Remember that I'm programmer. {\"type\": \"memory\"}";
        $content .= "Dodaj task o fixie do notifications. {\"type\": \"action\"}";
        $content .= "###message\n{$prompt}";

        $response = Client::chat()->create([
            'model' => 'gpt-3.5-turbo',
            'temperature' => 0,
            'messages' => [
                [
                    'role' => 'user',
                    'content' => $content
                ],
            ],
        ]);
        return $response->choices[0]->message->content;
    }
}
