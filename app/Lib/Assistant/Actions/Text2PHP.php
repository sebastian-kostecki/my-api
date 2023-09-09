<?php

namespace App\Lib\Assistant\Actions;

use App\Enums\OpenAIModel;
use App\Lib\Connections\OpenAI;
use App\Lib\Interfaces\ActionInterface;
use App\Models\Action;
use OpenAI\Laravel\Facades\OpenAI as Client;

class Text2PHP implements ActionInterface
{
    /**
     * Initial variables for action
     */
    public static string $name = 'Text2PHP';
    public static string $icon = 'fa-brands fa-php';
    public static string $shortcut = '';
    public static string $model = OpenAIModel::GPT4->value;


    public static string $systemPrompt = <<<END
You are building a text-to-code conversion system that generates PHP code, specifically targeting the Laravel framework.
Given a user message that describes a programming task in natural language, generate the corresponding PHP code using Laravel conventions and features.
The system should be able to handle various types of programming tasks, such as mathematical calculations, string manipulations, file operations, array operations, and database interactions, all within the Laravel framework.
Develop a PHP code generator that accurately converts user messages into executable Laravel PHP code.
Ensure that the generated code follows Laravel coding standards and utilizes Laravel-specific functionalities where applicable.
Return only code without any comments and nothing more.
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
        $response = Client::chat()->create([
            'model' => 'gpt-3.5-turbo',
            'temperature' => 0.1,
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
