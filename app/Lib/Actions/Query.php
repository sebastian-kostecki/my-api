<?php

namespace App\Lib\Actions;

use App\Lib\Interfaces\ActionInterface;

class Query extends AbstractAction implements ActionInterface
{
    public const NAME = 'Query';
    public const ICON = 'fa-solid fa-circle-question';
    public const SHORTCUT = null;

    public function execute(): string
    {
        $model = $this->assistant->model->name;
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

        return $this->assistant->model->type::factory()->completion($model, $messages);
    }
}
