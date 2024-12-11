<?php

namespace App\Lib\Connections\Notion;

use App\Lib\Connections\Notion;
use Illuminate\Support\Collection;

class Databases
{
    public function __construct(readonly private Notion $api) {}

    public function queryPanelalphaTasks(string $databaseId): Collection
    {
        $result = $this->api->getConnection()->post("/databases/{$databaseId}/query", [
            'filter' => [
                'and' => [],
            ],
        ])->json();

        return collect($result['results'])->mapWithKeys(function ($item) {
            return [$item['properties']['ID']['number'] => [
                'page_id' => $item['id'],
                'id' => $item['properties']['ID']['number'],
                'url' => $item['properties']['URL']['url'],
            ]];
        });
    }
}
