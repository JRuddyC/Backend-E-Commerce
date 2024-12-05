<?php

namespace App\Http\Controllers;

use App\Exceptions\AuthException;
use App\Helpers\DateFormatter;
use App\Http\Requests\ValidationLogin;
use App\Http\Requests\ValidationPersonRequest;
use App\Http\Requests\ValidationRegisterRequest;
use App\Http\Requests\ValidationUser;
use App\Models\Person;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use PhpParser\Node\Stmt\TryCatch;

class AuthController extends Controller
{

    public function verifyPerson(ValidationPersonRequest $request)
    {
        return response()->json(['message' => 'Datos correctos.'], 200);
    }
    public function verifyEmail(ValidationRegisterRequest $request)
    {
        return response()->json(['message' => 'Datos correctos.'], 200);
    }

    public function register(ValidationPersonRequest $personRequest, ValidationRegisterRequest $userRequest)
    {
        DB::beginTransaction();
        try {
            $person = new Person();
            $person->name = strtolower($personRequest->name);
            $person->first_surname = strtolower($personRequest->first_surname);
            $person->last_surname = strtolower($personRequest->last_surname);
            $person->ci = strtoupper($personRequest->ci);
            $person->birthdate = $personRequest->birthdate;
            $person->created_at = DateFormatter::today();
            $person->updated_at = DateFormatter::today();
            $person->save();

            $user = new User();
            $user->email = strtolower($userRequest->email);
            $user->password = bcrypt($userRequest);
            $user->person_id = $person->id;
            $user->created_at = DateFormatter::today();
            $user->updated_at = DateFormatter::today();
            $user->save();
            DB::commit();
            return response()->json(['message' => 'Persona y Usuario creados correctamente'], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Error al guardar los datos', 'details' => $e->getMessage()], 400);
        }
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
