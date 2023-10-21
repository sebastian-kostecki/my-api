<?php

namespace App\Lib\Assistant\Actions;

use App\Enums\Assistant\ChatModel as Model;
use App\Lib\Assistant\Actions\AbstractActions\AbstractPromptAction;
use App\Lib\Interfaces\ActionInterface;

class SeniorPhpDeveloper extends AbstractPromptAction implements ActionInterface
{
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

    /**
     * @return array{
     *     name: string,
     *     icon: string,
     *     shortcut: string,
     *     model: Model
     * }
     */
    public static function getInitAction(): array
    {
        return [
            'name' => 'PHP',
            'icon' => 'fa-brands fa-php',
            'shortcut' => '',
            'model' => Model::GPT3,
            'system_prompt' => self::$systemPrompt
        ];
    }
}
