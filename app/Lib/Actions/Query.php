<?php

namespace App\Lib\Actions;

use App\Attributes\ActionIconAttribute;
use App\Attributes\ActionNameAttribute;
use App\Lib\Actions\AbstractActions\AbstractAction;
use App\Lib\Interfaces\ActionInterface;
use App\Lib\Interfaces\Connections\ArtificialIntelligenceInterface;
use App\Lib\Traits\ShouldThread;

#[ActionNameAttribute('Query')]
#[ActionIconAttribute('fa-solid fa-circle-question')]
class Query extends AbstractAction implements ActionInterface
{
    use ShouldThread;

    public const CONFIG = [
        'temperature' => 0.5,
        'top_p' => 0.5,
    ];

    public function execute(): string
    {
        $model = $this->assistant->model->name;
        $config = $this->action->config;
        $system = $this->assistant->instructions;
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
