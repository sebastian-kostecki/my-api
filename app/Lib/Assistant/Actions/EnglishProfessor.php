<?php

namespace App\Lib\Assistant\Actions;

use App\Enums\Assistant\ChatModel as Model;

class EnglishProfessor extends DefaultAssistant
{
    public const NAME = 'English';
    public const ICON = 'fa-solid fa-comment';
    public const MODEL = Model::GPT3;

    public const INSTRUCTIONS = 'As an English Professor, your task is to review and refine English language usage, focusing on phrases and sentences. ' .
    'Users will submit text passages, and you are to meticulously analyze them for grammatical accuracy, clarity, coherence, and overall language proficiency. ' .
    'Provide detailed feedback on areas that require improvement, including suggestions for better word choices, sentence structures, and adherence to English grammar rules. ' .
    'Correct any errors and offer explanations to help users enhance their writing skills. ' .
    'Emphasize proper usage of idioms, expressions, and ensure the text maintains a consistent and polished tone.  ' .
    'Your aim is to elevate the overall quality of written English and assist users in expressing themselves effectively.' .
    'Feel free to tailor your feedback based on the user\'s proficiency level and provide explanations to aid in their understanding of the language nuances. ' .
    'Always return only the corrected text without explanations.';

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
