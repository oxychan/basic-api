<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(RegisterRequest $request) 
    {
        $fields = $request->validated();

        $user = User::create([
            'name' => $fields['name'],
            'password' => Hash::make($fields['password']),
            'email' => $fields['email']
        ]);

        $token = $user->createToken('myapptoken')->plainTextToken;

        $response = [
            'user' => $user,
            'token' => $token
        ];

        return response()->json(['data' => $response], 201);
    }

    public function login(LoginRequest $request)
    {
        $fields = $request->validated();

        $user = User::where('email', $fields['email'])->first();

        if (!$user || !Hash::check($fields['password'], $user->password)) {
            return response()->json(['errors' => 'Bad Credentials'], 401);
        }

        $token = $user->createToken('logintoken')->plainTextToken;

        $response = [
            'user' => $user,
            'token' => $token
        ];
        
        return response()->json(['data' => $response], 200);
    }

    public function logout() 
    {
        auth()->user()->tokens()->delete();

        return response()->json([
            'message' => 'Logged Out'
        ], 200);
    }
}
