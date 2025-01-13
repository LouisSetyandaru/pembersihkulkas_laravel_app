<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class RecipeController extends Controller
{
    public function getRecipes()
    {
        try {
            // Ambil API key dari .env
            $apiKey = config('services.spoonacular.api_key');
            if (!$apiKey) {
                return response()->json(['error' => 'API key not found'], 500);
            }

            // Lakukan request ke Spoonacular API
            $response = Http::get('https://api.spoonacular.com/recipes/complexSearch', [
                'apiKey' => $apiKey,
                'number' => 10,
            ]);

            // Periksa apakah request berhasil
            if ($response->failed()) {
                return response()->json(['error' => 'Failed to fetch data from Spoonacular'], 500);
            }

            // Ambil data resep dari response
            $data = $response->json();

            // Pastikan key 'results' ada di response
            if (!isset($data['results'])) {
                return response()->json(['error' => 'Invalid response from Spoonacular'], 500);
            }

            $recipes = $data['results'];

            // Kembalikan data dalam format JSON
            return response()->json($recipes);
        } catch (\Exception $e) {
            // Tangkap error dan kembalikan pesan error
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getRecipeDetail($id)
    {
        try {
            // Ambil API key dari .env
            $apiKey = config('services.spoonacular.api_key');
            if (!$apiKey) {
                return response()->json(['error' => 'API key not found'], 500);
            }

            // Lakukan request ke Spoonacular API untuk detail resep
            $response = Http::get("https://api.spoonacular.com/recipes/{$id}/information", [
                'apiKey' => $apiKey,
            ]);

            // Periksa apakah request berhasil
            if ($response->failed()) {
                return response()->json(['error' => 'Failed to fetch recipe details from Spoonacular'], 500);
            }

            // Ambil data detail resep dari response
            $recipeDetail = $response->json();

            // Kembalikan data dalam format JSON
            return response()->json($recipeDetail);
        } catch (\Exception $e) {
            // Tangkap error dan kembalikan pesan error
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
