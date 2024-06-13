<?php

namespace App\Lib\Connections\ArtificialIntelligence;

use App\Lib\Apis\OpenAI as Api;
use App\Lib\Interfaces\Connections\ArtificialIntelligenceInterface;
use App\Models\Model;

class OpenAI implements ArtificialIntelligenceInterface
{
    public Api $api;

    private float $temperature = 0.5;
    private bool $jsonMode = false;

    public function __construct()
    {
        $this->api = new Api();
    }

    public static function factory(): OpenAI
    {
        return new self();
    }

    /**
     * @return array{
     *     name: string,
     *     type: string
     * }
     */
    public function getModels(): array
    {
        $result = $this->api->models();
        $models = collect($result['data']);
        return $models
            ->sortByDesc('created')
            ->map(function ($model) {
                return [
                    'name' => $model['id'],
                    'type' => self::class
                ];
            })->values()->toArray();
    }

    /**
     * @param string $model
     * @param array $messages
     * @return string
     */
    public function completion(string $model, array $messages): string
    {
        return $this->api->completion($model, $messages, $this->createParams());
    }

    /**
     * @param string $text
     * @return string
     */
    public function shortSummarize(string $text): string
    {
        $model = Model::getModel('gpt-3.5');
        return $this->api->completion(
            $model->name,
            [
                [
                    'role' => 'user',
                    'content' => "Summarize the following text in one short sentence (maximum 250 characters) in Polish: \n" . $text
                ]
            ],
            [
                'temperature' => 1
            ]
        );
    }

    /**
     * @return array
     */
    private function createParams(): array
    {
        $params = [
            'temperature' => $this->temperature,
        ];
        if ($this->jsonMode) {
            $params['response_format'] = [
                'type' => 'json_object'
            ];
        }
        return $params;
    }
}
