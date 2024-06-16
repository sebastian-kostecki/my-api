<?php

namespace App\Lib\Actions;

abstract class AbstractChatAction extends AbstractAction
{
    protected const INSTRUCTIONS = "Some Instructions";

    /**
     * @return string
     */
    public function execute(): string
    {
        $model = $this->assistant->model->name;
        $config = $this->action->config;
        $messages = [
            [
                'role' => 'system',
                'content' => $this->assistant->instructions . "\n" . static::INSTRUCTIONS,
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
