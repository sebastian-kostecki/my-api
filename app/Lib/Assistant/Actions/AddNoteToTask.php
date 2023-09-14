<?php

namespace App\Lib\Assistant\Actions;

use App\Enums\Assistant\ChatModel;
use App\Lib\Connections\Notion\PanelAlphaTasksTable;
use App\Lib\Connections\OpenAI;
use App\Lib\Interfaces\ActionInterface;
use FiveamCode\LaravelNotionApi\Entities\Blocks\BulletedListItem;
use Illuminate\Support\Collection;

class AddNoteToTask implements ActionInterface
{
    /**
     * Initial variables for action
     */
    public const EXAMPLE = [
        "Dodaj notatke o poprawieniu kontrolera do zadania \"Task Management\" {\"action\": \"" . self::class . "\"}"
    ];
    public static string $name = 'Add Note To Task';
    public static string $icon = 'fa-regular fa-comment';
    public static string $shortcut = '';
    public static string $model = ChatModel::GPT3->value;


    protected string $prompt;
    protected OpenAI $openAI;
    protected Collection $tasks;
    protected \stdClass $response;

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
     */
    public function execute(): string
    {
        $this->getTasks();
        $content = $this->getPrompt();
        $this->sendToOpenAI($content);
        $task = $this->findTask();
        $this->addNote($task);

        return "Zadanie zostało dodane";
    }

    /**
     * @return void
     */
    protected function getTasks(): void
    {
        $this->tasks = PanelAlphaTasksTable::getActiveTasks();
    }

    /**
     * @return string
     */
    protected function getPrompt(): string
    {
        $issues = $this->tasks->map(function ($issue) {
            return $issue->getRawResponse()['properties']['Name']['title'][0]['text']['content'];
        })->join('|');

        $content = "Describe my intention from message below with JSON";
        $content .= "Focus on the beginning of it. Always return JSON and nothing more. \n";
        $content .= "Return a response in the format: {\"task\": \"Activity Logs w Client Area\", \"comment\": \"Muszę zrobić nowe logi do systemu\"}\n";
        $content .= "###Tasks: " . $issues . "\n";
        $content .= "###Examples: Do zadania Activity Logs dodaj, że muszę zrobić nowe logi";
        $content .= "{\"task\": \"Activity Logs w Client Area\", \"comment\": \"Muszę zrobić nowe logi do systemu\"\n";
        $content .= "Zadanie poprawki w komendzie notifications:sync. Muszę przepisać funkcję o ładowaniu klas.";
        $content .= "{\"task\": \"Poprawki w komendzie notifications:sync\", \"comment\": \" Muszę przepisać funkcję o ładowaniu klas.\"\n\n";
        $content .= "###message\n{$this->prompt}";

        return $content;
    }

    /**
     * @param string $content
     * @return void
     */
    protected function sendToOpenAI(string $content): void
    {
        $response = $this->openAI->getJson($content, $this->action);
        $this->response = json_decode($response);
    }

    /**
     * @return mixed|void
     */
    protected function findTask()
    {
        foreach ($this->tasks as $task) {
            if (strtolower($task->getTitle()) === strtolower($this->response->task)) {
                return $task;
            }
        }
    }

    /**
     * @param $task
     * @return void
     */
    protected function addNote($task): void
    {
        $listItem = BulletedListItem::create($this->response->comment);
        \Notion::block($task->getId())->append($listItem);
    }
}
