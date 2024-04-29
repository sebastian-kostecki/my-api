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
}
