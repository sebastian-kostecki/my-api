<?php

namespace App\Lib\Assistant\Assistant;

use App\Enums\Assistant\ChatModel as Model;
use App\Lib\Assistant\Assistant;
use App\Lib\Exceptions\ConnectionException;
use App\Models\Conversation;
use JsonException;

class Query
{
    protected Assistant $assistant;

    protected array $resources = [];
    protected string $response;

    public function __construct(Assistant $assistant)
    {
        $this->assistant = $assistant;
    }

//    /**
//     * @return void
//     * @throws ConnectionException
//     * @throws JsonException
//     */
//    public function execute(): void
//    {
//        $this->assignCategory();
//        if ($this->assistant->category !== 'all') {
//            $this->getResources();
//        }
//        $this->assistant->conversation->updateSystemPrompt($this->resources);
//        $this->sendRequest();
//        $this->setResponse();
//    }
//
//    /**
//     * @return void
//     * @throws JsonException
//     */
//    public function assignCategory(): void
//    {
//        $model = Model::GPT3;
//        $messages = [
//            [
//                'role' => 'system',
//                'content' => "Identify the following query with one of the categories below."
//                    . "Query is for AI Assistant who needs to identify parts of a long-term memory to access the most relevant information."
//                    . "categories: memory|note|link|conversation|all"
//                    . "If query is related directly to the assistant or user, classify as 'memory'."
//                    . "If query includes any mention of conversation or history, classify as 'conversation'"
//                    . "If query includes any mention of notes, classify as 'note'"
//                    . "If query includes any mention of links, classify as 'link'"
//                    . "If query doesn't fit to any other category, classify as 'all'."
//            ],
//            [
//                'role' => 'user',
//                'content' => $this->assistant->query
//            ]
//        ];
//        $temperature = 0.1;
//        $functions = [
//            [
//                'name' => 'parse_query_category',
//                'description' => 'Parse category of query from user message.',
//                'parameters' => [
//                    'type' => 'object',
//                    'properties' => [
//                        'category' => [
//                            'type' => 'string',
//                            'enum' => [
//                                'memory',
//                                'note',
//                                'link',
//                                'conversation',
//                                'all'
//                            ],
//                        ],
//                    ],
//                    'required' => ['category']
//                ],
//            ]
//        ];
//        $this->assistant->api->chat()->create($model, $messages, $temperature, $functions);
//        $response = $this->assistant->api->chat()->getFunctions();
//        $this->assistant->category = $response->category;
//    }
//
//    /**
//     * @return void
//     * @throws JsonException
//     * @throws ConnectionException
//     */
//    protected function getResources(): void
//    {
//        $embedding = $this->assistant->api->embeddings()->create($this->assistant->query);
//        $points = $this->assistant->vectorDatabase->points()->searchPoints($embedding, $this->assistant->category);
//
//        $scores = array_column($points, 'score');
//        $average = count($scores) > 0 ? array_sum($scores) / count($scores) : 0;
//
//        $filteredResults = array_filter($points, static function ($vector) use ($average) {
//            return $vector->score >= $average;
//        });
//
//        $this->resources = array_map(static function ($item) {
//            return $item->payload->text;
//        }, $filteredResults);
//    }
//
//    /**
//     * @return void
//     * @throws JsonException
//     */
//    protected function sendRequest(): void
//    {
//        $model = Model::GPT3;
//        $messages = Conversation::getConversationsLastFiveMinutes();
//        $this->assistant->api->chat()->create($model, $messages);
//        $this->response = $this->assistant->api->chat()->getResponse();
//    }
//
//    /**
//     * @return void
//     */
//    protected function setResponse(): void
//    {
//        $this->assistant->setResponse($this->response);
//    }
}
