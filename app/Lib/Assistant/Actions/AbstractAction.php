<?php

namespace App\Lib\Assistant\Actions;

use App\Enums\Assistant\ChatModel;
use App\Lib\Interfaces\AssistantInterface;

abstract class AbstractAction
{
    public const NAME = "AbstractAction";
    public const ICON = null;
    public const MODEL = ChatModel::GPT4;
    public const SHORTCUT = null;
    public const SYSTEM_PROMPT = null;
    public const HIDDEN = false;

    /**
     * @return array{
     *     type: string,
     *     name: string,
     *     icon: string|null,
     *     model: ChatModel,
     *     shortcut: string|null,
     *     system_prompt: string|null,
     *     enabled: bool,
     *     hidden: bool,
     *     assistant: bool
     * }
     */
    public static function getInit(): array
    {
        return [
            'type' => static::class,
            'name' => static::NAME,
            'icon' => static::ICON,
            'model' => static::MODEL,
            'shortcut' => static::SHORTCUT,
            'system_prompt' => static::SYSTEM_PROMPT,
            'enabled' => true,
            'hidden' => static::HIDDEN ?? false,
            'assistant' => in_array(AssistantInterface::class, class_implements(static::class), true)
        ];
    }
}
