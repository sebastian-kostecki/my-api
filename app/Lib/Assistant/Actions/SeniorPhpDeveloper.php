<?php

namespace App\Lib\Assistant\Actions;

use App\Enums\Assistant\ChatModel;
use App\Enums\Assistant\ChatModel as Model;

class SeniorPhpDeveloper extends DefaultAssistant
{
    public const NAME = 'PHP';
    public const ICON = 'fa-brands fa-php';
    public const MODEL = Model::GPT4;
    public const INSTRUCTIONS = "You are acting as a Senior PHP Developer with a strong focus on the Laravel framework.\n" .
    "Users will approach you with questions, seek guidance, and request suggestions related to PHP programming," .
    "code optimization, best practices, project management, and other aspects of PHP projects, specifically in the context of Laravel.\n" .
    "Leverage your expertise in PHP and Laravel to provide expert-level advice," .
    "share insights on Laravel development methodologies, recommend Laravel-specific tools and libraries," .
    "offer Laravel code samples, and assist with problem-solving within the Laravel ecosystem.\n" .
    "Help users with their PHP projects by providing valuable suggestions, coding techniques," .
    "and practical solutions that adhere to Laravel conventions, promote efficient development practices, and ensure security and scalability.";

    /**
     * @var array
     */
    public static array $configFields = [
        'name' => [
            'name' => 'name',
            'label' => 'Name',
            'type' => 'text',
            'default' => self::NAME
        ],
        'icon' => [
            'name' => 'icon',
            'label' => 'Icon',
            'type' => 'text',
            'default' => self::ICON
        ],
        'instructions' => [
            'name' => 'instructions',
            'label' => 'Instructions',
            'type' => 'textarea',
            'default' => self::INSTRUCTIONS
        ],
        'model' => [
            'name' => 'model',
            'label' => 'Model',
            'type' => 'model',
            'default' => self::MODEL
        ]
    ];
}
