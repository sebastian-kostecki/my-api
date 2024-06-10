<?php

namespace App\Lib\Apis\OpenAI;

use Illuminate\Support\Facades\Http;

class Request
{
    public const BASEURL = 'https://api.openai.com/v1/';

    /**
     * @param string $method
     * @param string $endpoint
     * @param array $params
     * @return array
     */
    public function call(string $method, string $endpoint, array $params = []): array
    {
        $url = rtrim(self::BASEURL, '/') . '/' . $endpoint;
        $response = Http::withToken(config('services.open_ai.api_key'))->{strtolower($method)}($url, $params);
        if (!$response->successful()) {
            throw new \RuntimeException('Request failed with error: ' . $response->body());
        }
        return $response->json();
    }


//    public function callStream(string $method, string $endpoint, array $params = []): ?array
//    {
//        $url = rtrim(self::BASEURL, '/') . '/' . $endpoint;
//        $response = Http::withToken(config('services.open_ai.api_key'))->{strtolower($method)}($url, $params);
//
//        $body = $response->body();
//        $chunks = explode("\n", $body);
//        foreach ($chunks as $chunk) {
//            $data = Str::after($chunk, 'data: ');
//            dump($data);
//        }
//
//
//        if (!$response->successful()) {
//            throw new \RuntimeException('Request failed with error: ' . $response->body());
//        }
//        return $response->json();
//    }
}
