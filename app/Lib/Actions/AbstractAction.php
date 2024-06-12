<?php

namespace App\Lib\Actions;

use App\Models\Assistant;
use App\Models\Thread;

class AbstractAction
{
    public const NAME = 'Abstract Action';
    public const ICON = 'icon';

    public const SHORTCUT = null;

    protected Assistant $assistant;
    protected ?Thread $thread;
    protected string $input;

    public function __construct(Assistant $assistant, ?Thread $thread, string $input)
    {
        $this->assistant = $assistant;
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
     * @return bool
     */
    public function isRequireThread(): bool
    {
        return false;
    }
}