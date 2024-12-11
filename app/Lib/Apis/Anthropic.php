<?php

namespace App\Lib\Apis;

use App\Lib\Apis\Anthropic\Request;

class Anthropic
{
    private Request $request;

    public function __construct()
    {
        $this->request = new Request;
    }

    public function message(string $model, array $messages, ?string $system = null, array $params = []): string
    {
        $apiParams = [
            'model' => $model,
            'messages' => $messages,
            'max_tokens' => 4096,
            ...$params,
        ];

        if ($system) {
            $apiParams['system'] = $system;
        }

        $result = $this->request->call('POST', 'messages', $apiParams);

        return $result['content'][0]['text'];
    }
}