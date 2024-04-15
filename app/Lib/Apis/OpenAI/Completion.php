<?php

namespace App\Lib\Apis\OpenAI;

use App\Lib\Apis\OpenAI;
use stdClass;

class Completion
{
    private OpenAI $connection;

    public function __construct(OpenAI $openAI)
    {
        $this->connection = $openAI;
    }

    public function request(string $model, array $messages, array $params = []): string
    {
        $url = 'https://api.openai.com/v1/chat/completions';
        $data = [
            'model' => $model,
            'messages' => $messages
        ];

        if (!empty($params)) {
            $data = array_merge($data, $params);
        }

        $result = $this->connection->request->post($url, $data);
        $response = json_decode($result->body(), false, 512, JSON_THROW_ON_ERROR);
        return $this->getAssistantResponse($response);
    }

    /**
     * @param stdClass $response
     * @return string
     */
    private function getAssistantResponse(stdClass $response): string
    {
        return $response->choices[0]->message->content;
    }
}
