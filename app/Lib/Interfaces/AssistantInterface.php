<?php

namespace App\Lib\Interfaces;

interface AssistantInterface
{
    public static function getName(): string;

    public static function getDescription(): string;

    public static function getInstructions(): string;
}
