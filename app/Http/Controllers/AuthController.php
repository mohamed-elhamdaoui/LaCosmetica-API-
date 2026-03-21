<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'client',
        ]);


        $token = Auth::login($user);

        return response()->json([
            'message' => 'User successfully registered',
            'access_token' => $token,
            'token_type' => 'bearer',
            'user' => $user
        ], 201);
    }


    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        // Simple check: attempt to log in
        if (!$token = Auth::attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized: Invalid credentials'], 401);
        }

        return response()->json([
            'message' => 'Login successful',
            'access_token' => $token,
            'token_type' => 'bearer',
            'user' => Auth::user()
        ]);
    }


    
}
