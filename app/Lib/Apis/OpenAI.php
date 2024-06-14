<?php

namespace App\Lib\Apis;

use App\Lib\Apis\OpenAI\Request;

class OpenAI
{
    public const BASEURL = 'https://api.openai.com/v1/';

    private Request $request;

    public function __construct()
    {
        $this->request = new Request();
    }

    /**
     * @return OpenAI
     */
    public static function factory(): OpenAI
    {
        return new self();
    }

    /**
     * @return array
     */
    public function models(): array
    {
        return $this->request->call('GET', 'models');
    }

    /**
     * @param string $model
     * @param array $messages
     * @param array $params
     * @return string
     */
    public function completion(string $model, array $messages, array $params = []): string
    {
        $apiParams = [
            'model' => $model,
            'messages' => $messages,
            ...$params
        ];

        $result = $this->request->call('POST', 'chat/completions', $apiParams);
        return $result['choices'][0]['message']['content'];
    }

    /**
     * @param string $model
     * @param string $input
     * @return array
     */
    public function embeddings(string $model, string $input): array
    {
        $apiParams = [
            'model' => $model,
            'input' => $input
        ];
        $result = $this->request->call('POST', 'embeddings', $apiParams);
        return $result['data'][0]['embedding'];
    }
}
