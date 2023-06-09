<?php

namespace App\Lib\Assistant\Shortcuts;

use App\Lib\Connections\OpenAI;
use App\Lib\Connections\Qdrant;
use App\Models\Note;
use Exception;
use Qdrant\Exception\InvalidArgumentException;

class SaveNote
{
    protected OpenAI $openAI;
    protected string $collectionName;

    public function __construct(string $collectionName)
    {
        $this->openAI = new OpenAI();
        $this->collectionName = $collectionName;
    }

    /**
     * @param string $text
     * @return void
     * @throws Exception
     */
    public function execute(string $text): void
    {
        $lines = explode('.', $text);

        try {
            foreach ($lines as $line) if ($line) {
                $language = detectLanguage($line);
                if ($language !== 'pl') {
                    $line = $this->openAI->translateToPolish($line);
                }
                $note = Note::create(['content' => trim($line)]);
                $embedding = $this->openAI->createEmbedding($note->content);
                $vectorDatabase = new Qdrant($this->collectionName);
                $vectorDatabase->insertVector($note->id, $embedding, [
                    'id' => $note->id,
                    'content' => $note->content
                ]);
            }
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }
}
