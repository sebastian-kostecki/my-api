<?php

namespace App\Lib\Assistants;

use App\Lib\Interfaces\AssistantInterface;

class DefaultAssistant extends AbstractAssistant implements AssistantInterface
{
    public const NAME = 'Ed';

    public const DESCRIPTION = 'Default Assistant';

    public const INSTRUCTIONS = 'You are a helpful assistant called Ed. Answer questions as short and concise and as truthfully as possible'
        ."If you don't know the answer say \"I don't know\" or \"I have no information about this\" in your own words. Response only in Polish.";
}
