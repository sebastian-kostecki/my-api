<?php

namespace App\Http\Controllers;

use App\Lib\Assistant\Shortcuts\Translate;
use DeepL\DeepLException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ShortcutController extends Controller
{
    public function __construct(
        protected Translate $translator
    ) {}

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

        $translatedText = $this->translator->translate($request->input('text'));
        return new JsonResponse([
            'data' => $translatedText
        ]);
    }
}
