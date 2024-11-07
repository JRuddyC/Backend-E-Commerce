<?php

namespace App\Http\Controllers;

use App\Http\Requests\ValidationAssignRoleRequest;
use App\Models\Person;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function show()
    {
        $users = User::with("roles")->get();
        return $users->makeHidden(['created_at', 'updated_at']);
    }

    public function find(String $id)
    {
        $user = User::find($id);

        if (!$user)
            return ["message" => "El usuario que esta buscando no existe."];
        return $user;
    }

    public function assignRole(ValidationAssignRoleRequest $request)
    {
        $user = User::find($request->user_id);
        $user->syncRoles($request->roles);
        $user->load("roles");
        return $user;
    }
}
