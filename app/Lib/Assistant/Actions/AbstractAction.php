<?php

namespace App\Lib\Assistant\Actions;

use App\Enums\Assistant\ChatModel;
use App\Models\Action;

abstract class AbstractAction
{
    /**
     * @return ChatModel
     */
    public function getModel(): ChatModel
    {
        return Action::type($this::class)->value('model');
    }

    /**
     * @return string
     */
    public function getSystemPrompt()
    {
        return Action::type($this::class)->value('system_prompt');
    }
}
