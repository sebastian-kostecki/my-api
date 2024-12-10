<?php

namespace App\Lib\Connections\Notion;

use FiveamCode\LaravelNotionApi\Query\Filters\Filter;
use FiveamCode\LaravelNotionApi\Query\Filters\Operators;
use Illuminate\Support\Collection;

class PanelAlphaIssuesTable
{
    protected const TABLE_ID = 'e5f489afb035406bbcfbd57d9533147a';

    public static function getIssuesList(): Collection
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
