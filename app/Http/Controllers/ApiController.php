<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class ApiController extends Controller
{
    /**
     * @throws ValidationException
     */
    public function login(Request $request): JsonResponse
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['As credenciais fornecidas estão incorretas.'],
            ]);
        }

        $user->tokens()->delete();

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Login realizado com sucesso',
            'data' => [
                'user' => $user,
                'token' => $token,
            ],
        ], 200);
    }

    public function logoutAll(Request $request): JsonResponse
    {
        $request->user()->tokens()->delete();

        return response()->json([
            'message' => 'Logout de todos os dispositivos realizado com sucesso',
        ], 200);
    }

    public function register(Request $request)
    {
        $validate = $request->validate([
            'email' => ['required', 'unique:users,email'],
            'name' => ['required'],
            'password' => ['string', 'min:6'],
        ], [
            'email.required' => 'Insira o email',
            'email.unique' => 'Este email já está em uso',
            'name.required' => 'Insira seu nome',
            'password.string' => 'A senha deve ter letras e números',
            'password.min' => 'A senha deve ter pelo menos 6 caracteres',
        ]);

        try {

            $user = User::create([
                'name' => strtolower($validate['name']),
                'email' => strtolower($validate['email']),
                'password' => Hash::make($validate['password']),
                'type' => 'customer',
                'exam_price' => '130,00',
            ]);

            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'message' => 'Conta criada com sucesso',
                'data' => [
                    'user' => $user,
                    'token' => $token,
                ],
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erro ao registrar usuário',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
