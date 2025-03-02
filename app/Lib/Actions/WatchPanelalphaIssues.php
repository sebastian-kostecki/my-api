<?php

namespace App\Lib\Actions;

use App\Attributes\ActionIconAttribute;
use App\Attributes\ActionNameAttribute;
use App\Lib\Actions\AbstractActions\AbstractCommandAction;
use App\Lib\Interfaces\ActionInterface;
use Illuminate\Support\Facades\Artisan;

#[ActionNameAttribute('Watch PanelAlpha Issues')]
#[ActionIconAttribute('fa-solid fa-table-list')]
class WatchPanelalphaIssues extends AbstractCommandAction implements ActionInterface
{
    public function execute(): string
    {
        Artisan::call('panelalpha:watch-issues');

        return static::getResponse();
    }
}
