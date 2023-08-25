<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateRoleRequest;
use App\Http\Requests\CreateUserRequest;
use App\Models\RoleHasPermissions;
use Illuminate\Http\Request;
use J3dyy\LaravelAdminuka\App\Models\Posts;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesController extends Controller
{

    public function index()
    {
        $list = Role::paginate(10);
        return view('manager.pages.roles.list',['list'=>$list]);
    }

    public function create()
    {
        $permissions = Permission::all();
        return view('manager.pages.roles.form', [
            'permissions' => $permissions
        ]);
    }

    public function edit(int $id)
    {
        $entity = Role::with('permissions')->findOrFail($id);
        $permissions = Permission::all();

        return view('manager.pages.roles.form',[
            'entity'=>$entity,
            'permissions'=>$permissions
        ]);
    }

    public function store(CreateRoleRequest $request)
    {
        $role = new Role();

        if ($request->id){
            $role = Role::findOrFail($request->id);
        }

        $role->name = $request->name;
        $role->save();



        return response()->json([
            'success'=>true,
            'message'=>'User Created',
            'redirect'=> route('manager.roles.index')
        ]);
    }

    public function updatePermission(Request $request)
    {
        $rolePerms = RoleHasPermissions::where('role_id','=',$request->id)
            ->where('permission_id','=',$request->permId)->first();

        if ($rolePerms == null){
            $rolePerms = new RoleHasPermissions();
            $rolePerms->permission_id = $request->permId;
            $rolePerms->role_id = $request->id;
        }

        $rolePerms->{$request->action} = $request->value;

        $rolePerms->save();

        return response()->json([
            'success'=>true
        ]);

    }

    public function destroy($id){
        Role::destroy($id);

        return response()->json([
            'ids'=>[$id]
        ]);
    }

    public function bulkDelete(Request $request)
    {
        if ($request->ids && is_array($request->ids))
        {
            Role::destroy($request->ids);
        }

        return response()->json([
            'ids'=>$request->ids
        ]);
    }
}
