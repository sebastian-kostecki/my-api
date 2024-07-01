<?php

namespace App\Lib\Connections\ArtificialIntelligence;

use App\Lib\Apis\Anthropic as AnthropicApi;
use App\Lib\Interfaces\Connections\ArtificialIntelligenceInterface;

class Anthropic implements ArtificialIntelligenceInterface
{
    public AnthropicApi $api;

    private array $models = [
        'claude-3-5-sonnet-20240620'
    ];

    public function __construct()
    {
        $this->api = new AnthropicApi();
    }

    /**
     * @return array{
     *     name: string,
     *     type: string
     * }
     */
    public function getModels(): array
    {
        return array_map(static function (string $model) {
            return [
                'name' => $model,
                'type' => self::class
            ];
        }, $this->models);
    }

    /**
     * @param string $model
     * @param array $messages
     * @param string|null $system
     * @param float $temperature
     * @param float $topP
     * @return string
     */
    public function chat(
        string $model,
        array  $messages,
        ?string  $system = null,
        float  $temperature = 0.5,
        float  $topP = 0.5
    ): string
    {
        return $this->api->message($model, $messages, $system, [
            'temperature' => $temperature,
            'top_p' => $topP
        ]);
    }
}
