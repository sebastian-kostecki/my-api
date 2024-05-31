<?php

namespace App\Lib\Connections\ArtificialIntelligence;

use App\Lib\Apis\OpenAI as Api;
use App\Lib\Interfaces\Connections\ArtificialIntelligenceInterface;

class OpenAI implements ArtificialIntelligenceInterface
{
    public Api $api;

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

    public function chat(string $model, array $messages, array $params)
    {
        $result = $this->api->completion($model, $messages, $params);
    }
}
