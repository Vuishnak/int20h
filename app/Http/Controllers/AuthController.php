<?php

namespace App\Http\Controllers;

use Dingo\Api\Contract\Http\Request;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{

    /**
     * Авторизация пользователя
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function authenticate(Request $request)
    {
        // забираем нужные данные из запроса
        $credentials = $request->only('email', 'password');
        try {
            // попытка авторизации и создание токена
            if (!$token = JWTAuth::attempt($credentials)) {
                return response()->json(['error' => 'invalid_credentials'], 401);
            }
        } catch (JWTException $e) {
            return response()->json(['error' => 'could_not_create_token'], 500);
        }

        return response()->json(compact('token'));
    }

    /**
     * Выход из аккаунта
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout();
        return response()->json([
            'message' => 'Вы успешно вышли из аккаунта!',
        ], 200);
    }

    /**
     * Получаем авторизированного пользователя
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUser()
    {
        if (!$user = JWTAuth::parseToken()->authenticate()) {
            return response()->json(['user_not_found'], 404);
        }

        return response()->json(compact('user'));
    }

}
