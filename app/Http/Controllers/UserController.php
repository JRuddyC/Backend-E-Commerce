<?php

namespace App\Http\Controllers;

use App\Helpers\DateFormatter;
use App\Http\Requests\ValidationAssignRoleRequest;
use App\Http\Requests\ValidationUser;
use App\Models\User;

class UserController extends Controller
{
    public function show()
    {
        $users = User::with("roles")->get();
        return $users->makeHidden(['created_at', 'updated_at']);
    }


    public function create(ValidationUser $request)
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
