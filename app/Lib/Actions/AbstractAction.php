<?php

namespace App\Lib\Actions;

class AbstractAction
{
    public const NAME = 'Abstract Action';
    public const ICON = 'icon';

    public const SHORTCUT = null;

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
}
