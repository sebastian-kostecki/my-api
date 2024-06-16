<?php

namespace App\Lib\Interfaces;

interface AssistantInterface
{
    /**
     * @return string
     */
    public static function getName(): string;

    /**
     * @return string
     */
    public static function getDescription(): string;

    /**
     * @return string
     */
    public static function getInstructions(): string;
}
