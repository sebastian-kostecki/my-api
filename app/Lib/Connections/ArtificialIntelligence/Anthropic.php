<?php

namespace App\Lib\Connections\ArtificialIntelligence;

use App\Lib\Apis\Anthropic as AnthropicApi;
use App\Lib\Interfaces\Connections\ArtificialIntelligenceInterface;

class Anthropic implements ArtificialIntelligenceInterface
{
    public AnthropicApi $api;

    public function __construct()
    {
        $this->api = new AnthropicApi;
    }

    /**
     * @return array{
     *     name: string,
     *     type: string
     * }
     */
    public function getModels(): array
    {
        $models = $this->api->models();

        return array_map(static function (array $model) {
            return [
                'title' => $model['display_name'],
                'name' => $model['id'],
                'type' => self::class,
            ];
        }, $models['data']);
    }

    public function chat(
        string $model,
        array $messages,
        ?string $system = null,
        float $temperature = 0.5,
        float $topP = 0.5
    ): string {
        return $this->api->message($model, $messages, $system, [
            'temperature' => $temperature,
            'top_p' => $topP,
        ]);
    }
}
