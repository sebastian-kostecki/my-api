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

    public function completion(string $model, array $messages, array $params = [])
    {
        $apiParams = [
            'model' => $model,
            'messages' => $messages
        ];
        if (!empty($params['temperature'])) {
            $apiParams['temperature'] = $params['temperature'];
        }
        if (!empty($params['tools'])) {
            $apiParams['tools'] = $params['tools'];
        }

        $result = $this->request->call('POST', 'chat/completions', $apiParams);
        dd($result);
    }
}
