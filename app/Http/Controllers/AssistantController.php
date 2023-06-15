<?php

namespace App\Http\Controllers;

use App\Lib\Assistant\Assistant;
use App\Lib\Connections\OpenAI;
use App\Lib\Connections\Pinecone;
use App\Models\Action;
use App\Models\Note;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class AssistantController extends Controller
{
    public function __construct(
        protected OpenAI $openAI,
        protected Assistant $assistant
    ) {}
    public function rememberText(string $url)
    {
        $response = Http::get('https://relaxed-boba-bcf830.netlify.app/.netlify/functions/unfluff?url=' . $url);
        $text = $response->json();

        $lines = explode('.', $text['text']);

        foreach ($lines as $line) if ($line) {
            $note = Note::create(['content' => trim($line)]);

            $openAI = new OpenAI();
            $pinecone = new Pinecone('notes-for-exercises');

            $embedding = $openAI->createEmbedding($note->content);
            $pinecone->upsertVectors($note->id, $embedding, [
                'tag' => 'notes'
            ]);
        }
    }

    public function makeQuestion(string $question)
    {
        $openAI = new OpenAI();
        $pinecone = new Pinecone('notes-for-exercises');

        $embedding = $openAI->createEmbedding($question);
        $response = $pinecone->queryVectors($embedding);
        $ids = array_map(function ($item) {
            return $item['id'];
        }, $response['matches']);

        $notes = Note::whereIn('id', $ids)->get();
        $notes = $notes->map(function ($note) {
            return $note->content;
        })->toArray();
        $context = implode('/n', $notes);

        $response = $openAI->chat('Na podstawie poniższego kontekstu odpowiedz na pytanie:' . $question . '\n\nContext:\n' . $context);
        echo $response;
    }
//
//    public function chat(Request $request)
//    {
//        $params = $request->validate([
//            'prompt' => 'required|string'
//        ]);
//
//        $openAI = new OpenAI();
//        $response = $openAI->chat($params['prompt']);
//        return new JsonResponse($response);
//    }

    /**
     * Send prompt to OpenAI
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function ask(Request $request): JsonResponse
    {
        $params = $request->validate([
            'prompt' => 'required|string'
        ]);

        $this->assistant->execute($params['prompt']);
        $response = $this->assistant->getPrompt();


        $options = [
            'max_tokens' => 1500,
            'temperature' => 0.8,
            'model' => 'gpt-3.5-turbo'
        ];

        return new JsonResponse([
            'messages' => [
                [
                    'role' => 'system',
                    'content' => 'You are helpful assistant named Ed'
                ],
                [
                    'role' => 'user',
                    'content' => $response
                ]
            ],
            'options' => $options
        ]);
    }

    /**
     * Process answer from OpenAI
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function get(Request $request): JsonResponse
    {
        $params = $request->validate([
            'answer' => 'required|string'
        ]);

        return new JsonResponse([
            'data' => $params['answer']
        ]);
    }

    /**
     * @return JsonResponse
     */
    public function getActions(): JsonResponse
    {
        $actions = Action::all();
        return new JsonResponse([
            'data' => $actions
        ]);
    }
}
