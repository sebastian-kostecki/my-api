<?php

namespace App\Lib\Assistant\Actions;

use App\Enums\Assistant\ChatModel;
use App\Lib\Assistant\Assistant;
use App\Lib\Exceptions\ConnectionException;
use JsonException;

class History extends AbstractAction
{
    public const NAME = 'History';
    public const ICON = 'fa-solid fa-clock-rotate-left';

    public Assistant $assistant;

    public static array $configFields = [
        'name' => [
            'name' => 'name',
            'label' => 'Name',
            'type' => 'text',
            'default' => self::NAME
        ],
        'icon' => [
            'name' => 'icon',
            'label' => 'Icon',
            'type' => 'text',
            'default' => self::ICON
        ],
    ];

    public function __construct(Assistant $assistant)
    {
        $this->assistant = $assistant;
    }

    /**
     * @return void
     * @throws ConnectionException
     * @throws JsonException
     */
    public function execute(): void
    {
        $points = $this->getConversations();
        $context = $this->getContext($points);
        $messages = [
            [
                'role' => 'user',
                'content' => "Based on the context given below, answer the question posed. Answer always must be in Polish\n\n"
                    . "###Question: " . $this->assistant->getQuery()
                    . "\n\n###Context: " . $context
            ]
        ];
        $response = $this->assistant->api->chat()->create(ChatModel::GPT3, $messages);
        $this->assistant->setResponse($response->choices[0]->message->content);
    }

    /**
     * @return array
     * @throws ConnectionException
     * @throws JsonException
     */
    private function getConversations(): array
    {
        $query = $this->assistant->getQuery();
        $embeddings = $this->assistant->api->embeddings()->create($query);
        return $this->assistant->vectorDatabase->points()->searchPoints($embeddings, 'conversation');
    }

    /**
     * @param array $points
     * @return string
     */
    private function getContext(array $points): string
    {
        $pointsCollection = collect($points);
        $averageScore = $pointsCollection->avg('score');
        $overScorePoints = $pointsCollection->filter(function ($point) use ($averageScore) {
            return $point->score > $averageScore;
        });
        return $overScorePoints->reduce(function ($carry, $point) {
            return $carry . "\n" . $point->payload->text;
        });
    }
}
