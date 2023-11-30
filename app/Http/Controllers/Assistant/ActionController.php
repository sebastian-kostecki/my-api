<?php

namespace App\Http\Controllers\Assistant;

use App\Enums\Assistant\ChatModel;
use App\Http\Controllers\Controller;
use App\Http\Resources\ActionResource;
use App\Lib\Assistant\Actions\DefaultAssistant;
use App\Lib\Assistant\Actions\Query;
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
        $actions = Action::all()->filter(function ($action) {
            return !$action->hidden;
        });
        return ActionResource::collection($actions);
    }

    /**
     * @param Request $request
     * @return ActionResource
     */
    public function create(Request $request): ActionResource
    {
        $params = $request->validate([
            'name' => 'string|required',
            'icon' => 'string|required',
            'shortcut' => 'string|nullable',
            'model' => 'string|required',
            'instructions' => 'string|required',
            'enabled' => 'boolean|required'
        ]);

        $params['type'] = DefaultAssistant::class;
        $action = Action::create($params);

        return new ActionResource($action);
    }

    /**
     * @param Request $request
     * @param int $id
     * @return ActionResource
     */
    public function update(Request $request, int $id): ActionResource
    {
        $params = $request->validate([
            'name' => 'string|required',
            'icon' => 'string|required',
            'shortcut' => 'sometimes|string|nullable',
            'model' => 'sometimes|string|nullable',
            'instructions' => 'sometimes|string|nullable',
            'enabled' => 'boolean|required'
        ]);

        $action = Action::findOrFail($id);
        $action->update($params);
        $action->syncAssistant();

        return new ActionResource($action);
    }

    /**
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        $action = Action::findOrFail($id);
        $action->delete();

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
