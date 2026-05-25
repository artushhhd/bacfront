<?php

namespace App\Http\Controllers;

use App\Http\Requests\loginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function register(RegisterRequest $request)
    {
        $data = $request->validated();
        $data['password'] = bcrypt($data['password']);

        $user = User::create($data);

        return response()->json([
            'user'  => $user,
            'token' => $user->createToken('auth')->plainTextToken
        ], 201);
    }

    public function login(loginRequest $request)
    {
        if (!Auth::attempt($request->validated())) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }
        $user = User::find(Auth::id());
        $token = $user->createToken('auth')->plainTextToken;

        return response()->json([ 
            'user'  => $user,
            'token' => $token
        ]);
    }
    public function profile(){
        return response()->json(User::find(Auth::id()));
    }

    public function logout(Request $request){
        if ($request->user() && $request->user()->currentAccessToken()) {
            $request->user()->currentAccessToken()->delete();
        }

        return response()->json(['message' => 'Logged out successfully']);
    }
}
