<?php

namespace App\Lib\Actions;

use App\Lib\Interfaces\ActionInterface;
use App\Lib\Traits\ShouldThread;

class Query extends AbstractAction implements ActionInterface
{
    use ShouldThread;

    public const NAME = 'Query';
    public const ICON = 'fa-solid fa-circle-question';
    public const SHORTCUT = null;
    public const CONFIG = [
        'temperature' => 0.5,
        'top_p' => 0.5
    ];

    public function execute(): string
    {
        $model = $this->assistant->model->name;
        $config = $this->action->config;
        $messages = [
            [
                'role' => 'system',
                'content' => $this->assistant->instructions
            ],
            ...$this->thread->getLastMessages(),
            [
                'role' => 'user',
                'content' => $this->input
            ]
        ];

        return $this->assistant->model->type::factory()->chat($model, $messages, $config['temperature'], $config['top_p']);
    }


    //bug scanner
    //history
    //refactoring
    //code generator
    //copywriter - hosting
    //clean mailer


    //panelalpha assistant - Konrad :)
}
