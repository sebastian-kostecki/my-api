<?php

use App\Http\Controllers\Assistant\ActionController;
use App\Http\Controllers\Assistant\AssistantController;
use App\Http\Controllers\Assistant\ChatController;
use App\Http\Controllers\Assistant\ModelController;
use Illuminate\Support\Facades\Route;

Route::get('/assistant/{id}/avatar', [AssistantController::class, 'getAvatarUrl'])
    ->name('assistant.avatar');

Route::get('/assistants', [AssistantController::class, 'index'])
    ->name('assistant.list');

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/execute', [ChatController::class, 'store'])
        ->name('chat');

    Route::get('/actions', [ActionController::class, 'index'])
        ->name('action.list');
    Route::post('/action/create', [ActionController::class, 'create'])
        ->name('action.create');
    Route::put('/action/{id}/update', [ActionController::class, 'update'])
        ->name('action.update');
    Route::delete('/action/{id}', [ActionController::class, 'destroy'])
        ->name('action.delete');

    Route::get('/models', [ModelController::class, 'index'])
        ->name('model.list');


    Route::patch('/assistant/{id}/model', [AssistantController::class, 'updateModel'])
        ->name('assistant.model.update');
});
