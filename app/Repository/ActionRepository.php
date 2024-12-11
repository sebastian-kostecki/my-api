<?php

namespace App\Repository;

use App\Models\Action;
use Illuminate\Database\Eloquent\Collection;

class ActionRepository
{
    public function all(): Collection
    {
        return Action::all();
    }
}
