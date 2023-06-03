<?php

use App\Http\Controllers\AssistantController;
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

    Route::post('/translate', [TranslationController::class, 'translate'])
        ->name('translate');

    Route::post('/save-text', [TextController::class, 'saveText']);

    Route::post('/chat', [AssistantController::class, 'chat']);

    Route::post('/openai/chat', [AssistantController::class, 'assistant']);
});

