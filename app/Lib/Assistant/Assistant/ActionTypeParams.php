<?php

namespace App\Lib\Assistant\Assistant;

use App\Enums\OpenAIModel;

class ActionTypeParams
{
    protected const SYSTEM_PROMPT = <<<EOT
Identify the following query with one of the types below.
Query is for AI Assistant who needs to identify parts of a long-term memory to access the most relevant information.
Pay special attention to distinguish questions from actions.
types: query|save|forget|action
If query is a direct action like 'Add task' or 'Translate text', classify query as 'action'.
If query includes any mention of 'save' or 'notes' or 'memory' or 'link', classify as 'save'.
If query includes any mention of 'forget' or 'remove', classify as 'forget'.
If query doesn't fit to any other category, classify as 'query'.
Focus on the beginning of it. Return plain category name and nothing else.
Examples:
'Zapisz wiadomość' - 'save'.
'Zapisz notatkę' - 'save'.
'Are you Ed?' - query'.
'Co to jest docker?' - query'.
'Zapamiętaj, że jestem programistą.' - 'save'.
'Zapomnij notatkę o poprzednim spotkaniu.' - forget'.
'Dodaj task o fixie do notifications.' - action'.
EOT;

    protected const MODEL = OpenAIModel::GPT4;
    protected const TEMPERATURE = 0.1;

    /**
     * @param string $prompt
     * @return array
     */
    public static function make(string $prompt): array
    {
        $content = self::SYSTEM_PROMPT . "###message\n{$prompt}";
        return [
            'model' => self::MODEL->value,
            'temperature' => self::TEMPERATURE,
            'messages' => [
                [
                    'role' => 'user',
                    'content' => $content
                ],
            ],
        ];
    }
}
