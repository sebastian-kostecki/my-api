<?php

namespace App\Http\Controllers\Assistant;

use App\Http\Controllers\Controller;
use App\Http\Resources\ModelResource;
use App\Repository\ModelRepository;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ModelController extends Controller
{
    public function index(ModelRepository $repository): AnonymousResourceCollection
    {
        $models = $repository->getModels();

        return ModelResource::collection($models);
    }
}
