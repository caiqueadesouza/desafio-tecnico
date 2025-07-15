<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        try {
            if (!$accessToken = JWTAuth::attempt($credentials)) {
                return response()->json(['error' => 'Credenciais inválidas'], 404);
            }
        } catch (JWTException $e) {
            return response()->json(['error' => 'No se pudo crear el token'], 500);
        }

        $user = auth()->user();

        if (!$user) {
            return response()->json(['error' => 'Usuário não encontrado ou desativado'], 404);
        }

        return response()->json([
            'user' => $user,
            'accessToken' => $accessToken
        ]);
    }

    public function logout()
    {
        JWTAuth::invalidate(JWTAuth::getToken());

        return response()->json(['message' => 'Logout realizado com sucesso']);
    }

    public function refreshToken()
    {
        $token = JWTAuth::getToken();
        $newToken = JWTAuth::refresh($token);
        $user = auth()->user();

        return response()->json([
            'user' => $user,
            'accessToken' => $newToken
        ]);
    }
}
