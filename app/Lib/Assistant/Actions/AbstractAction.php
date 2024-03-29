<?php

namespace App\Lib\Assistant\Actions;

use App\Enums\Assistant\ChatModel;
use App\Lib\Interfaces\ActionInterface;
use App\Lib\Interfaces\AssistantInterface;

abstract class AbstractAction implements ActionInterface
{
    public const NAME = "AbstractAction";
    public const ICON = null;
    public const MODEL = ChatModel::GPT4;
    public const SHORTCUT = null;
    public const INSTRUCTIONS = null;
    public const HIDDEN = false;

    /**
     * @return array{
     *     type: string,
     *     name: string,
     *     icon: string|null,
     *     model: ChatModel,
     *     shortcut: string|null,
     *     instructions: string|null,
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
            'instructions' => static::INSTRUCTIONS,
            'enabled' => true,
            'hidden' => static::HIDDEN ?? false,
            'assistant' => in_array(AssistantInterface::class, class_implements(static::class), true)
        ];
    }

    /**
     * @return array
     */
    public static function getConfigFields(): array
    {
        return static::$configFields;
    }
}
