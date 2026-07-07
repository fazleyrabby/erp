<?php

namespace App\Http\Controllers\Admin\RolePermission;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:role.view|role.store|role.delete', ['only' => ['index']]);
        $this->middleware('permission:role.store', ['only' => ['store']]);
        $this->middleware('permission:role-delete', ['only' => ['delete']]);
    }

    public function index()
    {
        $roles = Role::where('deleted', '=', 'No')->get();

        return view('admin.RolesPermissions.Roles.roleView', ['roles' => $roles]);
    }

    public function store(Request $request)
    {
        $roles = Role::create([
            'name' => $request->name,
            'deleted' => 'No',
            'status' => 'Active',
        ]);

        return redirect('role/view')->with('message', $request->name.' saved sucessfully');
    }

    public function delete($id)
    {
        $roles = Role::find($id);
        $roles->deleted = 'Yes';
        // $roles->status='Inactive';
        // $roles->deleted_by=auth()->user()->id;
        $roles->save();

        return redirect('role/view')->with('message', 'Roles deleted sucessfully');
    }
}
