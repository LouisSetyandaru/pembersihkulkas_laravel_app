<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Services\FirebaseService;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    protected $firebase;

    public function __construct(FirebaseService $firebase)
    {
        $this->firebase = $firebase->getDatabase();
    }

    public function register(Request $request)
    {
        Log::info('Register request received', ['request' => $request->all()]);

        // Validasi input
        $request->validate([
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
        ]);

        try {
            // Buat user baru
            $user = User::create([
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            Log::info('User registered successfully', ['user' => $user]);

            // Kirim respons sukses
            return response()->json([
                'message' => 'User registered successfully',
                'user' => $user,
            ], 200);
        } catch (\Exception $e) {
            Log::error('Error during registration', ['error' => $e->getMessage()]);

            // Kirim respons error
            return response()->json([
                'message' => 'Registration failed',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function login(Request $request)
    {
        $uid = $request->input('uid');
        $email = $request->input('email');
        $name = $request->input('name');
        $password = $request->input('password');

        $this->firebase->getReference('users/' . $uid)->set([
            'email' => $email,
            'name' => $name,
            'password' => $password,
        ]);

        return response()->json(['message' => 'User logged in successfully']);
    }
}

// namespace App\Http\Controllers;

// use App\Models\User;
// use Illuminate\Http\Request;
// use Illuminate\Support\Facades\Auth;
// use Illuminate\Support\Facades\Hash;
// use Laravel\Socialite\Facades\Socialite;
// use Illuminate\Validation\ValidationException;

// class AuthController extends Controller
// {
//     public function register(Request $request)
//     {
//         $request->validate([
//             'name' => 'required|string|max:255',
//             'email' => 'required|string|email|max:255|unique:users',
//             'password' => 'required|string|min:8',
//         ]);

//         $user = User::create([
//             'name' => $request->name,
//             'email' => $request->email,
//             'password' => Hash::make($request->password),
//         ]);

//         $token = $user->createToken('auth_token')->plainTextToken;

//         return response()->json([
//             'access_token' => $token,
//             'token_type' => 'Bearer',
//             'user' => $user
//         ], 201);
//     }

//     public function login(Request $request)
//     {
//         $request->validate([
//             'email' => 'required|email',
//             'password' => 'required',
//         ]);

//         if (!Auth::attempt($request->only('email', 'password'))) {
//             throw ValidationException::withMessages([
//                 'email' => ['The provided credentials are incorrect.'],
//             ]);
//         }

//         $user = User::where('email', $request->email)->firstOrFail();
//         $token = $user->createToken('auth_token')->plainTextToken;

//         return response()->json([
//             'access_token' => $token,
//             'token_type' => 'Bearer',
//             'user' => $user
//         ]);
//     }

//     public function handleGoogleAuth(Request $request)
//     {
//         try {
//             $googleUser = Socialite::driver('google')
//                 ->stateless()
//                 ->userFromToken($request->input('access_token'));

//             $user = User::updateOrCreate(
//                 ['email' => $googleUser->email],
//                 [
//                     'name' => $googleUser->name,
//                     'google_id' => $googleUser->id,
//                     'password' => Hash::make(uniqid()) // Random password for Google users
//                 ]
//             );

//             $token = $user->createToken('auth_token')->plainTextToken;

//             return response()->json([
//                 'access_token' => $token,
//                 'token_type' => 'Bearer',
//                 'user' => $user
//             ]);
//         } catch (\Exception $e) {
//             return response()->json(['error' => 'Failed to authenticate with Google'], 401);
//         }
//     }

//     public function logout(Request $request)
//     {
//         $request->user()->currentAccessToken()->delete();
//         return response()->json(['message' => 'Logged out successfully']);
//     }

//     public function user(Request $request)
//     {
//         return response()->json($request->user());
//     }
// }
