<?php

use App\Http\Controllers\Assistant\ActionController;
use App\Http\Controllers\Assistant\AssistantController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/', [AssistantController::class, 'query'])
        ->name('assistant_chat');

    Route::get('actions', [ActionController::class, 'index'])
        ->name('action.list');
    Route::put('action/{id}/update', [ActionController::class, 'update'])
        ->name('action.update');
});
