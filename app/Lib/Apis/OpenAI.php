<?php

namespace App\Lib\Apis;

use App\Lib\Apis\OpenAI\Assistant;
use App\Lib\Apis\OpenAI\Chat;
use App\Lib\Apis\OpenAI\Embedding;
use App\Lib\Apis\OpenAI\Request;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;

class OpenAI
{
    public const BASEURL = 'https://api.openai.com/v1/';

    private Request $request;

    public function __construct()
    {
        $this->request = new Request();
    }

    /**
     * @return OpenAI
     */
    public static function factory(): OpenAI
    {
        return new self();
    }

    /**
     * @return array
     */
    public function models(): array
    {
        $result = $this->request->call('GET', 'models');
        return array_map(static function ($model) {
            return [
                'name' => $model['id'],
                'type' => 'open_ai'
            ];
        }, $result['data']);
    }


}
