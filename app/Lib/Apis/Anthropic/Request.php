<?php

namespace App\Lib\Apis\Anthropic;

use Illuminate\Support\Facades\Http;

class Request
{
    public const BASEURL = 'https://api.anthropic.com/v1/';

    public function call(string $method, string $endpoint, array $params = []): array
    {
        $url = rtrim(self::BASEURL, '/').'/'.$endpoint;

        $response = Http::withHeaders([
            'x-api-key' => config('services.anthropic.api_key'),
            'anthropic-version' => '2023-06-01',
            'content-type' => 'application/json',
        ])->{strtolower($method)}($url, $params);
        if (! $response->successful()) {
            throw new \RuntimeException('Request failed with error: '.$response->body());
        }

        return $response->json();
    }
}
