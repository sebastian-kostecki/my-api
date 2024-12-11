<?php

namespace App\Lib\Apis\OpenAI\Assistant;

use App\Models\Action;

class Assistant
{
    private \App\Lib\Apis\OpenAI\Assistant $assistant;

    public function __construct($assistant)
    {
        $this->assistant = $assistant;
    }

    public function list(): array
    {
        $url = $this->assistant->api::BASEURL.'assistants';
        $result = $this->assistant->request->get($url);

        return json_decode($result->body(), true);
    }

    public function retrieve(string $assistantId): array
    {
        $url = $this->assistant->api::BASEURL.'assistants/'.$assistantId;
        $result = $this->assistant->request->get($url);

        return json_decode($result->body(), true);
    }

    public function create(array $params): array
    {
        $data = [
            'model' => $params['model'],
            'name' => $params['name'],
            'instructions' => $params['instructions'],
            'tools' => [
                ['type' => 'code_interpreter'],
            ],
        ];
        $url = $this->assistant->api::BASEURL.'assistants';
        $result = $this->assistant->request->post($url, $data);

        return json_decode($result->body(), true);
    }

    public function modify(Action $action): array
    {
        $data = [
            'model' => $action->model,
            'name' => $action->name,
            'instructions' => $action->instructions,
        ];
        $url = $this->assistant->api::BASEURL.'assistants/'.$action->remoteAssistant->remote_id;
        $result = $this->assistant->request->post($url, $data);

        return json_decode($result->body(), true);
    }

    public function delete(Action $action): void
    {
        $url = $this->assistant->api::BASEURL.'assistants/'.$action->remoteAssistant->remote_id;
        $this->assistant->request->delete($url);
    }
}
