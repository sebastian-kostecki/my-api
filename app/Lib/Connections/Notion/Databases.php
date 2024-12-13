<?php

namespace App\Lib\Connections\Notion;

use App\Lib\Connections\Notion;
use App\Lib\HttpLogger;
use FiveamCode\LaravelNotionApi\Entities\Page;
use FiveamCode\LaravelNotionApi\Query\Filters\Filter;
use FiveamCode\LaravelNotionApi\Query\Filters\Operators;
use Illuminate\Support\Collection;

class Databases
{
    public function __construct(readonly private Notion $api) {}

    public function queryPanelalphaTasks(string $databaseId): Collection
    {
        $response = $this->api->getConnection()->post("/databases/{$databaseId}/query", [
            'filter' => [
                'and' => [],
            ],
        ]);

        HttpLogger::log($response);
        $result = $response->json();

        return collect($result['results'])->mapWithKeys(function ($item) {
            return [$item['properties']['ID']['number'] => [
                'page_id' => $item['id'],
                'id' => $item['properties']['ID']['number'],
                'url' => $item['properties']['URL']['url'],
            ]];
        });
    }

    public function queryPanelalphaTodayTasks(string $databaseId): Collection
    {
        $response = $this->api->getConnection()->post("/databases/{$databaseId}/query", [
            'filter' => [
                'or' => [
                    [
                        'property' => 'Today',
                        'checkbox' => [
                            'equals' => true,
                        ],
                    ],
                ],
            ],
        ])->json();

        HttpLogger::log($response);
        $result = $response->json();

        return collect($result['results'])->map(function ($item) {
            return [
                'name' => $item['properties']['Name']['title'][0]['text']['content'],
                'milestone' => $item['properties']['Milestone']['rich_text'][0]['text']['content'],
                'status' => [
                    'name' => $item['properties']['Status']['status']['name'],
                    'color' => $item['properties']['Status']['status']['color'],
                ],
                'url' => $item['properties']['URL']['url'],
            ];
        });
    }

    public function queryPanelalphaNextTasks(string $databaseId): Collection
    {
        $response = $this->api->getConnection()->post("/databases/{$databaseId}/query", [
            'filter' => [
                'or' => [
                    [
                        'property' => 'Status',
                        'status' => [
                            'does_not_equal' => 'Done',
                        ],
                    ],
                ],
            ],
            'sorts' => [
                [
                    'property' => 'Milestone',
                    'direction' => 'ascending',
                ],
                [
                    'property' => 'Status',
                    'direction' => 'descending',
                ],
                [
                    'property' => 'Priority',
                    'direction' => 'ascending',
                ],
            ],
        ]);

        HttpLogger::log($response);
        $result = $response->json();

        return collect($result['results'])->map(function ($item) {
            return [
                'name' => $item['properties']['Name']['title'][0]['text']['content'],
                'milestone' => $item['properties']['Milestone']['rich_text'][0]['text']['content'],
                'priority' => [
                    'name' => $item['properties']['Priority']['select']['name'],
                    'color' => $item['properties']['Priority']['select']['color'] === 'yellow'
                        ? 'orange'
                        : $item['properties']['Priority']['select']['color'],
                ],
                'status' => [
                    'name' => $item['properties']['Status']['status']['name'],
                    'color' => $item['properties']['Status']['status']['color'] === 'default'
                        ? 'gray'
                        : $item['properties']['Status']['status']['color'],
                ],
                'url' => $item['properties']['URL']['url'],
            ];
        });
    }

    public function clearDailyTasksStatus(string $databaseId): void
    {
        $todayFilter = Filter::rawFilter('Today', [
            'checkbox' => [Operators::EQUALS => true],
        ]);

        $result = \Notion::database($databaseId)
            ->filterBy($todayFilter)
            ->query()
            ->asCollection();

        $result->each(function ($item) {
            $id = $item->getId();
            $page = new Page;
            $page->setId($id);
            $page->setCheckbox('Today', false);
            \Notion::pages()->update($page);
        });
    }
}
