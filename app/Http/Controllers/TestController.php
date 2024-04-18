<?php

namespace App\Http\Controllers;

use App\Lib\Apis\OpenAI;
use App\Lib\Connections\Qdrant;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use JsonException;

class TestController extends Controller
{
    /**
     * @throws JsonException
     */
    public function request(Request $request)
    {
        $question = $request->input('question');

        $type = $this->getType($question);

        $openAi = OpenAI::factory();
        $qdrant = Qdrant::factory();

        if ($type === 'question') {
            $embedding = $openAi->embeddings()->create($question);
            $data = $qdrant->points()->search('ai-devs', $embedding);

            $context = array_map(function ($item) {
                return $item->payload->text;
            }, $data);
            $result = $this->getAnswer($question, $context);
        } else {
            $embedding = $openAi->embeddings()->create($question);
            $point = [
                "id" => Str::uuid()->toString(),
                "vector" => $embedding,
                "payload" => [
                    "text" => $question
                ]
            ];
            $qdrant->points()->create('ai-devs', $point);

            $result = "Przyjąłem";
        }

        return response()->json([
            'reply' => $result
        ]);
    }

    private function getType(string $question): string
    {
        $messages = [
            [
                'role' => 'system',
                'content' => "Zastanów się nad odpowiedzią użytkownika. Zwróć w formacie json typ odpowiedzi.\nMożliwe typy: question|information.\nExamples: Czy mieszkam w Lublinie? {\"type\":\"question\"}\nMieszkam w Lublinie. {\"type\":\"information\"}"
            ],
            [
                'role' => 'user',
                'content' => $question
            ]
        ];
        $params = [
            'response_format' => [
                'type' => 'json_object'
            ]
        ];

        $openAi = OpenAI::factory();
        $response = $openAi->completion()->request('gpt-3.5-turbo', $messages, $params);

        return json_decode($response)->type;
    }

    private function getAnswer(string $question, array $context)
    {
        $context = implode("\n", $context);

        $messages = [
            [
                'role' => 'system',
                'content' => "Na podstawie kontekstu oraz własnej wiedzy odpowiedz na postawione pytanie.\nPytanie: {$question}\n\nKontekst###\n$context"
            ],
            [
                'role' => 'user',
                'content' => $question
            ]
        ];

        $openAi = OpenAI::factory();
        return $openAi->completion()->request('gpt-3.5-turbo', $messages);
    }
}
