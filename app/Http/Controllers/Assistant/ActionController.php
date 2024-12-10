<?php

namespace App\Http\Controllers\Assistant;

use App\Http\Controllers\Controller;
use App\Http\Resources\ActionResource;
use App\Repository\ActionRepository;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ActionController extends Controller
{
    public function index(ActionRepository $actionRepository): AnonymousResourceCollection
    {
        $actions = $actionRepository->all();

        return ActionResource::collection($actions);
    }
}
