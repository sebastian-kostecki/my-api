<?php

namespace App\Lib\Assistant\Actions;

use App\Models\Action;

abstract class AbstractAction
{
    protected string $prompt;

    /**
     * @return string
     */
    public function getModel(): string
    {
        $model = Action::class($this::class)->value('model');
        return $model->value;
    }

    /**
     * @param string $prompt
     * @return void
     */
    public function setPrompt(string $prompt): void
    {
        $this->prompt = $prompt;
    }

    /**
     * @return string
     */
    protected function getSystemPrompt(): string
    {
        return Action::class($this::class)->value('prompt');
    }
}
