<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserSession;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Carbon\Carbon;

class AuthController extends Controller
{
    // 1 ENDPOINT: POST /api/v1/register (Registro de Clientes) 
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'email' => 'required|string|email|max:254|unique:users',
            'phone' => 'nullable|string|max:30',
            'password' => 'required|string|min:12|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => strtolower($request->email),
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'role' => 'CLIENTE',
            'is_active' => true,
        ]);

        return response()->json(['message' => 'Usuario registrado correctamente', 'user' => $user], 201);
    }

    // 2 ENDPOINT: POST /api/v1/login (Login + Token) 
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = User::where('email', strtolower($request->email))->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Credenciales incorrectas'], 401);
        }

        if (!$user->is_active) {
            return response()->json(['message' => 'Cuenta desactivada'], 403);
        }

        // Crear el Token de Sanctum
        $token = $user->createToken('auth_token')->plainTextToken;
        
        // Registrar la sesion en la base de datos (JTI, expiración, etc.)
        $jti = Str::uuid()->toString();
        
        UserSession::create([
            'user_id' => $user->id,
            'jti' => $jti,
            'last_activity_at' => Carbon::now(),
            'expires_at' => Carbon::now()->addHours(2), // Expira en 2 horas
            'is_active' => true,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return response()->json([
            'message' => 'Login exitoso',
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => $user
        ]);
    }

    // 3 ENDPOINT: POST  /api/v1/refresh (Perfil autenticado)
    public function me(Request $request)
    {
        return response()->json($request->user());
    }

    // 4 ENDPOINT: POST /api/v1/logout (Cierre sesion) 
    public function logout(Request $request)
    {
        // Cancelar el token actual
        $request->user()->currentAccessToken()->delete();
        
        // Marcar la sesion activa o cancelada
        UserSession::where('user_id', $request->user()->id)
            ->where('is_active', true)
            ->update([
                'is_active' => false,
                'revoked_at' => Carbon::now()
            ]);

        return response()->json(['message' => 'Sesión cerrada correctamente']);
    }
}