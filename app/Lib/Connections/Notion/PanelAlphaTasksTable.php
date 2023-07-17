<?php

namespace App\Lib\Connections\Notion;

use FiveamCode\LaravelNotionApi\Entities\Page;
use FiveamCode\LaravelNotionApi\Query\Filters\Filter;
use FiveamCode\LaravelNotionApi\Query\Filters\Operators;
use Illuminate\Support\Collection;

class PanelAlphaTasksTable
{
    const TABLE_ID = "73c22dd1451d40a48ced9f69a33b195d";

    public static function createNewTask(\stdClass $task, Collection $issues)
    {
        $issue = $issues->first(function ($item) use ($task) {
            return str_contains($item->getRawResponse()['properties']['Title']['title'][0]['text']['content'], $task->issue);
        });

        $page = new Page();
        $page->setTitle('Name', $task->task);
        $page->setSelect('Status', 'Not started');
        $page->setSelect('Priority', $task->priority ?? "Medium");
        if ($issue) {
            $page->setRelation('Issue', [$issue->getId()]);
        }

        \Notion::pages()->createInDatabase(self::TABLE_ID, $page);
    }

    public static function getActiveTasks()
    {
        $statusFilter = Filter::rawFilter("Status", [
            "select" => [Operators::DOES_NOT_EQUAL => 'Done'],
        ]);

        return \Notion::database(self::TABLE_ID)
            ->filterBy($statusFilter)
            ->query()
            ->asCollection();
    }
}
