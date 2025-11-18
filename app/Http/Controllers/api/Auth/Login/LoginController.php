<?php

namespace App\Http\Controllers\api\Auth\Login;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws ValidationException
     */
    public function login(Request $request): JsonResponse
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::query()->where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['Неверные учетные данные.'],
            ]);
        }

        // Генерация нового токена
        $string = Str::random(64);
        $user->api_token = $string;
        $user->save();

        return response()->json([
            'status' => 200,
            'token' => $user->api_token,
            'user' => $user,
        ]);
    }


    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function logout(Request $request): JsonResponse
    {
        $token = $request->bearerToken();

        if ($token) {
            $user = User::where('api_token', $token)->first();
            if ($user) {
                $user->api_token = null;
                $user->save();
            }
        }

        return response()->json(['message' => 'Logged out']);
    }
}
