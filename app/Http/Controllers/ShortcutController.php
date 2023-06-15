<?php

namespace App\Http\Controllers;

use App\Lib\Assistant\Actions\SaveNote;
use App\Lib\Assistant\Actions\Translate;
use DeepL\DeepLException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ShortcutController extends Controller
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

        $translator = new Translate($request->input('text'));
        $translatedText = $translator->execute();
        return new JsonResponse([
            'data' => $translatedText
        ]);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function saveNote(Request $request): JsonResponse
    {
        $request->validate([
            'text' => 'string|required'
        ]);
        try {
            $client = new SaveNote('test-notes');
            $client->setText($request->input('text'));
            $response = $client->execute();
        } catch (\Exception $e) {
            return new JsonResponse([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
        return new JsonResponse([
            'data' => $response
        ]);
    }
}
