<?php

namespace App\Lib\Assistant;

use App\Lib\Connections\OpenAI;
use App\Models\Action;
use OpenAI\Laravel\Facades\OpenAI as Client;

class Assistant
{
    protected string $prompt;
    protected string $type;

    protected OpenAI $openAI;

    public function __construct()
    {
        $this->openAI = new OpenAI();
    }

    public function execute(string $prompt)
    {
        $this->prompt = $prompt;
        $this->type = $this->describeIntention();
        $this->executeByType();
    }


    /**
     * @param string $prompt
     * @return string
     */
    public function describeIntention(): string
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
        return json_decode($response)->type;
    }

    protected function executeByType()
    {
        switch ($this->type) {
            case 'memory':
                //akcja typu memory
                break;
            case 'action':
                $action = $this->selectAction();
                $actionClass = Action::where('slug', $action)->value('type');
                break;
            default:
                //query
        }
    }

    protected function selectAction()
    {
        $actions = Action::pluck('slug');

        $content = "Describe my intention from message below with JSON";
        $content .= "Focus on the beginning of it. Always return JSON and nothing more. \n";
        $content .= "Actions: " . implode('|', $actions) . "\n";
        $content .= "Example: Dodaj zadanie do pracy o zrobieniu nowego wyglądu strony {\"action\": \"add-work-task\"}";
        $content .= "Zadanie o nauczeniu się Vue.js {\"action\": \"add-private-task\"}";
        $content .= "Zapisz to jako email {\"action\": \"save-email\"}";
        $content .= "###message\n{$this->prompt}";

        $response = $this->openAI->getJson($content);
        return json_decode($response)->action;
    }
}
