<?php

namespace App\Lib\Assistant\Assistant;

use App\Enums\OpenAIModel;

class CategoryParams
{
    protected const SYSTEM_PROMPT = <<<EOT
Identify the following query with one of the categories below.
Query is for AI Assistant who needs to identify parts of a long-term memory to access the most relevant information.
Pay special attention to distinguish questions from actions.

If query is a direct action like "Dodaj coś" or "Przetłumacz tekst", classify query as "actions".
I query is related directly to the assistant or user, classify as "memories"
If query includes any mention of "notatki", classify as "notes"
If query includes any mention of "wiedza" lub cointains message like "Dodaj do mojej wiedzy", classify as "knowledge"
If query includes any mention of "linki" lub "link", classify as "links"
If query doesn't fit to any other category, classify as "all".

Return plain category name and nothing else.

Examples:
"Jak się masz?" - "all"
"Sprawdź moje notatki o poprzednim spotkaniu." - "notes"
"Jak mam na imię?" - "memories"
"Masz linki na temat LLM?" - "links"
"Sprawdź mój kalendarz." - "actions"
"Dodaj zadanie do pracy." - "actions"
"Dodaj do mojej wiedzy ten tekst." - "knowledge"
"Sprawdź w mojej wiedzy co wiesz o Laravelu?" - "knowledge"
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
