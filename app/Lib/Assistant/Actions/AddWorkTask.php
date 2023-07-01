<?php

namespace App\Lib\Assistant\Actions;

use App\Lib\Connections\Notion\PanelAlphaIssuesTable;
use App\Lib\Connections\Notion\PanelAlphaTasksTable;
use App\Lib\Connections\OpenAI;
use App\Lib\Interfaces\ActionInterface;
use Illuminate\Support\Collection;

class AddWorkTask implements ActionInterface
{
    public static string $name = 'New Task';
    public static string $slug = 'add-work-task';
    public static string $icon = 'fa-solid fa-check';
    public static string $shortcut = 'CommandOrControl+Shift+Q';

    protected string $prompt;
    protected OpenAI $openAI;
    protected Collection $issues;
    protected \stdClass $response;

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
     */
    public function execute(): string
    {
        $this->getIssues();
        $content = $this->getPrompt();
        $this->sendToOpenAI($content);
        PanelAlphaTasksTable::createNewTask($this->response, $this->issues);
        return "Zadanie zostało dodane";
    }

    protected function getIssues(): void
    {
        $this->issues = PanelAlphaIssuesTable::getIssuesList();
    }

    protected function getPrompt(): string
    {
        $issues = $this->issues->map(function ($issue) {
            return $issue->getRawResponse()['properties']['Title']['title'][0]['text']['content'];
        })->join('|');

        $content = "Describe my intention from message below with JSON";
        $content .= "Focus on the beginning of it. Always return JSON and nothing more. \n";
        $content .= "Return a response in the format: {\"task\": \"Make function\", \"issue\": \"Notifications\", \"priority\": \"High\"}\n";
        $content .= "###Issues: " . $issues . "\n";
        $content .= "###Priority: High|Medium|Low\n";
        $content .= "###Example: Dodaj zadanie do pracy o zrobieniu nowego wyglądu strony do issue o nowa strona z niskim priorytetem ";
        $content .= "{\"task\": \"Nowy wygląd strony\", \"issue\": \"Notifications\", \"priority\": \"High\"}\n";
        $content .= "###message\n{$this->prompt}";

        return $content;
    }

    protected function sendToOpenAI(string $content): void
    {
        $response = $this->openAI->getJson($content);
        $this->response = json_decode($response);
    }
}
