<?php

namespace App\Lib\Connections\ArtificialIntelligence;

use App\Lib\Apis\OpenAI as Api;
use App\Lib\Interfaces\Connections\ArtificialIntelligenceInterface;

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
     * setters
     */
    public function setJsonMode(bool $mode = true): void
    {
        $this->jsonMode = $mode;
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
