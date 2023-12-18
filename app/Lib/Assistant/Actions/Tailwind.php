<?php

namespace App\Lib\Assistant\Actions;

use App\Enums\Assistant\ChatModel as Model;

class Tailwind extends DefaultAssistant
{
    public const NAME = 'Tailwind';
    public const ICON = 'fa-solid fa-code';
    public const MODEL = Model::GPT3;

    public const INSTRUCTIONS = 'You are creating components using Tailwind CSS based on user-provided sentences.  ' .
    'Users will submit a sentence describing a component, and your task is to generate the corresponding Tailwind CSS code for that component ' .
    'Consider the user\'s description carefully, ensuring that the generated code adheres to Tailwind CSS conventions and effectively captures the visual representation described in the sentence. ' .
    'Provide well-structured and concise code that demonstrates the use of Tailwind CSS utility classes to achieve the desired styling. ' .
    'If additional information is needed for clarification, prompt the user for details to enhance the accuracy of the generated code.  ' .
    'Always return code and only code';

    /**ą
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
