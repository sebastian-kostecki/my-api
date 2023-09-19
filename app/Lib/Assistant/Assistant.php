<?php

namespace App\Lib\Assistant;

use App\Enums\Assistant\ChatModel as Model;
use App\Enums\Assistant\Type;
use App\Lib\Apis\OpenAI;
use App\Lib\Assistant\Assistant\CategoryParams;
use App\Lib\Assistant\Assistant\Query;
use App\Lib\Assistant\Assistant\Save;
use App\Lib\Connections\Qdrant;
use App\Lib\Exceptions\ConnectionException;
use App\Models\Action;
use App\Models\Conversation;
use App\Models\Resource;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use JsonException;
use Qdrant\Exception\InvalidArgumentException;
use stdClass;

class Assistant
{
    protected const DATABASE_NAME = 'myapi';

    public string $query;
    public Type $type;
    public ?string $category;
    public ?Action $action;

    protected string $response;

    public OpenAI $api;
    public Qdrant $database;

    protected Conversation $conversation;

    public function __construct()
    {
        $this->api = new OpenAI();
        $this->database = new Qdrant(self::DATABASE_NAME);
        $this->conversation = new Conversation();
    }

    /**
     * @return Query
     */
    public function query(): Query
    {
        return new Query($this);
    }

    /**
     * @return Save
     */
    public function save(): Save
    {
        return new Save($this);
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
     * @return void
     * @throws JsonException
     */
    public function setType(): void
    {
        if ($this->action) {
            $this->type = Type::ACTION;
        } else {
            $type = $this->assignType();
            $this->type = Type::from($type->type);
        }
    }

    /**
     * @return stdClass
     * @throws JsonException
     */
    protected function assignType(): stdClass
    {
        $model = Model::GPT3;
        $messages = [
            [
                'role' => 'system',
                'content' => "Identify the following query with one of the types below."
                    . "Query is for AI Assistant who needs to identify parts of a long-term memory to access the most relevant information."
                    . "Pay special attention to distinguish questions from actions."
                    . "types: query|save|forget|action"
                    . "If query includes any mention of 'save' or 'notes' or 'memory', classify as 'save'."
                    . "If query includes any mention of 'forget' or 'remove', classify as 'forget'."
                    . "If query doesn't fit to any other category, classify as 'query'."
                    . "Focus on the beginning of it. Return plain category name and nothing else."
            ],
            [
                'role' => 'user',
                'content' => $this->query
            ]
        ];
        $temperature = 0.1;
        $functions = [
            [
                'name' => 'parse_query_type',
                'description' => 'Parse type of query from user message.',
                'parameters' => [
                    'type' => 'object',
                    'properties' => [
                        'type' => [
                            'type' => 'string',
                            'enum' => [
                                Type::QUERY->value,
                                Type::SAVE->value,
                                Type::FORGET->value
                            ],
                        ],
                    ],
                    'required' => ['type'],
                ],
            ]
        ];
        $this->api->chat()->create($model, $messages, $temperature, $functions);
        return $this->api->chat()->getFunctions();
    }

    /**
     * @return void
     * @throws ConnectionException
     * @throws JsonException
     */
    public function execute(): void
    {


        switch ($this->type) {
            case Type::QUERY:
                $this->conversation->saveQuestion($this->query);
                $this->query()->execute();
                $this->saveAnswer();
                $this->conversation->saveAnswer($this->response);
                break;
            case Type::SAVE:
                $this->save()->execute();
                break;
            case Type::FORGET:
                //usuwanie z pamięci memory
                break;
            case Type::ACTION:
                //wywoływanie akcji
                //zastanowić się jak zrobić, żeby nie zawsze zapisywało answer do qdrant
                break;
        }

    }

    /**
     * @return void
     * @throws ConnectionException
     * @throws JsonException
     */
    protected function saveAnswer(): void
    {
        $embeddings = $this->api->embeddings()->create($this->response);
        $point = [
            "id" => Str::uuid()->toString(),
            "vector" => $embeddings,
            "payload" => [
                "text" => $this->response,
                'category' => 'conversation'
            ]
        ];
        $this->database->points()->upsertPoint($point);
    }

    /**
     * @return string
     */
    public function getResponse(): string
    {
        return $this->response;
    }

    /**
     * @param string $response
     * @return void
     */
    public function setResponse(string $response): void
    {
        $this->response = $response;
    }






























//    public function findAction()
//    {
//        $types = Action::pluck('type')->toArray();
//
//        $systemPrompt = "Describe my intention from message below with JSON"
//            . "Focus on the beginning of it. Always return JSON and nothing more. \n"
//            . "From the actions below, choose the best fit.\n"
//            . "Actions: " . implode('|', $types) . "\nExamples:\n";
//        foreach ($types as $type) {
//            $systemPrompt .= implode("\n", $type::EXAMPLE) . "\n";
//        }
//
//        $params = [
//            'model' => ChatModel::G->value,
//            'temperature' => 0.1,
//            'messages' => [
//                [
//                    'role' => 'system',
//                    'content' => $systemPrompt
//                ],
//                [
//                    'role' => 'user',
//                    'content' => $this->query
//                ]
//            ]
//        ];
//
//        $response = \OpenAI\Laravel\Facades\OpenAI::chat()->create($params);
//        dd($response);
//    }


    protected string $prompt = "";
    //protected string $type = "";


    protected Qdrant $vectorDatabase;
    //protected string $response = "";
    //protected Conversation $conversation;


    /**
     * @return string
     */
    public function selectTypeAction(): string
    {
        $params = ActionTypeParams::make($this->prompt);
        return $this->api->chat($params);
    }

//    /**
//     * @param array $params
//     * @return string
//     */
//    public function query(array $params): string
//    {
//        $this->conversation->saveQuestion($params['query']);
//
//        if (!$params['group']) {
//            $params['group'] = $this->categorizePrompt();
//        }
//
//        $resources = $this->getResources($params);
//        Conversation::updateSystemPrompt($resources);
//
//        $params = [
//            'model' => ChatModel::GPT3->value,
//            'messages' => Conversation::getConversationsLastFiveMinutes()
//        ];
//        $response = $this->api->chat($params);
//
//        $this->conversation->saveAnswer($response);
//
//        return $response;
//    }

//    /**
//     * @param array $params
//     * @return string
//     */
//    public function save(array $params): string
//    {
//        $language = detectLanguage($params['query']);
//        if ($language !== 'pl') {
//            $params['query'] = $this->api->translateToPolish($params['query']);
//        }
//
//        if (!$params['group']) {
//            $params['group'] = $this->api->categorizeQueryPrompt($params['query']);
//        }
//
//        $response = $this->api->generateTagsAndTitle($params['query']);
//
//        $resource = new Resource();
//        $resource->title = $response->title;
//        $resource->content = $params['query'];
//        $resource->category = $params['group'];
//        $resource->tags = $response->tags;
//        $resource->save();
//
//        $text = $resource->content . " " . implode(',', $resource->tags);
//        $embedding = $this->api->createEmbedding($text);
//
//        $this->vectorDatabase->insertVector($resource->id, $embedding, [
//            'id' => $resource->id,
//            'category' => $resource->category,
//            'tags' => implode(',', $resource->tags)
//        ]);
//        return "Notatkę zapisano";
//    }

    /**
     * @param $params
     * @return string
     * @throws InvalidArgumentException
     */
    public function forget($params): string
    {
        $embedding = $this->api->createEmbedding($params['query']);
        $resourceId = $this->vectorDatabase->findMessage($embedding);

        $resource = Resource::findOrFail($resourceId);
        Log::debug('forget', [$resource]);
        $resource->delete();

        $vectorDatabase = new Qdrant('test');
        $vectorDatabase->deleteVector($resourceId);

        return "Notatkę usunięto";
    }

    /**
     * @param array $params
     * @return string
     */
    public function action(array $params): string
    {
        if (!$params['action'] || !isset($params['action'])) {
            $slug = $this->selectAction($params['query']);
            $params['action'] = Action::where('slug', $slug)->value('type');
        }

        $action = new $params['action']();
        $action->setPrompt($params['query']);
        return $action->execute();
    }

    /**
     * @param string $prompt
     * @return void
     */
    public function setPrompt(string $prompt): void
    {
        $this->prompt = $prompt;
    }

    protected function selectAction(string $query)
    {
        $actions = Action::pluck('slug')->toArray();

        $content = "Describe my intention from message below with JSON";
        $content .= "Focus on the beginning of it. Always return JSON and nothing more. \n";
        $content .= "Actions: " . implode('|', $actions) . "\n";
        $content .= "Example: Dodaj zadanie do pracy o zrobieniu nowego wyglądu strony {\"action\": \"add-work-task\"}";
        $content .= "Zadanie o nauczeniu się Vue.js {\"action\": \"add-private-task\"}";
        $content .= "Zapisz to jako email {\"action\": \"save-email\"}";
        $content .= "Napisz metodę w kontrolerze tworzącą nowego użytkownika. {\"action\": \"text-to-php\"}";
        $content .= "Przetłumacz tekst: I would like to eat some pizza. {\"action\": \"translate\"}";
        $content .= "###message\n{$$query}";

        $response = $this->api->getJson($content);
        $response = returnJson($response);
        return json_decode($response)->action;
    }

    /**
     * @return string
     */
    protected function categorizePrompt(): string
    {
        $params = CategoryParams::make($this->prompt);
        return $this->api->chat($params);
    }

//    protected function getResources(array $params)
//    {
//        $embedding = $this->api->createEmbedding($params['query']);
//        $resourcesIds = $this->vectorDatabase->getIdsOverAverageScore($embedding);
//
//        return Resource::where('category', $params['group'])
//            ->whereIn('id', $resourcesIds)
//            ->pluck('content')
//            ->toArray();
//    }
}
