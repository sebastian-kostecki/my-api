<?php

use App\Http\Controllers\Assistant\ActionController;
use App\Http\Controllers\AssistantController;
use App\Http\Controllers\ShortcutController;
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

    /**
     * Shortcuts
     */
    Route::post('/shortcut/translate', [ShortcutController::class, 'translate']);
    Route::post('/shortcut/save-note', [ShortcutController::class, 'saveResource']);
    /**
     * Assistant
     */
    Route::post('/assistant/chat', [AssistantController::class, 'chat']);
    Route::get('/assistant/actions', [ActionController::class, 'index']);
    Route::put('/assistant/action/{id}/update', [ActionController::class, 'update']);
});

