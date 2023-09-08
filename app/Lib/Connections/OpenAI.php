<?php

namespace App\Lib\Connections;

use Carbon\Carbon;
use OpenAI\Laravel\Facades\OpenAI as Client;
use OpenAI\Responses\Chat\CreateResponse;

class OpenAI
{
//    public function chat(string $prompt): string
//    {
//        $response = Client::chat()->create([
//            'model' => 'gpt-3.5-turbo',
//            'messages' => [
//                ['role' => 'user', 'content' => $prompt],
//            ],
//        ]);
//        return $response->choices[0]->message->content;
//    }

    public function createEmbedding(string $input)
    {
        $response = Client::embeddings()->create([
            'model' => 'text-embedding-ada-002',
            'input' => $input,
        ]);
        return $response->toArray()['data'][0]['embedding'];
    }

    public function chatConversation(string $prompt)
    {
        return Client::chat()->createStreamed([
            'model' => 'gpt-3.5-turbo',
            'messages' => [
                ['role' => 'user', 'content' => $prompt],
            ],
        ]);
    }

    public function describeIntention(string $prompt)
    {
        $response = Client::chat()->create([
            'model' => 'gpt-4',
            'temperature' => 0,
            'messages' => [
                [
                    'role' => 'user',
                    'content' => "Describe my intention from message below with JSON. Focus on the beginning of it. Always return JSON and nothing more. \ntypes: action|query|memory\nnothing more. types: actionlquerylmemory" .
                        "Example: Napisz wiadomość. {\"type\": \"action\"} Zapisz notatkę {\"type\": \"action\"} Are you Ed? {\"type\": \"query\"} Remember that I'm programmer. {\"type\": \"memory\"} Dodaj task o fixie do notifications. {\"type\": \"action\"}###message\n{$prompt}"
                ],
            ],
        ]);
        return $response->choices[0]->message->content;
    }


    /**
     * @param string $text
     * @return string
     */
    public function getJson(string $text, string $model): string
    {
        $response = Client::chat()->create([
            'model' => $model,
            'temperature' => 0,
            'messages' => [
                [
                    'role' => 'user',
                    'content' => $text
                ],
            ],
        ]);
        return $response->choices[0]->message->content;
    }

    /**
     * @param string $text
     * @return string
     */
    public function translateToPolish(string $text): string
    {
        $response = Client::chat()->create([
            'model' => 'gpt-3.5-turbo',
            'temperature' => 0.8,
            'messages' => [
                [
                    'role' => 'system',
                    'content' => 'Translate the following text into Polish, observing all grammatical, stylistic and orthographic regularities appropriate to the type of text:'
                ],
                [
                    'role' => 'user',
                    'content' => $text
                ],
            ],
        ]);
        return $response->choices[0]->message->content;
    }

    public function generateTagsAndTitle(string $text)
    {
        $content = "Based on the text below, generate a list of tags and title to describe the text with JSON. Always return JSON and nothing more.";
        $content .= "Example:\n";
        $content .= "Zapisz informację o moim spotkaniu z Hubertem: {\"title\": \"Spotkanie z Hubertem\", \"tags\": [\"spotkanie\", \"Hubert\"]}";
        $content .= "Zapamiętaj, że jestem programistą PHP: {\"title\": \"Programista\", \"tags\": [\"programista\", \"PHP\"]}";


        $response = Client::chat()->create([
            'model' => 'gpt-4',
            'temperature' => 0.1,
            'messages' => [
                [
                    'role' => 'system',
                    'content' => $content
                ],
                [
                    'role' => 'user',
                    'content' => $text
                ],
            ],
        ]);
        $response = $response->choices[0]->message->content;
        return json_decode($response);
    }

    public function categorizeQueryPrompt(string $query)
    {
        $prompt = <<<EOD
Identify the following query with one of the categories below.

Query is for AI Assistant who needs to identify parts of a long-term memory to access the most relevant information. Pay special attention to distinguish questions from actions.

If query is a direct action like "Dodaj coś" or "Przetłumacz tekst", classify query as "actions".
I query is related directly to the assistant or user, classify as "memories"
If query includes any mention of "notatki", classify as "notes"
If query includes any mention of "wiedza" lub cointains message like "Dodaj do mojej wiedzy", classify as "knowledge"
If query includes any mention of "linki" lub "link", classify as "links"
If query doesn't fit to any other category, classify as "all".

Return plain category name and nothing else.

examples```
Jak się masz?
all
Sprawdź moje notatki o poprzednim spotkaniu.
notes
Jak mam na imię?
memories
Masz linki na temat LLM?
links
Sprawdź mój kalendarz.
actions
Dodaj zadanie do pracy.
actions
Dodaj do mojej wiedzy ten tekst.
knowledge
Sprawdź w mojej wiedzy co wiesz o Laravelu?
knowledge
```

query```
{$query}
```
EOD;

        $response = Client::chat()->create([
            'model' => 'gpt-4',
            'temperature' => 0.1,
            'messages' => [
                [
                    'role' => 'user',
                    'content' => $prompt
                ],
            ],
        ]);
        return $response->choices[0]->message->content;
    }

    public function updateSystemPrompt(array $context)
    {
        $context = implode("\n", $context);
        $currentDateTime = Carbon::now()->format('Y-m-d H:i');
        $prompt = <<<EOD
You're a helpful assistant. Answer questions as short and concise and as truthfully as possible, based on a context.

Please note that context below may include:
- long-term memory,
- actions you may take,
- user's personal notes and/or links you do have access to.

And you should prioritize this knowledge while answering.

If you don't know the answer say "I don't know" or "I have no information about this" in your own words.

context```
{$context}
```
$currentDateTime
EOD;

        $response = Client::chat()->create([
            'model' => 'gpt-3.5-turbo',
            'temperature' => 0.8,
            'messages' => [
                [
                    'role' => 'user',
                    'content' => $prompt
                ],
            ],
        ]);
        return $response->choices[0]->message->content;
    }

    public function chat(array $messages) {
        $response = Client::chat()->create([
            'model' => 'gpt-4',
            'messages' => $messages
        ]);
        return $response->choices[0]->message->content;
    }


}
