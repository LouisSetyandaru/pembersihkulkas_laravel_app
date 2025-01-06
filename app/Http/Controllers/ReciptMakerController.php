<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use OpenAI\Laravel\Facades\OpenAI;
use App\Models\ReciptMaker;

class ReciptMakerController extends Controller
{
    public function sendMessage(Request $request)
    {
        try {
            // Validasi input
            $request->validate([
                'ingredients' => 'required|array', // Daftar bahan masakan dalam bentuk array
                'user_id' => 'required|string',
            ]);

            // Gabungkan bahan menjadi string
            $ingredients = implode(', ', $request->ingredients);

            // Buat prompt untuk OpenAI
            $prompt = "Buatkan resep masakan yang menggunakan bahan-bahan berikut: $ingredients. Tuliskan langkah-langkah membuat masakan dari bahan tersebut secara jelas dan detail.";

            // Simpan pesan user
            $userMessage = ReciptMaker::create([
                'user_id' => $request->user_id,
                'message' => "Bahan-bahan: $ingredients",
                'is_ai' => false,
            ]);

            // Buat pesan untuk OpenAI
            $messages = [
                [
                    'role' => 'user',
                    'content' => $prompt
                ]
            ];

            // Dapatkan respons dari OpenAI
            $result = OpenAI::chat()->create([
                'model' => 'gpt-3.5-turbo',
                'messages' => $messages,
                'temperature' => 0.7,
                'max_tokens' => 800,
            ]);

            // Ekstrak respons AI
            $aiResponse = $result->choices[0]->message->content;

            // Simpan respons AI ke database
            $aiMessage = ReciptMaker::create([
                'user_id' => $request->user_id,
                'message' => $aiResponse,
                'is_ai' => true,
            ]);

            // Kembalikan respons
            return response()->json([
                'status' => 'success',
                'data' => [
                    'user_message' => [
                        'message' => "Bahan-bahan: $ingredients",
                        'timestamp' => $userMessage->created_at
                    ],
                    'ai_response' => [
                        'message' => $aiResponse,
                        'timestamp' => $aiMessage->created_at
                    ]
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('ReciptMakerController Error: ' . $e->getMessage());

            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
