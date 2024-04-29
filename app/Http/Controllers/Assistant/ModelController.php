<?php

namespace App\Http\Controllers\Assistant;

use App\Http\Controllers\Controller;
use App\Lib\Connections\ArtificialIntelligence\OpenAI;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ModelController extends Controller
{
    /**
     * @param OpenAI $openAI
     * @return JsonResponse
     */
    public function index(OpenAI $openAI): JsonResponse
    {
        return new JsonResponse([
            'data' => $openAI->getModels()
        ]);
    }
}
