<?php

namespace App\Lib\Assistant\Actions\Assistants;

use App\Enums\Assistant\ChatModel as Model;
use App\Lib\Assistant\Actions\AssistantAction;

class Query
{
    /**
     * @return array{
     *     name: string,
     *     icon: string,
     *     shortcut: string,
     *     model: Model,
     *     system_prompt: string
     * }
     */
    public static function getInitAction(): array
    {
        return [
            'type' => AssistantAction::class,
            'name' => 'Query',
            'icon' => null,
            'shortcut' => null,
            'model' => Model::GPT3,
            'system_prompt' => 'You are a helpful assistant called Ed.',
            'hidden' => true
        ];
    }
}
