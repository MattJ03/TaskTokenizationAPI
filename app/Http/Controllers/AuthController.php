<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request) {
        $request->validate([           //validating only correct information is input, in order to next
            'name' => 'required|string|max:50',
            'email' => 'required|string|email|max:50|unique:users',
            'password' => 'required|string|min:6|max:25|confirmed',
        ]);

        //creating the user
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $token = $user->createToken('token')->plainTextToken;
        return response()->json(['user' => $user, 'token' => $token],201);

    }

    public function login(Request $request) {
        $request->validate([
            'name' => 'required|string|max:50',
            'email' => 'required|string|email|max:50',
            'password' => 'required|string|min:6|max:25',
        ]);
         //make sure the user logged in under correct creds
        if(!Auth::attempt($request->only('email', 'password'))) {
            return response()->json(['Message' => 'Wrongs Credentials'], 401);
        }
        $user = Auth::user();
        $token = $user->createToken('token')->plainTextToken;
        return response()->json(['user' => $user, 'token' => $token], 201);
    }

    public function logout(Request $request)  {
        $request->user()->tokens()->delete();
        return response()->json(['Message' => 'Logged out'], 201);
    }
}
