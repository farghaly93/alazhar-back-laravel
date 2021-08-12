<?php

namespace App\Http\Controllers;

use App\Models\User;
use Exception;
use Facade\FlareClient\Http\Response;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

class authController extends Controller
{
    function login(Request $request) {
        try {
            $credentials = [
                "email" => $request->input("email"),
                "password" => $request->input("password"),
            ];
            if(!$token = JWTAuth::attempt($credentials)) {
                return response()->json(["error" => "Email or password is invalid"], 401);
            }
            return response()->json(['token'=> $token], 200);
        }
        catch(Exception $e) {
            return response()->json(["error" => $e->getMessage()], 500);
        }
    }

    function signup(Request $request) {
        try {
            $user = new User([
                'email' => $request->input('email'),
                'password' => bcrypt($request->input('password'))
            ]);
            $user->save();

            $credentials = [
                "email" => $request->input("email"),
                "password" => $request->input("password"),
            ];
            if(!$token = JWTAuth::attempt($credentials)) {
                return response()->json(["error" => "Email or password is invalid"], 401);
            }
            return response()->json(['token'=> $token], 200);
        }
        catch(Exception $e) {
            return response()->json(['signedUp'=> false], 403);
        }
    }
}
