<?php

namespace App\Http\Controllers;

use App\Lib\Connections\OpenAI;
use App\Lib\Connections\Pinecone;
use App\Models\Note;;
use Illuminate\Support\Facades\Http;

class AssistantController extends Controller
{
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
        },$response['matches']);

        $notes = Note::whereIn('id', $ids)->get();
        $notes = $notes->map(function ($note) {
            return $note->content;
        })->toArray();
        $context = implode('/n', $notes);

        $response = $openAI->chat('Na podstawie poniższego kontekstu odpowiedz na pytanie:' . $question . '\n\nContext:\n' . $context);
        echo $response;
    }
}
