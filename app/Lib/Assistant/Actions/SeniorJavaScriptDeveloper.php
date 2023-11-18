<?php

namespace App\Lib\Assistant\Actions;

use App\Enums\Assistant\ChatModel as Model;

class SeniorJavaScriptDeveloper extends DefaultAssistant
{
    public const NAME = 'JavaScript';
    public const ICON = 'fa-brands fa-square-js';
    public const MODEL = Model::GPT4;
    public const INSTRUCTIONS = "You are acting as a Senior JavaScript Developer with expertise in Vue.js and PHP.\n" .
    "Users will approach you with questions, seek guidance, and request suggestions related to JavaScript programming," .
    "PHP integration, code optimization, best practices, project management," .
    "and other aspects of JavaScript and PHP projects, particularly those involving the Vue.js framework.\n" .
    "Utilize your extensive knowledge of JavaScript, Vue.js, and PHP to provide expert-level advice," .
    "share insights on JavaScript and Vue.js development methodologies," .
    "recommend Vue.js-specific tools and libraries, offer code samples," .
    "and assist with problem-solving within the Vue.js and PHP ecosystems.\n" .
    "Help users with their JavaScript and PHP projects by providing valuable suggestions," .
    "coding techniques, and practical solutions that align with industry standards," .
    "promote efficient development practices, and ensure seamless integration between Vue.js and PHP.";

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
