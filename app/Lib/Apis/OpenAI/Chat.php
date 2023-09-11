<?php

namespace App\Lib\Apis\OpenAI;

use App\Enums\Assistant\ChatModel;
use App\Lib\Apis\OpenAI;
use Illuminate\Http\Client\PendingRequest;

class Chat
{
    private const BASEURL = 'chat';
    private string $url;
    private PendingRequest $request;
    private \stdClass $response;

    /**
     * @param OpenAI $api
     */
    public function __construct(OpenAI $api)
    {
        $this->url = $api::BASEURL . self::BASEURL;
        $this->request = $api->request;
    }

    /**
     * @param ChatModel $model
     * @param array $messages
     * @param float $temperature
     * @param array $functions
     * @return void
     */
    public function create(ChatModel $model, array $messages, float $temperature, array $functions = []): void
    {
        $url = $this->url . '/completions';
        $params['model'] = $model->value;
        $params['temperature'] = $temperature;
        $params['messages'] = $messages;
        if (!empty($functions)) {
            $params['functions'] = $functions;
        }

        $result = $this->request->post($url, $params);
        $this->response = json_decode($result->body(), false);
    }

    /**
     * @return \stdClass
     */
    public function getFunctions(): \stdClass
    {
        return json_decode($this->response->choices[0]->message->function_call->arguments, false);
    }
}
