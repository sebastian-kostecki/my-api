<?php

namespace App\Lib\Actions\AbstractActions;

use App\Lib\Interfaces\Connections\ArtificialIntelligenceInterface;

abstract class AbstractChatAction extends AbstractAction
{
    protected const INSTRUCTIONS = 'Some Instructions';

    public function execute(): string
    {
        $model = $this->assistant->model->name;
        $config = $this->action->config;
        $system = $this->assistant->instructions."\n".static::INSTRUCTIONS;
        $messages = [
            ...$this->thread->getLastMessages(),
            [
                'role' => 'user',
                'content' => $this->input,
            ],
        ];

        /** @var ArtificialIntelligenceInterface $connection */
        $connection = (new $this->assistant->model->type);

        return $connection->chat($model, $messages, $system, $config['temperature'], $config['top_p']);
    }
}
