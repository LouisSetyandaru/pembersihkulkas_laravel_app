<?php

use App\Http\Controllers\ReciptMakerController;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::post('/generate/send', [ReciptMakerController::class, 'sendMessage'])->withoutMiddleware(VerifyCsrfToken::class);
Route::get('/generate/messages', [ReciptMakerController::class, 'getMessages'])->withoutMiddleware(VerifyCsrfToken::class);
// Route::post('/register', [AuthController::class, 'register'])->withoutMiddleware(VerifyCsrfToken::class);
// Route::post('/login', [AuthController::class, 'login'])->withoutMiddleware(VerifyCsrfToken::class);
// Route::post('/google-auth', [AuthController::class, 'handleGoogleAuth'])->withoutMiddleware(VerifyCsrfToken::class);
Route::post('/api/register', [AuthController::class, 'register']);
Route::post('/api/login', [AuthController::class, 'login']);
Route::post('/api/google-auth', [AuthController::class, 'handleGoogleAuth']);
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/api/logout', [AuthController::class, 'logout']);
    Route::get('/api/user', [AuthController::class, 'user']);
});
// Route::get('/createToken', function(){
//    $user = User::create([
//     'name' => 'Galih',
//     'email'=> 'aahjaaa@gmail.com',
//     'password'=> bcrypt('password')
//    ]);

//    return    $user->createToken('apiKey')->plainTextToken;
// });
