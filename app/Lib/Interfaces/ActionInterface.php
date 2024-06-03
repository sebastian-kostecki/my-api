<?php

namespace App\Lib\Interfaces;

interface ActionInterface
{
    /**
     * @return string
     */
    public static function getName(): string;

    /**
     * @return string
     */
    public static function getIcon(): string;

    /**
     * @return string|null
     */
    public static function getShortcut(): ?string;
}
