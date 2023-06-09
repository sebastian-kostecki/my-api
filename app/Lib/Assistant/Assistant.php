<?php

namespace App\Lib\Assistant;

use App\Lib\Connections\OpenAI;
use App\Lib\Connections\Qdrant;
use App\Models\Action;
use App\Models\Note;
use Illuminate\Support\Facades\Log;
use Mockery\Matcher\Not;
use OpenAI\Laravel\Facades\OpenAI as Client;
use Qdrant\Models\Request\SearchRequest;
use Qdrant\Models\VectorStruct;

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
        $this->vectorDatabase = new Qdrant('test-notes');
    }

    public function execute(string $prompt): void
    {
        $this->prompt = $prompt;
        $this->describeIntention();
        $this->executeByType();
    }

    public function getPrompt(): string
    {
        return $this->response;
    }

    /**
     * @return void
     */
    public function describeIntention(): void
    {
        $content = "Describe my intention from message below with JSON";
        $content .= "Focus on the beginning of it. Always return JSON and nothing more. \n";
        $content .= "types: action|query|memory\n";
        $content .= "Example: Napisz wiadomość. {\"type\": \"action\"}";
        $content .= "Zapisz notatkę {\"type\": \"action\"}";
        $content .= "Are you Ed? {\"type\": \"query\"}";
        $content .= "Remember that I'm programmer. {\"type\": \"memory\"}";
        $content .= "Dodaj task o fixie do notifications. {\"type\": \"action\"}";
        $content .= "###message\n{$this->prompt}";

        $response = $this->openAI->getJson($content);
        $response = returnJson($response);
        $this->type = json_decode($response)->type;
    }

    protected function executeByType()
    {
        switch ($this->type) {
            case 'memory':
                // zapis do pamięći
                break;
            case 'note':
                break;
            case 'action':
                $action = $this->selectAction();
                $actionClass = Action::where('slug', $action)->value('type');
                $action = new $actionClass($this->prompt);
                $this->response = $action->execute();
                break;
            default:
                $type = $this->selectQuery();
                if ($type === 'note') {
                    $embedding = $this->openAI->createEmbedding($this->prompt);
                    $response = $this->vectorDatabase->search($embedding);
                    $context = "Na podstawie poniższego kontekstu odpowiedz na następujące pytanie" . $this->prompt . "\n\n###Kontekst\n";
                    foreach ($response as $vector) {
                        $id = $vector['id'];
                        $notes = Note::whereIn('id', [$id-1, $id, $id+1])->pluck('content')->toArray();
                        $notes = implode("\n", $notes);
                        $context .= $notes;
                    }
                    $this->response = $context;
                } else if ($type === 'memory') {
                    //
                } else {
                    $this->response = $this->prompt;
                }
        }
    }

    protected function selectAction()
    {
        $actions = Action::pluck('slug')->toArray();

        $content = "Describe my intention from message below with JSON";
        $content .= "Focus on the beginning of it. Always return JSON and nothing more. \n";
        $content .= "Actions: " . implode('|', $actions) . "\n";
        $content .= "Example: Dodaj zadanie do pracy o zrobieniu nowego wyglądu strony {\"action\": \"add-work-task\"}";
        $content .= "Zadanie o nauczeniu się Vue.js {\"action\": \"add-private-task\"}";
        $content .= "Zapisz to jako email {\"action\": \"save-email\"}";
        $content .= "###message\n{$this->prompt}";

        $response = $this->openAI->getJson($content);
        $response = returnJson($response);
        return json_decode($response)->action;
    }

    protected function selectQuery()
    {
        $content = "Describe my intention from message below with JSON.";
        $content .= "Focus on the beginning of it. Always return JSON and nothing more. \n";
        $content .= "Types: note|memory|default\n";
        $content .= "If you don't recognise the type then assign a default\n";
        $content .= "Example: Poszukaj w moich notatkach, gdzie leży Lublin? {\"type\": \"note\"}";
        $content .= "Czy pamiętasz, gdzie pracuję? {\"type\": \"memory\"}";
        $content .= "Czym jest docker? {\"type\": \"default\"}";
        $content .= "###message\n{$this->prompt}";

        $response = $this->openAI->getJson($content);
        $response = returnJson($response);
        return json_decode($response)->type;
    }
}
