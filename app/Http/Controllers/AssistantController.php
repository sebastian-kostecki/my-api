<?php

namespace App\Http\Controllers;

use App\Lib\Connections\DeepL;
use DeepL\DeepLException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AssistantController extends Controller
{
    /**
     * @param Request $request
     * @return JsonResponse
     * @throws DeepLException
     */
    public function translate(Request $request): JsonResponse
    {
        $request->validate([
            'text' => 'string|required'
        ]);

        $translator = new DeepL();
        $translatedText = $translator->translate($request->input('text'));
        return new JsonResponse([
            'data' => $translatedText
        ]);
    }
}
