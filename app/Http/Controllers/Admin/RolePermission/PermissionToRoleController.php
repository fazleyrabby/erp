<?php

namespace App\Http\Controllers\Admin\RolePermission;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\permission;
use Spatie\Permission\Models\Role;

class PermissionToRoleController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:permissionToRole.view', ['only' => ['index', 'getPermission']]);
        $this->middleware('permission:permissionToRole.store', ['only' => ['store']]);
        $this->middleware('permission:permissionToRole.delete', ['only' => ['delete']]);
    }

    public function index()
    {
        $permissions = permission::where('deleted', '=', 'No')->get();
        $roles = Role::where('deleted', '=', 'No')->get();
        $permission_groups = User::getPermissionGroups();

        return view('admin.rolesPermissions.permission.permissionToRoleList', ['permissions' => $permissions, 'roles' => $roles, 'permission_groups' => $permission_groups]);
    }

    public function store(Request $request)
    {

        $request->validate([
            'role_id' => 'required',
        ]);

        $role = Role::find($request->role_id);

        $permissions = $request->input('permissions');

        if (! empty($permissions)) {
            $role->syncPermissions($permissions);

            return redirect('permission/to/role/view')->with('message', 'Permission Assigned sucessfully');
        }
    }

    public function getPermission(Request $request)
    {
        $permissions = DB::table('role_has_permissions')
            ->leftjoin('permissions', 'permissions.id', '=', 'role_has_permissions.permission_id')
            ->select('role_has_permissions.*', 'permissions.name as permissionName')
            ->where('role_has_permissions.role_id', '=', $request->id)
            ->get();

        return $permissions;
    }
}
