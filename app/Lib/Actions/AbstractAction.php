<?php

namespace App\Lib\Actions;

use App\Attributes\ActionIconAttribute;
use App\Attributes\ActionNameAttribute;
use App\Attributes\ActionShortcutAttribute;
use App\Models\Action;
use App\Models\Assistant;
use App\Models\Thread;
use ReflectionClass;

abstract class AbstractAction
{
    public const CONFIG = [
        'temperature' => 0.5,
        'top_p' => 0.5,
    ];

    protected Assistant $assistant;

    protected Action $action;

    protected ?Thread $thread;

    protected string $input;

    public function __construct(Assistant $assistant, Action $action, ?Thread $thread, string $input)
    {
        $this->assistant = $assistant;
        $this->action = $action;
        $this->thread = $thread;
        $this->input = $input;
    }

    public static function getAttribute(string $attributeClass): mixed
    {
        $reflection = new ReflectionClass(static::class);
        $attributes = $reflection->getAttributes($attributeClass);

        foreach ($attributes as $attribute) {
            $values = $attribute->getArguments();

            return $values[0];
        }

        return null;
    }

    public static function getName(): string
    {
        return static::getAttribute(ActionNameAttribute::class);
    }

    public static function getIcon(): string
    {
        return static::getAttribute(ActionIconAttribute::class);
    }

    public static function getShortcut(): ?string
    {
        return static::getAttribute(ActionShortcutAttribute::class);
    }

    /**
     * @return array{
     *     temperature: float,
     *     top_p: float
     * }|null
     */
    public static function getConfig(): ?array
    {
        return static::CONFIG;
    }

    public function isRequireThread(): bool
    {
        return false;
    }
}
