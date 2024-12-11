<?php

namespace App\Lib\Assistants;

class AbstractAssistant
{
    public const NAME = 'John Doe';

    public const DESCRIPTION = 'Abstract Assistant';

    public const INSTRUCTIONS = 'Some instructions';

    public static function getName(): string
    {
        return static::NAME;
    }

    public static function getDescription(): string
    {
        return static::DESCRIPTION;
    }

    public static function getInstructions(): string
    {
        return static::INSTRUCTIONS;
    }
}
