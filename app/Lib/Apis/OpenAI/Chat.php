<?php

namespace App\Lib\Apis\OpenAI;

use App\Enums\Assistant\ChatModel;
use App\Lib\Apis\OpenAI;
use Illuminate\Http\Client\PendingRequest;
use JsonException;
use stdClass;

class Chat
{
    private const BASEURL = 'chat';
    private string $url;
    private PendingRequest $request;
    private stdClass $response;

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
     * @throws JsonException
     */
    public function create(ChatModel $model, array $messages, float $temperature = 0.5, array $functions = []): void
    {
        $url = $this->url . '/completions';
        $params['model'] = $model->value;
        $params['temperature'] = $temperature;
        $params['messages'] = $messages;
        if (!empty($functions)) {
            $params['functions'] = $functions;
        }
        $result = $this->request->post($url, $params);
        $this->response = json_decode($result->body(), false, 512, JSON_THROW_ON_ERROR);
    }

    /**
     * @return stdClass
     * @throws JsonException
     */
    public function getFunctions(): stdClass
    {
        return json_decode($this->response->choices[0]->message->function_call->arguments, false, 512, JSON_THROW_ON_ERROR);
    }

    /**
     * @return string
     */
    public function getResponse(): string
    {
        return $this->response->choices[0]->message->content;
    }
}
