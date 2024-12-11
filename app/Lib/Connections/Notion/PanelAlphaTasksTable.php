<?php

namespace App\Lib\Connections\Notion;

use FiveamCode\LaravelNotionApi\Entities\Page;
use FiveamCode\LaravelNotionApi\Query\Filters\Filter;
use FiveamCode\LaravelNotionApi\Query\Filters\Operators;
use stdClass;

class PanelAlphaTasksTable
{
    protected const TABLE_ID = '73c22dd1451d40a48ced9f69a33b195d';

    public static function createNewTask(stdClass $task, ?string $issueId): void
    {
        $page = new Page;
        $page->setTitle('Name', $task->task);
        $page->setSelect('Status', 'Not started');
        $page->setSelect('Priority', $task->priority ?? 'Medium');
        if ($issueId) {
            $page->setRelation('Issue', [$issueId]);
        }

        \Notion::pages()->createInDatabase(self::TABLE_ID, $page);
    }

    public static function getActiveTasks()
    {
        $statusFilter = Filter::rawFilter('Status', [
            'select' => [Operators::DOES_NOT_EQUAL => 'Done'],
        ]);

        return \Notion::database(self::TABLE_ID)
            ->filterBy($statusFilter)
            ->query()
            ->asCollection();
    }
}
