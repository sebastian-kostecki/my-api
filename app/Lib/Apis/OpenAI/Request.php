<?php

namespace App\Lib\Apis\OpenAI;

use OpenAI\Laravel\Facades\OpenAI;
use OpenAI\Responses\Chat\CreateResponse;

class Request
{
    protected array $messages;
    protected string $model = 'gpt-3.5-turbo';
    protected float $temperature = 0.5;
    protected CreateResponse $response;

    /**
     * @return void
     */
    public function chat(): void
    {
        $this->response = OpenAI::chat()->create([
            'model' => $this->model,
            'temperature' => $this->temperature,
            'messages' => $this->messages,
        ]);
    }

    /**
     * @param array $messages
     * @return void
     */
    public function setMessages(array $messages): void
    {
        $this->messages = $messages;
    }

    /**
     * @param string $model
     * @return void
     */
    public function setModel(string $model): void
    {
        $this->model = $model;
    }

    /**
     * @param string $temperature
     * @return void
     */
    public function setTemperature(string $temperature): void
    {
        $this->temperature = $temperature;
    }

    /**
     * @return string
     */
    public function getContent(): string
    {
        return $this->response->choices[0]->message->content;
    }
}
