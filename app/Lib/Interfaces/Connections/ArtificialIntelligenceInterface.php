<?php

namespace App\Lib\Interfaces\Connections;

interface ArtificialIntelligenceInterface
{
    /**
     * @return array{
     *     name: string,
     *     type: string
     * }
     */
    public function getModels(): array;

    /**
     * @param string $model
     * @param array $messages
     * @param ?string $system
     * @param float $temperature
     * @param float $topP
     * @return string
     */
    public function chat(
        string $model,
        array  $messages,
        ?string $system = null,
        float  $temperature = 0.5,
        float  $topP = 0.5
    ): string;
}
