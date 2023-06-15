<?php

use App\Http\Controllers\AssistantController;
use App\Http\Controllers\ShortcutController;
use App\Http\Controllers\TextController;
use App\Http\Controllers\TranslationController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::post('/login', function (Request $request) {
        return;
    })->name('login');

    Route::post('/chat', [AssistantController::class, 'chat']);
    

    /**
     * Shortcuts
     */
    Route::post('/shortcut/translate', [ShortcutController::class, 'translate']);
    Route::post('/shortcut/save-note', [ShortcutController::class, 'saveNote']);

    /**
     * Assistant
     */
    Route::post('/assistant/prompt', [AssistantController::class, 'ask']);
    Route::post('/assistant/answer', [AssistantController::class, 'get']);
    Route::get('/assistant/actions', [AssistantController::class, 'getActions']);
});

