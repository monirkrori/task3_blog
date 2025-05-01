<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(RegisterRequest $request){

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return $this->responseWithToken($token, $user,'Register successfully',200);
    }

    public function login(LoginRequest $request){
        $user = User::where('email', $request->email)
            ->first();

        if(!$user || !Hash::check($request->password , $user->password)){
            return $this->responseWithError('Invalid credentials',401);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return $this->responseWithToken($token, $user,'Login successfully',200);
    }

    public function logout(Request $request){

        $request->user()->tokens()->delete();

        return $this->responseWitSuccess('Logged out successfully',200);
    }
}



