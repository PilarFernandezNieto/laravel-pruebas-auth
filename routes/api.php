<?php

use App\Http\Controllers\AdminController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum', 'verified'])->get('/user', function (Request $request) {
    return $request->user();
});

Route::get("/", function () {
    return "Hola Mundo";
});
Route::middleware(['auth:sanctum', 'verified'])->group(function () {
    Route::apiResource('/admin', AdminController::class);
});
