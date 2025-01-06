<?php

use App\Http\Controllers\ReciptMakerController;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Support\Facades\Route;

Route::post('/generate/send', [ReciptMakerController::class, 'sendMessage'])->withoutMiddleware(VerifyCsrfToken::class);
Route::get('/generate/messages', [ReciptMakerController::class, 'getMessages'])->withoutMiddleware(VerifyCsrfToken::class);
Route::get('/api-key', function () {
    return response()->json(['api_key' => env('OPENAI_API_KEY')]);
});
