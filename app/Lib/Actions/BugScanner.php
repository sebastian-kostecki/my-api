<?php

namespace App\Lib\Actions;

use App\Attributes\ActionIconAttribute;
use App\Attributes\ActionNameAttribute;
use App\Attributes\ActionShortcutAttribute;
use App\Lib\Interfaces\ActionInterface;
use App\Lib\Traits\ShouldThread;

#[ActionNameAttribute('Bug Scanner')]
#[ActionIconAttribute('fa-solid fa-bug')]
#[ActionShortcutAttribute('CommandOrControl+Shift+B')]
class BugScanner extends AbstractChatAction implements ActionInterface
{
    use ShouldThread;

    public const CONFIG = [
        'temperature' => 0.3,
        'top_p' => 0.2,
    ];

    protected const INSTRUCTIONS = 'You will be provided with a piece of code, and your task is to find and fix bugs in it.';
}
