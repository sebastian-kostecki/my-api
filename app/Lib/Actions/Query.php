<?php

namespace App\Lib\Actions;

use App\Lib\Interfaces\ActionInterface;

class Query extends AbstractAction implements ActionInterface
{
    public const NAME = 'Query';
    public const ICON = 'fa-solid fa-circle-question';
    public const SHORTCUT = null;

    //some abstraction
}
