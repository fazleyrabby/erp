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

    public function index(Request $request)
    {
        $query = permission::where('deleted', '=', 'No');

        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function ($qry) use ($q) {
                $qry->where('name', 'like', "%{$q}%")
                    ->orWhere('group_name', 'like', "%{$q}%");
            });
        }

        $sortBy = $request->get('sort_by', 'id');
        $sortDirection = $request->get('sort_direction', 'DESC');
        $limit = $request->get('limit', 10);

        $permissions = $query->orderBy($sortBy, $sortDirection)
            ->paginate($limit)
            ->appends($request->all());

        return view('admin.RolesPermissions.permission.permissionView', ['permissions' => $permissions]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'       => 'required|string|max:255',
            'group_name' => 'required|string|max:255',
        ]);

        try {
            // Create the group-level permission if it doesn't exist yet
            $permissionExist = permission::where('group_name', $request->group_name)
                ->where('name', $request->group_name)
                ->first();

            if (! $permissionExist) {
                permission::create([
                    'name'       => $request->group_name,
                    'group_name' => $request->group_name,
                    'deleted'    => 'No',
                    'status'     => 'Active',
                ]);
            }

            // Check if the exact permission name already exists
            $alreadyExists = permission::where('name', $request->name)->exists();

            if ($alreadyExists) {
                return redirect('permission/view')
                    ->with('error', 'Permission "' . $request->name . '" already exists.');
            }

            permission::create([
                'name'       => $request->name,
                'group_name' => $request->group_name,
                'deleted'    => 'No',
                'status'     => 'Active',
            ]);

            return redirect('permission/view')
                ->with('message', $request->name . ' saved successfully');

        } catch (\Spatie\Permission\Exceptions\PermissionAlreadyExists $e) {
            return redirect('permission/view')
                ->with('error', 'Permission "' . $request->name . '" already exists.');
        } catch (\Exception $e) {
            return redirect('permission/view')
                ->with('error', 'An error occurred: ' . $e->getMessage());
        }
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
