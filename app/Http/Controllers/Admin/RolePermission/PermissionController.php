<?php

namespace App\Http\Controllers\Admin\RolePermission;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\permission;

class PermissionController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:permission.view|permission.store|permission.delete', ['only' => ['index']]);
        $this->middleware('permission:permission.store', ['only' => ['store']]);
        $this->middleware('permission:permission.edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:permission.delete', ['only' => ['delete']]);
    }

    public function index()
    {
        $permissions = permission::where('deleted', '=', 'No')->get();

        return view('admin.rolesPermissions.permission.permissionView', ['permissions' => $permissions]);
    }

    public function store(Request $request)
    {

        $permissionExist = permission::where('group_name', $request->group_name)->first();
        if (! $permissionExist) {
            $permission = permission::create([
                'name' => $request->group_name,
                'group_name' => $request->group_name,
                'deleted' => 'No',
                'status' => 'Active',
            ]);
        }
        $permissions = permission::create([
            'name' => $request->name,
            'group_name' => $request->group_name,
            'deleted' => 'No',
            'status' => 'Active',
        ]);

        return redirect('permission/view')->with('message', $request->name.' saved sucessfully');
    }

    public function edit(Request $request)
    {
        $permissions = permission::find($request->id);

        return $permissions;
    }

    public function update(Request $request)
    {
        $permissions = permission::find($request->editId);
        $permissions->name = $request->editName;
        $permissions->group_name = $request->editGroup_name;

        $permissions->updated_by = auth()->user()->id;
        $permissions->save();

        return redirect('permission/view')->with('message', $request->name.' updated sucessfully');
    }

    public function delete($id)
    {
        $permissions = permission::find($id);
        $permissions->deleted = 'Yes';
        $permissions->status = 'Inactive';
        $permissions->deleted_by = auth()->user()->id;
        $permissions->save();

        return redirect('permission/view')->with('message', 'Permission deleted sucessfully');
    }
}
