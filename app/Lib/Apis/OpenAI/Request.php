<?php

namespace App\Lib\Apis\OpenAI;

use Illuminate\Support\Facades\Http;
use OpenAI\Laravel\Facades\OpenAI;
use OpenAI\Responses\Chat\CreateResponse;
use OpenAI\Responses\Embeddings\CreateResponse as CreateResponseEmbedding;

class Request
{
    protected array $messages;
    protected string $input;
    protected string $model = 'gpt-3.5-turbo';
    protected float $temperature = 0.5;
    protected CreateResponse|CreateResponseEmbedding $response;

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

    public function embedding(): void
    {
        $this->response = OpenAI::embeddings()->create([
            'model' => $this->model,
            'input' => $this->input,
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
     * @param string $input
     * @return void
     */
    public function setInput(string $input): void
    {
        $this->input = $input;
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

    public function getEmbedding()
    {
        return $this->response->toArray()['data'][0]['embedding'];
    }


    public function call()
    {
        //Http::withToken(config('openai.'))
    }


}
