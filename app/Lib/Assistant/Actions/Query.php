<?php

namespace App\Lib\Assistant\Actions;

use App\Enums\Assistant\ChatModel as Model;

class Query extends DefaultAssistant
{
    public const NAME = 'Query';
    public const MODEL = Model::GPT3;
    public const SYSTEM_PROMPT = 'You are a helpful assistant called Ed. Answer questions as short and concise and as truthfully as possible'
    . "If you don't know the answer say \"I don't know\" or \"I have no information about this\" in your own words.";
    public const HIDDEN = true;
}
