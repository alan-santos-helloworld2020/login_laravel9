<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{

    public function cadastro(Request $request)
    {
        //
        $fields = $request->validate([
            "name" => "required|string",
            "email" => "required|string|unique:users,email",
            "password" => "required|string|confirmed",
        ]);

        $user = User::create([
            "name" => $fields["name"],
            "email" => $fields["email"],
            "password" => bcrypt($fields["password"]),

        ]);

        return response($user, 201);
    }

    public function login(Request $request)
    {
        $fields = $request->validate([
            "email" => "required|string",
            "password" => "required|string",
        ]);

        $user = User::where("email", $fields["email"])->first();

        if (!$user || !Hash::check($fields["password"], $user->password)) {
            return response([
                "message" => "não autorizado"
            ], 401);
        }

        $token = $user->createToken("UserLogado")->plainTextToken;
        $result = [
            "user" => $user,
            "token" => $token
        ];
        return response($result,201);
    }
}
