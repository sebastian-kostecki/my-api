<?php

namespace App\Lib\Assistant\Actions;

use App\Lib\Connections\OpenAI;
use App\Lib\Connections\Qdrant;
use App\Lib\Interfaces\ActionInterface;
use App\Models\Resource;
use Exception;

class SaveNote implements ActionInterface
{
    public static string $name = 'Add Note';
    public static string $slug = 'add-note';
    public static string $icon = 'fa-regular fa-note-sticky';

    protected OpenAI $openAI;
    protected string $prompt;

    public function __construct()
    {
        $this->openAI = new OpenAI();
    }

    /**
     * @param string $prompt
     * @return void
     */
    public function setMessage(string $prompt): void
    {
        $this->prompt = $prompt;
    }

    /**
     * @return string
     * @throws Exception
     */
    public function execute(): string
    {
        try {
            $language = detectLanguage($this->prompt);
            if ($language !== 'pl') {
                $this->prompt = $this->openAI->translateToPolish($this->prompt);
            }
            $tags = json_decode($this->openAI->generateTags($this->prompt));

            $resource = new Resource();
            $resource->content = $this->prompt;
            $resource->category = 'notes';
            $resource->tags = $tags->tags;
            $resource->save();

            $text = $resource->content . " " . implode(',', $resource->tags);
            $embedding = $this->openAI->createEmbedding($text);

            $vectorDatabase = new Qdrant('test');
            $vectorDatabase->insertVector($resource->id, $embedding, [
                'id' => $resource->id,
                'tags' => implode(',', $resource->tags)
            ]);

            return "Notatka została dodana";
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }
}
