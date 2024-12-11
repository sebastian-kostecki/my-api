<?php

namespace App\Lib\Connections\Notion;

use App\Lib\Connections\Notion;

class Pages
{
    public function __construct(readonly private Notion $api) {}

    public function createPanelalphaTaskPage(
        string $databaseId,
        int $id,
        string $name,
        string $url,
        ?string $milestone = null,
        ?string $priority = null,
        array $labels = [],
        ?string $description = null,
    ) {
        $params = [
            'parent' => [
                'database_id' => $databaseId,
            ],
            'properties' => [
                'Name' => [
                    'title' => [
                        [
                            'text' => [
                                'content' => $name,
                            ],
                        ],
                    ],
                ],
                'Status' => [
                    'status' => [
                        'name' => 'Not started',
                    ],
                ],
                'Milestone' => [
                    'rich_text' => [
                        [
                            'type' => 'text',
                            'text' => [
                                'content' => $milestone ?? '',
                            ],
                        ],
                    ],
                ],
                'Priority' => [
                    'select' => [
                        'name' => $priority ?? 'Medium',
                    ],
                ],
                'URL' => [
                    'url' => $url,
                ],
                'ID' => [
                    'number' => $id,
                ],
            ],
            'children' => [
                [
                    'object' => 'block',
                    'type' => 'callout',
                    'callout' => [
                        'rich_text' => [
                            [
                                'type' => 'text',
                                'text' => [
                                    'content' => 'Description',
                                ],
                                'annotations' => [
                                    'bold' => true,
                                ],
                            ],
                            [
                                'type' => 'text',
                                'text' => [
                                    'content' => "\n".$description,
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ];

        if (! empty($labels)) {
            foreach ($labels as $label) {
                $params['properties']['Labels']['multi_select'][] = [
                    'name' => $label,
                ];
            }
        }

        $response = $this->api->getConnection()->post('/pages', $params);

        if ($response->failed()) {
            throw new \Exception($response->json()['message']);
        }
    }

    public function updatePanelalphaTaskPage(
        string $pageId,
        ?string $status = null,
        ?string $milestone = null,
        ?string $priority = null,
    ) {
        $params = [
            'properties' => [
                'Milestone' => [
                    'rich_text' => [
                        [
                            'type' => 'text',
                            'text' => [
                                'content' => $milestone ?? '',
                            ],
                        ],
                    ],
                ],
                'Priority' => [
                    'select' => [
                        'name' => $priority ?? 'Medium',
                    ],
                ],
            ],
        ];

        if ($status !== null) {
            $params['properties']['Status']['status']['name'] = $status;
        }

        $this->api->getConnection()->patch("/pages/{$pageId}", $params);
    }
}
