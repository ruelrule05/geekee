<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\LoginRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{    
    /**
     * login
     *
     * @param  mixed $request
     * @return void
     */
    public function login(LoginRequest $request)
    {
        $user = User::where('email', $request->email)->first();

        if ($user)
        {
            if (Hash::check($request->password, $user->password))
            {
                return response()->json([
                    'success'           =>  true,
                    'token'             =>  $user->createToken($request->device_name)->plainTextToken
                ]);
            }
        }

        return response()->json([
            'success'       =>  false,
            'message'       =>  'Invalid credentials.'
        ], 401);
    }

    public function logout(Request $request)
    {
        $logout = $request->user()->currentAccessToken()->delete();

        return response()->json([
            'success'       =>  $logout,
            'message'       =>  'You have been logged out.'
        ]);
    }
}
