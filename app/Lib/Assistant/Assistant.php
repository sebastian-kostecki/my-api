<?php

namespace App\Lib\Assistant;

use App\Lib\Connections\OpenAI;
use App\Lib\Connections\Qdrant;
use App\Models\Action;
use App\Models\Conversation;
use App\Models\Resource;
use Illuminate\Support\Facades\Log;
use OpenAI\Laravel\Facades\OpenAI as Client;
use Qdrant\Exception\InvalidArgumentException;

class Assistant
{
    protected string $prompt = "";
    protected string $type = "";

    protected OpenAI $openAI;
    protected Qdrant $vectorDatabase;
    protected string $response = "";

    public function __construct()
    {
        $this->openAI = new OpenAI();
        $this->vectorDatabase = new Qdrant('test');
    }

    /**
     * @param string $query
     * @return string
     */
    public function selectType(string $query): string
    {
        $content = "Identify the following query with one of the types below.";
        $content .= "Query is for AI Assistant who needs to identify parts of a long-term memory to access the most relevant information. Pay special attention to distinguish questions from actions.";
        $content .= "types: query|save|forget|action\n";
        $content .= "If query is a direct action like 'Add task' or 'Translate text', classify query as 'action'";
        $content .= "If query includes any mention of 'save' or 'notes' or 'memory' or 'link', classify as 'save'";
        $content .= "If query includes any mention of 'forget' or 'remove', classify as 'forget'";
        $content .= "If query doesn't fit to any other category, classify as 'query'.\n";
        $content .= "Focus on the beginning of it. Return plain category name and nothing else.\n";
        $content .= "Examples\n: Zapisz wiadomość. \n'save'";
        $content .= "Zapisz notatkę. \n'save'";
        $content .= "Are you Ed? \n'query'";
        $content .= "Co to jest docker? \n'query'";
        $content .= "Zapamiętaj, że jestem programistą. \n'query'";
        $content .= "Zapomnij notatkę o poprzednim spotkaniu. \n'forget'";
        $content .= "Dodaj task o fixie do notifications. \n'action'";
        $content .= "###message\n{$query}";

        $response = Client::chat()->create([
            'model' => 'gpt-3.5-turbo',
            'temperature' => 0.1,
            'messages' => [
                [
                    'role' => 'user',
                    'content' => $content
                ],
            ],
        ]);
        return $response->choices[0]->message->content;
    }

    /**
     * @param array $params
     * @return string
     */
    public function query(array $params): string
    {
        $newConversation = new Conversation();
        $newConversation->saveQuestion($params['query']);

        if (!$params['group']) {
            $params['group'] = $this->openAI->categorizeQueryPrompt($params['query']);
        }

        $embedding = $this->openAI->createEmbedding($params['query']);
        $resourcesIds = $this->vectorDatabase->getIdsOverAverageScore($embedding);

        $resources = Resource::where('category', $params['group'])
            ->whereIn('id', $resourcesIds)
            ->pluck('content')
            ->toArray();

        Conversation::updateSystemPrompt($resources);
        $messages = Conversation::getConversationsLastFiveMinutes();

        $response = $this->openAI->chat($messages);
        $newConversation->saveAnswer($response);

        return $response;
    }

    /**
     * @param array $params
     * @return string
     */
    public function save(array $params): string
    {
        $language = detectLanguage($params['query']);
        if ($language !== 'pl') {
            $params['query'] = $this->openAI->translateToPolish($params['query']);
        }

        if (!$params['group']) {
            $params['group'] = $this->openAI->categorizeQueryPrompt($params['query']);
        }

        $response = $this->openAI->generateTagsAndTitle($params['query']);

        $resource = new Resource();
        $resource->title = $response->title;
        $resource->content = $params['query'];
        $resource->category = $params['group'];
        $resource->tags = $response->tags;
        $resource->save();

        $text = $resource->content . " " . implode(',', $resource->tags);
        $embedding = $this->openAI->createEmbedding($text);

        $this->vectorDatabase->insertVector($resource->id, $embedding, [
            'id' => $resource->id,
            'category' => $resource->category,
            'tags' => implode(',', $resource->tags)
        ]);
        return "Notatkę zapisano";
    }

    /**
     * @param $params
     * @return string
     * @throws InvalidArgumentException
     */
    public function forget($params): string
    {
        $embedding = $this->openAI->createEmbedding($params['query']);
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
        $action->setMessage($params['query']);
        return $action->execute();
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
        $content .= "###message\n{$$query}";

        $response = $this->openAI->getJson($content);
        $response = returnJson($response);
        return json_decode($response)->action;
    }
}
