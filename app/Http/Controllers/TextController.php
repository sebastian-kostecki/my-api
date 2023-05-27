<?php

namespace App\Http\Controllers;

use App\Models\Note;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TextController extends Controller
{
    public function saveText(Request $request)
    {
        $request->validate([
            'text' => 'string|required'
        ]);

        $lines = explode('.', $request->input('text'));
        foreach ($lines as $line) {
            if ($line) Note::create(['content' => trim($line)]);
        }

        return new JsonResponse([
            'success' => true
        ]);
    }
}
