<?php

namespace App\Lib\Connections\Notion;

use FiveamCode\LaravelNotionApi\Entities\Page;
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
        $page->setSelect('Priority', $task->priority);
        $page->setRelation('Issue', [$issue->getId()]);

        \Notion::pages()->createInDatabase(self::TABLE_ID, $page);
    }
}
