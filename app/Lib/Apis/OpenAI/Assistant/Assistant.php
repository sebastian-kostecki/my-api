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

    /**
     * @return array
     */
    public function list(): array
    {
        $url = $this->assistant->api::BASEURL . 'assistants';
        $result = $this->assistant->request->get($url);
        return json_decode($result->body(), true);
    }

    /**
     * @param string $assistantId
     * @return array
     */
    public function retrieve(string $assistantId): array
    {
        $url = $this->assistant->api::BASEURL . 'assistants/' . $assistantId;
        $result = $this->assistant->request->get($url);
        return json_decode($result->body(), true);
    }

    /**
     * @param array $params
     * @return array
     */
    public function create(array $params): array
    {
        $data = [
            'model' => $params['model'],
            'name' => $params['name'],
            'instructions' => $params['instructions'],
            'tools' => [
                ['type' => 'code_interpreter']
            ]
        ];
        $url = $this->assistant->api::BASEURL . 'assistants';
        $result = $this->assistant->request->post($url, $data);
        return json_decode($result->body(), true);
    }

    /**
     * @param string $assistantId
     * @param array $params
     * @return array
     */
    public function modify(Action $action): array
    {
        $data = [
            'model' => $action->model,
            'name' => $action->name,
            'instructions' => $action->instructions
        ];
        $url = $this->assistant->api::BASEURL . 'assistants/' . $action->remoteAssistant->remote_id;
        $result = $this->assistant->request->post($url, $data);
        return json_decode($result->body(), true);
    }
}
