<?php

namespace App\Lib\Assistant\Actions;

use App\Enums\OpenAIModel;
use App\Lib\Connections\OpenAI;
use App\Lib\Connections\Qdrant;
use App\Lib\Interfaces\ActionInterface;
use App\Models\Resource;
use Exception;

class Remember implements ActionInterface
{
    /**
     * Initial variables for action
     */
    public const EXAMPLE = [
        "Zapamiętaj, że nazywam się Sebastian Kostecki {\"action\": \"". self::class . "\"}",
        "Dodaj do pamięci następujące informacje: Lublin jest miastem wojewódzkim {\"action\": \"". self::class . "\"}"
    ];

    public static string $name = 'Remember';
    public static string $icon = '';
    public static string $shortcut = 'CommandOrControl+Shift+N';
    public static string $model = OpenAIModel::GPT3->value;

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
    public function setPrompt(string $prompt): void
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
            $tags = json_decode($this->openAI->generateTagsAndTitle($this->prompt));

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
