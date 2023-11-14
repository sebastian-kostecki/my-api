<?php

namespace App\Lib\Assistant;

use App\Enums\Assistant\ChatModel as Model;
use App\Enums\Assistant\Type;
use App\Lib\Apis\OpenAI;
use App\Lib\Assistant\Assistant\Forget;
use App\Lib\Assistant\Actions\Query;
use App\Lib\Assistant\Assistant\Save;
use App\Lib\Connections\Qdrant;
use App\Lib\Exceptions\ConnectionException;
use App\Models\Action;
use App\Models\Conversation;
use Illuminate\Support\Str;
use JsonException;
use stdClass;

class Assistant
{
    protected const DATABASE_NAME = 'myapi';

    public OpenAI $api;
    public Qdrant $vectorDatabase;

    private string $query;
    private ?Action $action;

    public function __construct()
    {
        $this->api = new OpenAI();
        $this->vectorDatabase = new Qdrant(self::DATABASE_NAME);
    }

    /**
     * @return void
     */
    public function execute(): void
    {
        if (!$this->action) {
            $action = new Query($this);
        } else {
            $action = $this->action->factory($this);
        }
        $action->execute();
    }

    /**
     * @param string $query
     * @return void
     */
    public function setQuery(string $query): void
    {
        $this->query = $query;
    }

    /**
     * @param string|null $action
     * @return void
     */
    public function setAction(?string $action): void
    {
        $this->action = Action::type($action)->first();
    }

    /**
     * @return string
     */
    public function getQuery(): string
    {
        return $this->query;
    }











//    public string $query;
//    public Type $type;
//    public ?string $category;
//    public ?Action $action;
//
//    protected string $response;
//
//    public OpenAI $api;
//    public Qdrant $database;
//
//    public Conversation $conversation;
//
//    public function __construct()
//    {
//        $this->api = new OpenAI();
//        $this->database = new Qdrant(self::DATABASE_NAME);
//        $this->conversation = new Conversation();
//    }

//    /**
//     * @return Query
//     */
//    public function query(): Query
//    {
//        return new Query($this);
//    }
//
//    /**
//     * @return Save
//     */
//    public function save(): Save
//    {
//        return new Save($this);
//    }
//
//    /**
//     * @return Forget
//     */
//    public function forget(): Forget
//    {
//        return new Forget($this);
//    }
//
//    /**
//     * @param string $query
//     * @return void
//     */
//    public function setQuery(string $query): void
//    {
//        $this->query = $query;
//    }
//
//    /**
//     * @param string|null $action
//     * @return void
//     */
//    public function setAction(?string $action): void
//    {
//        $this->action = Action::type($action)->first();
//    }
//
//    /**
//     * @return void
//     * @throws JsonException
//     */
//    public function setType(): void
//    {
//        if ($this->action) {
//            $this->type = Type::ACTION;
//        } else {
//            $type = $this->assignType();
//            $this->type = Type::from($type->type);
//        }
//    }
//
//    /**
//     * @return stdClass
//     * @throws JsonException
//     */
//    protected function assignType(): stdClass
//    {
//        $model = Model::GPT3;
//        $messages = [
//            [
//                'role' => 'system',
//                'content' => "Identify the following query with one of the types below."
//                    . "Query is for AI Assistant who needs to identify parts of a long-term memory to access the most relevant information."
//                    . "Pay special attention to distinguish questions from actions."
//                    . "types: query|save|forget|action"
//                    . "If query includes any mention of 'save' or 'notes' or 'memory', classify as 'save'."
//                    . "If query includes any mention of 'forget' or 'remove', classify as 'forget'."
//                    . "If query doesn't fit to any other category, classify as 'query'."
//                    . "Focus on the beginning of it. Return plain category name and nothing else."
//            ],
//            [
//                'role' => 'user',
//                'content' => $this->query
//            ]
//        ];
//        $temperature = 0.1;
//        $functions = [
//            [
//                'name' => 'parse_query_type',
//                'description' => 'Parse type of query from user message.',
//                'parameters' => [
//                    'type' => 'object',
//                    'properties' => [
//                        'type' => [
//                            'type' => 'string',
//                            'enum' => [
//                                Type::QUERY->value,
//                                Type::SAVE->value,
//                                Type::FORGET->value
//                            ],
//                        ],
//                    ],
//                    'required' => ['type'],
//                ],
//            ]
//        ];
//        $this->api->chat()->create($model, $messages, $temperature, $functions);
//        return $this->api->chat()->getFunctions();
//    }
//
//    /**
//     * @return void
//     * @throws ConnectionException
//     * @throws JsonException
//     */
//    public function execute(): void
//    {
//        switch ($this->type) {
//            case Type::QUERY:
//                $this->conversation->saveQuestion($this->query);
//                $this->query()->execute();
//                $this->saveAnswerToDatabase();
//                $this->conversation->saveAnswer($this->response);
//                break;
//            case Type::SAVE:
//                $this->save()->execute();
//                break;
//            case Type::FORGET:
//                $this->forget()->execute();
//                break;
//            case Type::ACTION:
//                $action = $this->action->factory($this);
//                $action->execute();
//                break;
//        }
//    }
//
//    /**
//     * @return void
//     * @throws ConnectionException
//     * @throws JsonException
//     */
//    public function saveAnswerToDatabase(): void
//    {
//        $embeddings = $this->api->embeddings()->create($this->response);
//        $point = [
//            "id" => Str::uuid()->toString(),
//            "vector" => $embeddings,
//            "payload" => [
//                "text" => $this->response,
//                'category' => 'conversation'
//            ]
//        ];
//        $this->database->points()->upsertPoint($point);
//    }
//
//    /**
//     * @return string
//     */
//    public function getResponse(): string
//    {
//        return $this->response;
//    }
//
//    /**
//     * @param string $response
//     * @return void
//     */
//    public function setResponse(string $response): void
//    {
//        $this->response = $response;
//    }
}
