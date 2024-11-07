<?php

namespace App\Http\Controllers;

use App\Helpers\DateFormatter;
use App\Http\Requests\validationRoleRequest;
use App\Http\Requests\ValidationRoleUpdateRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    public function create(validationRoleRequest $request)
    {
        $role =  new Role();
        $role->name = $request->name;
        $role->created_at = DateFormatter::today();
        $role->updated_at = DateFormatter::today();
        $role->syncPermissions($request->permissions);
        $role->load("permissions");
        return $role;
    }

    public function show()
    {
        $roles = Role::with([
            "permissions" => function ($query) {
                $query->select('id', 'name', 'role_id');
            }
        ])->get();
        return $roles->makeHidden(['created_at', 'updated_at']);
    }

    public function update(string $id, validationRoleRequest $request)
    {
        $role = Role::find($id);
        $role->name = $request->name;
        $role->updated_at = DateFormatter::today();
        $role->syncPermissions($request->permissions);
        $role->load("permissions");
        return $role;
    }
    public function find(String $id)
    {
        $role = Role::find($id);

        if (!$role)
            return ["message" => "El rol que esta buscando no existe."];
        return $role;
    }
}
