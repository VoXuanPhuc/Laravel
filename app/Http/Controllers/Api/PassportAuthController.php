<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PassportAuthController extends Controller
{
    public function register(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|min:4',
            'email' => 'required:email',
            'password' => 'required|min:4'
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password)
        ]);

        $token = $user->createToken('Laravel9PassportAuth')->accessToken;

        return response()->json(['token' => $token], 200);
    }


    public function login(Request $request)
    {
        $user = [
            'email' => $request->email,
            'password' => $request->password
        ];

        if (auth()->attempt($user)) {
            $userLogin = Auth::user();
            $token = $userLogin->createToken('myapp')->accessToken;
            $response = [
                "user" => $userLogin,
                "access_token" => $token
            ];
            return response()->json($response, 200);
        } else {
            return response()->json(['error' => 'Unauthorised'], 401);
        }
    }

    public function logout(Request $request)
    {
        auth()->user()->token()->revoke();
        return response()->json(['isSuccess' => true, 'message' => 'logout']);
    }
}
