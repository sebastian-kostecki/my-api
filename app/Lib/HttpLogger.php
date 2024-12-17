<?php

namespace App\Lib;

use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Log;

class HttpLogger
{
    public static function log(Response $response, array $body = []): void
    {
        $method = $response->transferStats->getRequest()->getMethod();
        $url = $response->handlerStats()['url'];
        $responseBody = $response->body();

        Log::channel('api_requests')->debug('API: ', [
            'action' => "{$method} {$url}",
            'request' => http_build_query($body),
            'response' => $responseBody,
        ]);
    }
}
