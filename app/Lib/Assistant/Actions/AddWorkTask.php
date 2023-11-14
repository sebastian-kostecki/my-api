<?php

namespace App\Lib\Assistant\Actions;

use App\Enums\Assistant\ChatModel as Model;
use App\Lib\Assistant\Assistant;
use App\Lib\Connections\Notion\PanelAlphaIssuesTable;
use App\Lib\Connections\Notion\PanelAlphaTasksTable;
use Exception;
use Illuminate\Support\Collection;
use JsonException;
use stdClass;

class AddWorkTask extends AbstractAction
{
    public const NAME = 'New Task';
    public const ICON = 'fa-solid fa-check';
    public const SHORTCUT = 'CommandOrControl+Shift+Q';
    public const MODEL = Model::GPT3;

    protected Assistant $assistant;
    protected Collection $issues;
    protected stdClass $task;


    public function __construct(Assistant $assistant)
    {
        $this->assistant = $assistant;
    }

    public static function getInitAction(): array
    {
        return [
            'type' => self::class,
            'name' => 'New Task',
            'icon' => 'fa-solid fa-check',
            'shortcut' => 'CommandOrControl+Shift+Q',
            'model' => Model::GPT3
        ];
    }

    /**
     * @return void
     */
    public function execute(): void
    {
        try {
            $this->getPanelAlphaIssuesFromGitLab();
            $this->createTaskFromQuery();
            $this->addTaskToNotionTable();
        } catch (Exception $exception) {
            $this->assistant->setResponse($exception->getMessage());
            return;
        }
        $this->assistant->setResponse('Zadanie zostało dodane.');
    }

    protected function getPanelAlphaIssuesFromGitLab(): void
    {
        $this->issues = PanelAlphaIssuesTable::getIssuesList();
    }

    /**
     * @return void
     * @throws JsonException
     */
    protected function createTaskFromQuery(): void
    {
        $model = $this->getModel();
        $messages = [
            [
                'role' => 'system',
                'content' => "Describe my intention from message below. "
                    . "###Example: Dodaj zadanie do pracy o zrobieniu nowego wyglądu strony do issue o nowa strona z niskim priorytetem. "
                    . "{\"task\": \"Nowy wygląd strony\", \"issue\": \"Notifications\", \"priority\": \"High\"}\n"
            ],
            [
                'role' => 'user',
                'content' => $this->assistant->getQuery()
            ]
        ];
        $temperature = 0.1;
        $functions = [
            [
                'name' => 'parse_task',
                'description' => 'Parse task of query from user message.',
                'parameters' => [
                    'type' => 'object',
                    'properties' => [
                        'task' => [
                            'type' => 'string',
                            'description' => 'Extract name or title of task'
                        ],
                        'issue' => [
                            'type' => 'string',
                            'enum' => $this->getIssuesTitles(),
                            'description' => 'Assign issue to task'
                        ],
                        'priority' => [
                            'type' => 'string',
                            'enum' => [
                                'Low',
                                'Medium',
                                'High'
                            ]
                        ]
                    ],
                    'required' => ['task', 'issue', 'priority']
                ],
            ]
        ];
        $this->assistant->api->chat()->create($model, $messages, $temperature, $functions);
        $this->task = $this->assistant->api->chat()->getFunctions();
    }

    /**
     * @return void
     */
    protected function addTaskToNotionTable(): void
    {
        PanelAlphaTasksTable::createNewTask($this->task, $this->getSelectedIssueId());
    }

    /**
     * @return array
     */
    protected function getIssuesTitles(): array
    {
        return $this->issues->map(function ($issue) {
            return $issue->getTitle();
        })->toArray();
    }

    /**
     * @return string|null
     */
    protected function getSelectedIssueId(): ?string
    {
        return $this->issues->first(function ($item) {
            return str_contains($item->getRawResponse()['properties']['Title']['title'][0]['text']['content'], $this->task->issue);
        })->getId();
    }
}
