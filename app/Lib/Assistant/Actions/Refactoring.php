<?php

namespace App\Lib\Assistant\Actions;

use App\Enums\Assistant\ChatModel as Model;

class Refactoring extends DefaultAssistant
{
    public const NAME = 'Refactor';
    public const ICON = 'fa-solid fa-file-code';
    public const SHORTCUT = 'CommandOrControl+Shift+';
    public const MODEL = Model::GPT3;

    public const INSTRUCTIONS = 'Your role is to assess whether a given piece of code has undergone proper refactoring. ' .
    'Users will provide sections of code, and you need to evaluate them for adherence to refactoring principles.' .
    'Please provide a segment of code in the input, and in the output, present detailed information about the code\'s refactoring status. ' .
    'Include insights into improvements made in terms of code readability, maintainability, and efficiency. ' .
    'Additionally, provide a refactored version of the input code, demonstrating how it could be improved. ' .
    'Identify and comment on instances where the code could benefit from further refactoring or where established best practices have been followed.' .
    'Offer specific suggestions for improvements, considering factors such as code structure, naming conventions, and the application of design patterns. ' .
    'Your goal is to guide users towards code that is not only functional but also well-organized and easy to maintain. ' .
    'Please provide your feedback and suggestions in a constructive manner, highlighting areas of improvement and explaining the rationale behind your recommendations.' .
    'Feel free to customize this prompt based on specific criteria or principles you want to emphasize during the refactoring assessment.' .
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
