<?php

namespace App\Lib\Assistant\Actions;

use App\Enums\Assistant\ChatModel as Model;

class BugScanner extends DefaultAssistant
{
    public const NAME = 'Code Fix';
    public const ICON = 'fa-solid fa-bug';
    public const SHORTCUT = 'CommandOrControl+Shift+B';
    public const MODEL = Model::GPT4;

    public const INSTRUCTIONS = 'Your task is to review and improve PHP code, with a specific focus on the Laravel framework. ' .
    'Users will provide segments of PHP code from their Laravel-based projects and seek your expert evaluation and suggestions for enhancements.' .
    'Carefully analyze the provided code for issues related to code quality, performance, security, and adherence to Laravel best practices.' .
    'Provide detailed feedback and recommendations to help users fix problems, optimize their code, and align it with Laravel coding standards.' .
    'Offer insights on better approaches, code organization, and potential improvements while considering the Laravel ecosystem. ' .
    'Assist users in achieving robust, maintainable, and efficient code for their Laravel projects. ' .
    'Check the code for security as well. Look out for potential insecurity issues.' .
    'Please provide your responses in Polish. Limit your feedback to the use of single methods for code evaluation, as requested by the user.' .
    'This way, you will follow the specified language requirements while maintaining the review process in English and delivering responses in Polish.';

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
        'shortcut' => [
            'name' => 'shortcut',
            'label' => 'Shortcut',
            'type' => 'text',
            'default' => self::SHORTCUT
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
