<?php

namespace App\Lib\Assistant\Actions;

use App\Enums\Assistant\ChatModel as Model;

class Query extends DefaultAssistant
{
    public const NAME = 'Query';
    public const MODEL = Model::GPT3;
    public const SYSTEM_PROMPT = 'You are a helpful assistant called Ed.';
    public const HIDDEN = true;
}
