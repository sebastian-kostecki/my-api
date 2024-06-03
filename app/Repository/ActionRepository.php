<?php

namespace App\Repository;

use App\Models\Action;
use Illuminate\Database\Eloquent\Collection;

class ActionRepository
{
    /**
     * @return Collection
     */
    public function all(): Collection
    {
        return Action::all();
    }
}
