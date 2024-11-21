<?php

namespace App\Http\Controllers;

use App\Helpers\DateFormatter;
use App\Http\Requests\AssingPermissionRequest;
use App\Http\Requests\validationPermission;
use Illuminate\Support\Facades\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionController extends Controller
{
    public function create(validationPermission $request)
    {
        $permission = new Permission();
        $permission->name = $request->name;
        $permission->created_at = DateFormatter::today();
        $permission->updated_at = DateFormatter::today();
        $permission->save();
        return $permission;
    }

    public function show()
    {
        $permissions = Permission::get();
        return $permissions->makeHidden(['created_at', 'updated_at']);
    }

    public function update(string $id, validationPermission $request)
    {
        $permission = Permission::find($id);
        $permission->name = $request->name;
        $permission->updated_at = DateFormatter::today();
        $permission->save();
        return $permission;
    }
    public function find(String $id)
    {
        $permission = Permission::find($id);

        if (!$permission)
            return ["message" => "El permiso que esta buscando no existe."];
        return $permission;
    }
}       
