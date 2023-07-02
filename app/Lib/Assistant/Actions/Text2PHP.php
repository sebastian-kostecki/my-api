<?php

namespace App\Lib\Assistant\Actions;

use App\Lib\Connections\OpenAI;
use App\Lib\Interfaces\ActionInterface;
use OpenAI\Laravel\Facades\OpenAI as Client;

class Text2PHP implements ActionInterface
{
    public static string $name = 'Text2PHP';
    public static string $slug = 'text-to-php';
    public static string $icon = 'fa-brands fa-php';
    public static string $shortcut = '';

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
    public function setMessage(string $prompt): void
    {
        $this->prompt = $prompt;
    }

    /**
     * @return string
     */
    protected function getSystemPrompt(): string
    {
        $content = "You are building a text-to-code conversion system that generates PHP code, specifically targeting the Laravel framework. ";
        $content .= "Given a user message that describes a programming task in natural language, generate the corresponding PHP code using Laravel conventions and features. ";
        $content .= "The system should be able to handle various types of programming tasks, such as mathematical calculations, string manipulations, file operations, array operations, and database interactions, all within the Laravel framework. ";
        $content .= "Develop a PHP code generator that accurately converts user messages into executable Laravel PHP code. ";
        $content .= "Ensure that the generated code follows Laravel coding standards and utilizes Laravel-specific functionalities where applicable. ";
        $content .= "Return only code without any comments and nothing more. \n";
        $content .= "### Message\n" . $this->prompt;
        return $content;
    }
}
