<?php

namespace App\Http\Controllers;

use App\Lib\Apis\OpenAI;
use Illuminate\Http\Request;
use JsonException;

class TestController extends Controller
{
    /**
     * @throws JsonException
     */
    public function request(Request $request)
    {
        $question = $request->input('question');

        $messages = [
            [
                'role' => 'system',
                'content' => 'Odpowiedz na pytanie i zwróć odpowiedz'
            ],
            [
                'role' => 'user',
                'content' => $question
            ]
        ];

        $openAi = OpenAI::factory();
        $result = $openAi->completion()->request('gpt-3.5-turbo', $messages);

        return response()->json([
            'result' => $result
        ]);

    }
}
