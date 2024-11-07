<?php

namespace App\Http\Controllers;

use App\Exceptions\AuthException;
use App\Helpers\DateFormatter;
use App\Http\Requests\ValidationLogin;
use App\Http\Requests\ValidationUser;
use App\Models\Person;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{

    public function first(ValidationUser $request)
    {
        $user = User::first();
        if ($user)
            return ["messaje" => "No se permiten mas registros root de usuario."];
        return $this->register($request);
    }

    public function register(ValidationUser $request)
    {
        $user = new User();
        $user->email = strtolower($request->email);
        $user->password = bcrypt($request->password);
        $user->person_id  = $request->person_id;
        $user->created_at = DateFormatter::today();
        $user->updated_at = DateFormatter::today();
        $user->save();
        return $user;
    }

    public function login(ValidationLogin $request)
    {

        if (!Auth::attempt($request->only('email', 'password'))) {
            throw new AuthException('Credenciales incorrectas.');
        }

        $user = Auth::user();

        $token = $user->createToken('auth_token')->plainTextToken;

        $userAuthenticated = [
            'id' => $user->id,
            'email' => $user->email,
            'token' => $token,
        ];
        return $userAuthenticated;
    }
}
