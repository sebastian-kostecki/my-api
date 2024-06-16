<?php

namespace App\Lib\Traits;

trait ShouldThread
{
    /**
     * @return bool
     */
    public function isRequireThread(): bool
    {
        return true;
    }
}
