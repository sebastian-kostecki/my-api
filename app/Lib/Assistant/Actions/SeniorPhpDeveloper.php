<?php

namespace App\Lib\Assistant\Actions;

use App\Lib\Apis\OpenAI;
use App\Lib\Assistant\AbstractAction;
use App\Lib\Interfaces\ActionInterface;

class SeniorPhpDeveloper extends AbstractAction implements ActionInterface
{
    public static string $name = 'PHP';
    public static string $slug = 'php';
    public static string $icon = 'fa-brands fa-php';
    public static string $shortcut = '';

    const TEMPERATURE = 0.4;

    protected OpenAI $openAI;

    public function __construct()
    {
        $this->openAI = new OpenAI();
    }

    /**
     * @return string
     */
    public function execute(): string
    {
        $params = $this->makeParams();
        return $this->openAI->chat($params);
    }

    /**
     * @return array
     */
    protected function makeParams(): array
    {
        return [
            'model' => $this->getModel(),
            'temperature' => self::TEMPERATURE,
            'messages' => [
                [
                    'role' => 'user',
                    'content' => $this->createSystemPrompt()
                ],
            ],
        ];
    }

    /**
     * @return string
     */
    protected function createSystemPrompt(): string
    {
        $content = $this->getSystemPrompt();
        $content .= "\n### Message\n" . $this->prompt;
        return $content;
    }
}
