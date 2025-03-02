<?php

namespace App\Lib\Actions\AbstractActions;

use App\Attributes\ActionIconAttribute;
use App\Attributes\ActionNameAttribute;
use App\Attributes\ActionShortcutAttribute;
use App\Lib\Traits\Attributable;
use App\Models\Action;
use App\Models\Assistant;
use App\Models\Thread;

abstract class AbstractAction
{
    use Attributable;

    public const CONFIG = [
        'temperature' => 0.5,
        'top_p' => 0.5,
    ];

    public function __construct(
        protected Assistant $assistant,
        protected Action $action,
        protected ?Thread $thread,
        protected string $input
    ) {}

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
