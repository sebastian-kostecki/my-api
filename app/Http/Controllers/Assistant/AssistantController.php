<?php

namespace App\Http\Controllers\Assistant;

use App\Http\Controllers\Controller;
use App\Http\Requests\AssistantRequest;
use App\Http\Resources\AssistantResource;
use App\Models\Assistant;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class AssistantController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        $assistants = Assistant::all();

        return AssistantResource::collection($assistants);
    }

    public function getAvatarUrl(int $assistantId): BinaryFileResponse
    {
        $assistant = Assistant::findOrFail($assistantId);
        $path = resource_path("/img/assistants/{$assistant->name}.jpg");

        return response()->file($path);
    }

    public function updateModel(int $assistantId, AssistantRequest $request): AssistantResource
    {
        $params = $request->validated();

        $assistant = Assistant::findOrFail($assistantId);
        $assistant->setModel($params['model_id']);

        return new AssistantResource($assistant);
    }
}
