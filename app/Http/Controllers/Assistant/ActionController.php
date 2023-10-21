<?php

namespace App\Http\Controllers\Assistant;

use App\Enums\Assistant\ChatModel;
use App\Http\Controllers\Controller;
use App\Http\Resources\ActionResource;
use App\Models\Action;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ActionController extends Controller
{

    /**
     * @return AnonymousResourceCollection
     */
    public function index(): AnonymousResourceCollection
    {
        $actions = Action::all();
        return ActionResource::collection($actions);
    }

    /**
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $params = $request->validate([
            'name' => 'string|required',
            'icon' => 'string|required',
            'shortcut' => 'string|nullable',
            'model' => 'string|required',
            'enabled' => 'boolean|required'
        ]);

        $action = Action::findOrFail($id);
        $action->update($params);

        return new JsonResponse([
            'success' => true
        ]);
    }

    /**
     * @return JsonResponse
     */
    public function modelIndex(): JsonResponse
    {
        $models = ChatModel::cases();

        $data = collect($models)->map(function ($model) {
            return [
                'name' => $model->name,
                'value' => $model->value
            ];
        });

        return new JsonResponse([
            'data' => $data
        ]);
    }
}
