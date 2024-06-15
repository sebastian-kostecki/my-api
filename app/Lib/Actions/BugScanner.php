<?php

namespace App\Lib\Actions;

use App\Lib\Interfaces\ActionInterface;
use App\Lib\Traits\ShouldThread;

class BugScanner extends AbstractAction implements ActionInterface
{
    use ShouldThread;

    public const NAME = 'Bug Scanner';
    public const ICON = 'fa-solid fa-bug';
    public const SHORTCUT = 'CommandOrControl+Shift+B';
    public const CONFIG = [
        'temperature' => 0.3,
        'top_p' => 0.2
    ];

    private const INSTRUCTIONS = "You will be provided with a piece of code, and your task is to find and fix bugs in it.";

    public function execute(): string
    {
        $model = $this->assistant->model->name;
        $config = $this->action->config;
        $messages = [
            [
                'role' => 'system',
                'content' => $this->assistant->instructions . "\n" . self::INSTRUCTIONS,
            ],
            ...$this->thread->getLastMessages(),
            [
                'role' => 'user',
                'content' => $this->input
            ]
        ];

        return $this->assistant->model->type::factory()->chat($model, $messages, $config['temperature'], $config['top_p']);
    }
}
