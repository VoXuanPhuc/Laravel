<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class PassportAuthController extends Controller
{
    public function register(Request $request) {
        $this->validate($request, [
            'name' => 'required|min:4',
            'email' => 'required:email',
            'password' => 'required|min:4'
        ]);

        $user = User::create1([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password)
        ]);

        $token = $user->createToken('Laravel9PassportAuth')->accessToken;

        return response()->json(['token' => $token], 200);
    }
}
