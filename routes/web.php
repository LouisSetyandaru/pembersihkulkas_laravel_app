<?php

use App\Http\Controllers\ReciptMakerController;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RecipeController;

Route::post('/generate/send', [ReciptMakerController::class, 'sendMessage'])->withoutMiddleware(VerifyCsrfToken::class);
Route::get('/generate/messages', [ReciptMakerController::class, 'getMessages'])->withoutMiddleware(VerifyCsrfToken::class);
Route::get('/recipes', [RecipeController::class, 'getRecipes'])->withoutMiddleware(VerifyCsrfToken::class);
Route::get('/recipes/{id}', [RecipeController::class, 'getRecipeDetail'])->withoutMiddleware(VerifyCsrfToken::class);

// Route::get('/createToken', function(){
//    $user = User::create([
//     'name' => 'Galih',
//     'email'=> 'aahjaaa@gmail.com',
//     'password'=> bcrypt('password')
//    ]);

//    return    $user->createToken('apiKey')->plainTextToken;
// });
