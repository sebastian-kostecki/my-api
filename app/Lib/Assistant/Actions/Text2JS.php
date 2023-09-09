<?php

namespace App\Lib\Assistant\Actions;

use App\Enums\OpenAIModel;
use App\Lib\Connections\OpenAI;
use App\Lib\Interfaces\ActionInterface;
use App\Models\Action;
use OpenAI\Laravel\Facades\OpenAI as Client;

class Text2JS implements ActionInterface
{
    /**
     * Initial variables for action
     */
    public const EXAMPLE = [
        "Zamień następujący tekst na kod Javascript {\"action\": \"" . self::class . "\"}"
    ];
    public static string $name = 'Text2JS';
    public static string $icon = 'fa-brands fa-square-js';
    public static string $shortcut = '';
    public static string $model = OpenAIModel::GPT4->value;

    public static string $systemPrompt = <<<END
You are developing a system that generates JavaScript code based on user input.
Given a user's text description of a programming task or desired functionality, generate high-quality JavaScript code using JavaScript.
The system should accurately understand the user's requirements and provide code solutions that follow best practices and JavaScript conventions.
Consider the user's description and provide well-structured, efficient, and maintainable code snippets.
Help the user achieve their desired functionality in JavaScript by generating code that demonstrates good programming practices, follows standard JavaScript patterns, and adheres to best practices for readability, performance, and maintainability.
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
        return "```\n" . $response->choices[0]->message->content . "\n```";
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
