<?php

namespace App\Lib\Actions\AbstractActions;

class AbstractCommandAction extends AbstractAction
{
    public const CONFIG = null;

    protected static array $responses = [
        'Task accomplished!',
        'Mission accomplished!',
        'Job well done!',
        'The ducks are all in a row!',
        'The ship has sailed!',
        'I\'’ve crossed that bridge and burned it!',
        'The cake is baked and ready to be served!',
        'The puzzle is complete, and all the pieces fit!',
    ];

    protected static function getResponse(): string
    {
        $randomKey = array_rand(static::$responses);

        return static::$responses[$randomKey];
    }
}
