<?php

namespace App\Lib\Assistant\Assistant;

use App\Enums\Assistant\ChatModel as Model;
use App\Lib\Assistant\Assistant;
use App\Lib\Exceptions\ConnectionException;
use App\Models\Resource;
use Illuminate\Support\Str;
use JsonException;

class Save
{
    protected Assistant $assistant;

    protected array $tags;
    protected string $title;
    protected string $response;
    protected string $uuid;


    public function __construct(Assistant $assistant)
    {
        $this->assistant = $assistant;
    }

    /**
     * @return void
     * @throws ConnectionException
     * @throws JsonException
     */
    public function execute(): void
    {
        $this->generateTitleAndTags();
        $this->assistant->query()->assignCategory();
        $this->uuid = Str::uuid()->toString();
        $this->saveToDatabase();
        $this->saveResource();
        $this->assistant->setResponse('Zapisano.');
    }

    /**
     * @return void
     * @throws JsonException
     */
    protected function generateTitleAndTags(): void
    {
        $model = Model::GPT3;
        $messages = [
            [
                'role' => 'system',
                'content' => "Based on the text below, generate a list of tags and title to describe the text."
                    . "Example:\n"
                    . "Zapisz informację o moim spotkaniu z Hubertem: {\"title\": \"Spotkanie z Hubertem\", \"tags\": [\"spotkanie\", \"Hubert\"]}"
                    . "Zapamiętaj, że jestem programistą PHP: {\"title\": \"Programista\", \"tags\": [\"programista\", \"PHP\"]}"
            ],
            [
                'role' => 'user',
                'content' => $this->assistant->query
            ]
        ];
        $temperature = 0.1;
        $functions = [
            [
                'name' => 'generate_title_and_tags',
                'description' => 'Generate title and tags for user query',
                'parameters' => [
                    'type' => 'object',
                    'properties' => [
                        'title' => [
                            'type' => 'string',
                            'description' => 'Title of user query'
                        ],
                        'tags' => [
                            'type' => 'array',
                            'items' => [
                                'type' => 'string'
                            ]
                        ]
                    ],
                    'required' => ['category']
                ],
            ]
        ];
        $this->assistant->api->chat()->create($model, $messages, $temperature, $functions);
        $response = $this->assistant->api->chat()->getFunctions();
        $this->title = $response->title;
        $this->tags = $response->tags;
    }

    /**
     * @return void
     * @throws ConnectionException
     * @throws JsonException
     */
    protected function saveToDatabase(): void
    {

        $embedding = $this->assistant->api->embeddings()->create($this->assistant->query);
        $point = [
            "id" => $this->uuid,
            "vector" => $embedding,
            "payload" => [
                "text" => $this->assistant->query,
                'category' => $this->assistant->category
            ]
        ];
        $this->assistant->database->points()->upsertPoint($point);
    }

    /**
     * @return void
     */
    protected function saveResource(): void
    {
        $resource = new Resource();
        $resource->uuid = $this->uuid;
        $resource->title = $this->title;
        $resource->content = $this->assistant->query;
        $resource->category = $this->assistant->category;
        $resource->tags = $this->tags;
        $resource->save();
    }
}
