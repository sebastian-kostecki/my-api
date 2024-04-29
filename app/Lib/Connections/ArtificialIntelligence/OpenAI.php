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

    /**
     * @return array{
     *     name: string,
     *     type: string
     * }
     */
    public function getModels(): array
    {
        return $this->api->models();
    }
}
