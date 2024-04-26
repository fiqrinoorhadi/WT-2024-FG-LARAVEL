<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthenticationController extends Controller
{
    public function register(Request $request)
    {
        $validated = Validator::make($request->all(),[
            'full_name' => 'required',
            'username'  => 'required|unique:users,username|min:3|alpha_num|regex:/^[a-zA-Z0-9_.]+$/',
            'password'  => 'required|min:6',
            'bio'       => 'required|max:100',
            'is_private'=> 'boolean'
        ]);

        if ($validated->fails()) {
            return response()->json([
                'message'   => 'Invalid Field',
                'errors'  => $validated->errors()
            ], 422);
        }

        $request['password'] = Hash::make($request->password);

        $user = User::create($request->all());
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message'   => 'Register Success',
            'token'     => $token,
            'user'      => $user
        ], 201);
    }

    public function login(Request $request) {
        $validated = Validator::make($request->all(),[
            'username'  => 'required',
            'password'  => 'required'
        ]);

        $user = User::where('username', $request->username)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            return response()->json([
                'message'   => 'Wrong username or password'
            ], 401);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message'   => 'Login Success',
            'token'     => $token,
            'user'      => $user
        ], 200);
    }

    public function logout()
    {
        Auth::user()->tokens()->delete();

        return response()->json([
            'message'   => 'Logout Success'
        ], 200);
    }
}
