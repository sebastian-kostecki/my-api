<?php

namespace App\Lib;

use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Log;

class HttpLogger
{
    public static function log(Response $response): void
    {
        $method = $response->transferStats->getRequest()->getMethod();
        $url = $response->handlerStats()['url'];
        //$responseBody = $response->json();
        //$responseCode = $response->status();

        Log::channel('api_requests')->info('Request', [
            'url' => "{$method} {$url}",
        ]);
    }
}
