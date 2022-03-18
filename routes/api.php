<?php

use App\Http\Controllers\Api\AppController;
use Illuminate\Support\Facades\Route;

Route::resource('/', AppController::class)->only(['index']);
