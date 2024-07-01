<?php

namespace App\Lib\Actions;

use App\Lib\Interfaces\ActionInterface;
use App\Lib\Interfaces\Connections\ArtificialIntelligenceInterface;
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

    /**
     * @return string
     */
    public function execute(): string
    {
        $model = $this->assistant->model->name;
        $config = $this->action->config;
        $system = $this->assistant->instructions;
        $messages = [
            ...$this->thread->getLastMessages(),
            [
                'role' => 'user',
                'content' => $this->input
            ]
        ];

        /** @var ArtificialIntelligenceInterface $connection */
        $connection = (new $this->assistant->model->type);
        return $connection->chat($model, $messages, $system, $config['temperature'], $config['top_p']);
    }
}
