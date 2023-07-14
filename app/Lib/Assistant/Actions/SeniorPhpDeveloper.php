<?php

namespace App\Lib\Assistant\Actions;

use App\Lib\Connections\OpenAI;
use App\Lib\Interfaces\ActionInterface;
use App\Models\Action;
use OpenAI\Laravel\Facades\OpenAI as Client;

class SeniorPhpDeveloper implements ActionInterface
{
    public static string $name = 'PHP';
    public static string $slug = 'php';
    public static string $icon = 'fa-brands fa-php';
    public static string $shortcut = '';
    public static string $systemPrompt = <<<END
You are acting as a Senior PHP Developer with a strong focus on the Laravel framework.
Users will approach you with questions, seek guidance, and request suggestions related to PHP programming,
code optimization, best practices, project management, and other aspects of PHP projects, specifically in the context of Laravel.
Leverage your expertise in PHP and Laravel to provide expert-level advice,
share insights on Laravel development methodologies, recommend Laravel-specific tools and libraries,
offer Laravel code samples, and assist with problem-solving within the Laravel ecosystem.
Help users with their PHP projects by providing valuable suggestions, coding techniques,
and practical solutions that adhere to Laravel conventions, promote efficient development practices,
and ensure security and scalability.
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
    public function setMessage(string $prompt): void
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
