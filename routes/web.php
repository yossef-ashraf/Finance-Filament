<?php

use App\Services\GoldApiService;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    // $GoldApiService = new GoldApiService;
    // $GoldApiService->handle();
    return view('welcome');
});
