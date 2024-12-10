<?php

namespace App\Lib\Actions;

use App\Lib\Interfaces\ActionInterface;
use App\Lib\Traits\ShouldThread;

class BugScanner extends AbstractChatAction implements ActionInterface
{
    use ShouldThread;

    public const NAME = 'Bug Scanner';

    public const ICON = 'fa-solid fa-bug';

    public const SHORTCUT = 'CommandOrControl+Shift+B';

    public const CONFIG = [
        'temperature' => 0.3,
        'top_p' => 0.2,
    ];

    protected const INSTRUCTIONS = 'You will be provided with a piece of code, and your task is to find and fix bugs in it.';
}
