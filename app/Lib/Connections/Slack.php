<?php

namespace App\Lib\Connections;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Http;

class Slack
{
    protected string $channel;

    public function __construct(string $channel)
    {
        $this->channel = $channel;
    }

    public function sendMessage(string $message): JsonResponse
    {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . env('SLACK_BOT_TOKEN'),
        ])->post('https://slack.com/api/chat.postMessage', [
            'channel' => $this->channel,
            'text' => $message,
        ]);
        if ($response->successful()) {
            return new JsonResponse(['success' => true]);
        }
        return new JsonResponse(['success' => false]);
    }

    public function getMessage()
    {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . env('SLACK_BOT_TOKEN'),
        ])->post('https://slack.com/api/chat.postMessage', [
            'text' => $this->channel,
            'text' => $message,
        ]);
    }
}
