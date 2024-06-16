<?php

namespace App\Lib\Actions;

use App\Models\Action;
use App\Models\Assistant;
use App\Models\Thread;

abstract class AbstractAction
{
    public const NAME = 'Abstract Action';
    public const ICON = 'icon';
    public const SHORTCUT = null;
    public const CONFIG = [
        'temperature' => 0.5,
        'top_p' => 0.5
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

    /**
     * @return string
     */
    public static function getName(): string
    {
        return static::NAME;
    }

    /**
     * @return string
     */
    public static function getIcon(): string
    {
        return static::ICON;
    }

    /**
     * @return string|null
     */
    public static function getShortcut(): ?string
    {
        return static::SHORTCUT;
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

    /**
     * @return bool
     */
    public function isRequireThread(): bool
    {
        return false;
    }
}
