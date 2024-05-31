<?php

namespace App\Repository;

use App\Models\Model;
use Illuminate\Database\Eloquent\Collection;

class ModelRepository
{
    /**
     * @return Collection
     */
    public function getModels(): Collection
    {
        return Model::all();
    }
}
