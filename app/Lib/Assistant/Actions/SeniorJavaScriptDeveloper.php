<?php

namespace App\Lib\Assistant\Actions;

use App\Enums\OpenAIModel;
use App\Lib\Connections\OpenAI;
use App\Lib\Interfaces\ActionInterface;
use App\Models\Action;
use OpenAI\Laravel\Facades\OpenAI as Client;

class SeniorJavaScriptDeveloper implements ActionInterface
{
    /**
     * Initial variables for action
     */
    public static string $name = 'JavaScript';
    public static string $icon = 'fa-brands fa-square-js';
    public static string $shortcut = '';
    public static string $model = OpenAIModel::GPT3->value;

    
    public static string $systemPrompt = <<<END
You are acting as a Senior JavaScript Developer with expertise in Vue.js and PHP.
Users will approach you with questions, seek guidance, and request suggestions related to JavaScript programming,
PHP integration, code optimization, best practices, project management,
and other aspects of JavaScript and PHP projects, particularly those involving the Vue.js framework.
Utilize your extensive knowledge of JavaScript, Vue.js, and PHP to provide expert-level advice,
share insights on JavaScript and Vue.js development methodologies,
recommend Vue.js-specific tools and libraries, offer code samples,
and assist with problem-solving within the Vue.js and PHP ecosystems.
Help users with their JavaScript and PHP projects by providing valuable suggestions,
coding techniques, and practical solutions that align with industry standards,
promote efficient development practices, and ensure seamless integration between Vue.js and PHP.
END;


    protected OpenAI $openAI;
    protected string $prompt;

    public function __construct()
    {
        $this->openAI = new OpenAI();
    }

    /**
     * @return string
     */
    public function execute(): string
    {
        //dodać ostatnie wiadomości z pięciu minut

        $response = Client::chat()->create([
            'model' => 'gpt-3.5-turbo',
            'temperature' => 0.5,
            'messages' => [
                [
                    'role' => 'user',
                    'content' => $this->getSystemPrompt()
                ],
            ],
        ]);
        return $response->choices[0]->message->content;
    }

    /**
     * @param string $prompt
     * @return void
     */
    public function setPrompt(string $prompt): void
    {
        $this->prompt = $prompt;
    }

    /**
     * @return string
     */
    protected function getSystemPrompt(): string
    {
        $action = Action::where('slug', self::$slug)->first();

        $content = $action->prompt;
        $content .= "\n### Message\n" . $this->prompt;
        return $content;
    }
}
