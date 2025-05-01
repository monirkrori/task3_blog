<?php

namespace App\Http\Controllers;

abstract class Controller
{

    public function responseWitSuccess ($data = [] , $status = 200,$message = '')
    {
        return response()->json([
            'data' => $data,
            'message' => $message,
        ], $status);
    }
    public function responseWithToken($token , $user ,$message,$status )
    {
        return response()->json([
            'user' => $user,
            'access_token' => $token,
            'token_type' => 'bearer',
            'message' => $message,
        ],$status);
    }

    public function responseWithError($message,$status)
    {
        return response()->json([
            'message' => $message,
        ],$status);
    }
}
