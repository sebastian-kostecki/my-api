<?php

use App\Http\Controllers\AssistantController;
use Illuminate\Support\Facades\Route;

Route::post('/', [AssistantController::class, 'query']);
