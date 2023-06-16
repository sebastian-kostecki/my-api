<?php

namespace App\Lib\Assistant\Actions;

use App\Lib\Connections\OpenAI;
use App\Lib\Connections\Qdrant;
use App\Lib\Interfaces\ActionInterface;
use App\Models\Note;
use Exception;

class SaveNote implements ActionInterface
{
    public static string $name = 'Note';
    public static string $slug = 'add-note';
    public static string $icon = 'fa-regular fa-note-sticky';

    protected OpenAI $openAI;
    protected string $collectionName;
    protected string $text;

    public function __construct(string $collectionName)
    {
        $this->openAI = new OpenAI();
        $this->collectionName = $collectionName;
    }

    /**
     * @return string
     * @throws Exception
     */
    public function execute(): string
    {
        $lines = explode('.', $this->text);

        try {
            foreach ($lines as $line) if ($line) {
                $language = detectLanguage($line);
                if ($language !== 'pl') {
                    $line = $this->openAI->translateToPolish($line);
                }
                $note = Note::create(['content' => trim($line)]);
                $embedding = $this->openAI->createEmbedding($note->content);
                $vectorDatabase = new Qdrant();
                $vectorDatabase->insertVector($note->id, $embedding, [
                    'id' => $note->id,
                    'content' => $note->content
                ]);

            }
            return "Napisz, że tekst został dodany";
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    public function setText(string $text)
    {
        $this->text = $text;
    }
}
