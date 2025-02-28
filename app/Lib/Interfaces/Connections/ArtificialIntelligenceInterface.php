<?php

namespace App\Lib\Interfaces\Connections;

interface ArtificialIntelligenceInterface
{
    /**
     * @return array{
     *     title: string,
     *     name: string,
     *     type: string
     * }
     */
    public function getModels(): array;

    public function chat(
        string $model,
        array $messages,
        ?string $system = null,
        float $temperature = 0.5,
        float $topP = 0.5
    ): string;
}
