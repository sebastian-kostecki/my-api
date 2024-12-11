<?php

namespace App\Lib\Connections;

use App\Lib\Connections\Notion\Databases;
use App\Lib\Connections\Notion\Pages;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;

class Notion
{
    private const API_URL = 'https://api.notion.com/v1/';

    private PendingRequest $connection;

    public function __construct()
    {
        $this->connection = Http::withHeaders([
            'Notion-Version' => '2022-06-28',
        ])->baseUrl(self::API_URL)->withToken(env('NOTION_API_TOKEN'));
    }

    public function getConnection(): PendingRequest
    {
        return $this->connection;
    }

    public function pages(): Pages
    {
        return new Pages($this);
    }

    public function databases(): Databases
    {
        return new Databases($this);
    }
}
