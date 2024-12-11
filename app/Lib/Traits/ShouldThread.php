<?php

namespace App\Lib\Traits;

trait ShouldThread
{
    public function isRequireThread(): bool
    {
        return true;
    }
}
