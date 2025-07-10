<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Log;
use Exception;

class AuthController extends Controller
{
    public function newUser(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name'     => 'required|string|max:250',
                'email'    => 'required|string|email|unique:users',
                'password' => 'required|string|min:6|confirmed',
            ]);

            if ($validator->fails()) {
                return response()->json(['status' => false, 'errors' => $validator->errors()], 422);
            }

            $user = User::create([
                'name'     => $request->name,
                'email'    => $request->email,
                'password' => Hash::make($request->password),
            ]);

            return response()->json(['status' => true, 'message' => 'El usuario se ha creado con exito'], 201);
        } catch (Exception $e) {
            Log::error('Error en register(): ' . $e->getMessage());
            return response()->json(['status' => false, 'message' => 'Error interno', 'error' => $e->getMessage()], 500);
        }
    }

    public function login(Request $request)
    {
        try {
            $credentials = $request->only('email', 'password');

            if (!$token = JWTAuth::attempt($credentials)) {
                return response()->json(['status' => false, 'message' => 'Credenciales inválidas'], 401);
            }

            return response()->json([
                'status' => true,
                'token' => $token,
                'token_type' => 'bearer',
                'expires_in' => auth('api')->factory()->getTTL() * 60
            ]);
        } catch (Exception $e) {
            Log::error('Error en login(): ' . $e->getMessage());
            return response()->json(['status' => false, 'message' => 'Error interno', 'error' => $e->getMessage()], 500);
        }
    }

    public function me()
    {
        try {
            return response()->json(['status' => true, 'user' => auth()->user()]);
        } catch (Exception $e) {
            Log::error('Error en me(): ' . $e->getMessage());
            return response()->json(['status' => false, 'message' => 'Error al obtener usuario'], 500);
        }
    }
}
