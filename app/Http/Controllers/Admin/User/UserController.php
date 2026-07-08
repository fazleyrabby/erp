<?php

namespace App\Http\Controllers\Admin\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Image;
use Spatie\Permission\Models\permission;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    // Check Permission
    public function __construct()
    {
        $this->middleware('permission:user.view', ['only' => ['index', 'usertypeIndex']]);
        $this->middleware('permission:user.store', ['only' => ['store']]);
        $this->middleware('permission:user.edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:user.delete', ['only' => ['delete']]);
        $this->middleware('permission:user.changePassword', ['only' => ['changePassword']]);
    }

    // View User Page
    public function index(Request $request)
    {
        $roles = Role::where('deleted', '=', 'No')->get();

        $searchTerm = $request->q;
        $sortBy = $request->sort_by ?? 'users.id';
        $sortDirection = $request->sort_direction ?? 'DESC';
        $limit = $request->limit ?? 10;

        $query = User::where('deleted', 'No');

        if ($searchTerm) {
            $query->where(function ($q) use ($searchTerm) {
                $q->where('name', 'like', "%{$searchTerm}%")
                  ->orWhere('email', 'like', "%{$searchTerm}%")
                  ->orWhere('mobile_no', 'like', "%{$searchTerm}%");
            });
        }

        $users = $query->orderBy($sortBy, $sortDirection)
            ->paginate($limit)
            ->appends($request->all());

        return view('admin.user.view-users', compact('roles', 'users'));
    }

    // View Usertype Page (Grouped)
    public function usertypeIndex(Request $request)
    {
        $searchTerm = $request->q;
        $sortBy = $request->sort_by ?? 'id';
        $sortDirection = $request->sort_direction ?? 'DESC';
        $limit = $request->limit ?? 10;

        $query = DB::table('users')
            ->select('id', 'usertype', DB::raw('COUNT(*) as user_count'))
            ->where('deleted', 'No')
            ->groupBy('usertype');

        if ($searchTerm) {
            $query->where('usertype', 'like', "%{$searchTerm}%");
        }

        $usertypes = $query->orderBy($sortBy, $sortDirection)
            ->paginate($limit)
            ->appends($request->all());

        return view('admin.user.view-usertype', compact('usertypes'));
    }

    // public function add()
    // {
    //     return User::all();
    // }

    // Save User
    public function store(Request $request)
    {

        $request->validate([
            'name' => 'required|max:255|regex:/^([a-zA-Z0-9_ "\.\-\s\,\;\:\/\&\$\%\(\)]+\s)*[a-zA-Z0-9_ "\.\-\s\,\;\:\/\&\$\%\(\)]+$/u',
            'email' => 'required|unique:users,email|min:3|regex:/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix',
            'mobile_no' => 'required|unique:users,mobile_no|max:14|min:11|regex:/^(?:\+?88)?01[11-9]\d{8}$/u',
            'usertype_id' => 'required',
            'password' => 'required',
            'role' => 'required',
            'address' => 'nullable|regex:/^([a-zA-Z0-9_ "\.\-\s\,\;\:\/\&\$\%\(\)]+\s)*[a-zA-Z0-9_ "\.\-\s\,\;\:\/\&\$\%\(\)]+$/u',
            'designation' => 'nullable|regex:/^([a-zA-Z0-9_ "\.\-\s\,\;\:\/\&\$\%\(\)]+\s)*[a-zA-Z0-9_ "\.\-\s\,\;\:\/\&\$\%\(\)]+$/u',
            'department' => 'nullable|regex:/^([a-zA-Z0-9_ "\.\-\s\,\;\:\/\&\$\%\(\)]+\s)*[a-zA-Z0-9_ "\.\-\s\,\;\:\/\&\$\%\(\)]+$/u',
        ]);

        $imageName = '';
        if ($request->hasFile('image')) {
            $request->validate([
                'image' => 'image|max:2048',
            ]);

            // --- Image resize And upload in public
            $userImage = $request->file('image');
            $name = $userImage->getClientOriginalName();
            $uploadPath = 'upload/user_images/';
            $uploadPathOriginal = 'upload/original_user_images/';
            $imageName = time().$name;
            $imageUrl = $uploadPath.$imageName;
            $imageOriginalUrl = $uploadPathOriginal.time().$name;
            // --resize image upload in public--//
            Image::make($userImage)->resize(100, 100)->save($imageUrl);
            // --original image upload in public--//
            $request->image->move(public_path($uploadPathOriginal), $imageName);
        } else {
            $imageName = 'no_image.png';
        }

        if ($request->hasFile('signature')) {
            /*  $request->validate([
                'signature'   =>  'signature|max:2048'
            ]);
             */

            // --- Image resize And upload in public
            $userSignature = $request->file('signature');
            $name = $userSignature->getClientOriginalName();
            $uploadPath = 'upload/user_signatures/';
            $uploadPathOriginal = 'upload/original_user_signatures/';
            $signatureName = time().$name;
            $signatureUrl = $uploadPath.$signatureName;
            $signatureOriginalUrl = $uploadPathOriginal.time().$name;
            // --resize signature upload in public--//
            Image::make($userSignature)->resize(100, 100)->save($signatureUrl);
            // --original signature upload in public--//
            $request->signature->move(public_path($uploadPathOriginal), $signatureName);
        } else {
            $signatureName = 'no_signature.png';
        }

        $user = new User;
        $user->name = $request->name;
        $user->email = $request->email;
        $user->department = $request->department;
        $user->role = $request->role;
        $user->address = $request->address;
        $user->usertype_id = $request->usertype_id;
        $user->designation = $request->designation;
        $user->mobile_no = $request->mobile_no;
        $user->image = $imageName;
        $user->signature = $signatureName;
        $user->password = Hash::make($request->password);
        $user->created_by = auth()->user()->id;
        $user->created_date = date('Y-m-d H:i:s');
        $user->deleted = 'No';
        $user->save();

        $user->assignRole($request->role);

        return response()->json(['success' => 'User saved successfully']);
    }

    // Edit User
    public function edit(Request $request)
    {
        $user = User::find($request->id);

        return $user;
    }

    // Update User
    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:users,name,'.$request->id.'|max:255|regex:/^([a-zA-Z0-9_ "\.\-\s\,\;\:\/\&\$\%\(\)]+\s)*[a-zA-Z0-9_ "\.\-\s\,\;\:\/\&\$\%\(\)]+$/u',
            'email' => 'required|unique:users,email,'.$request->id.'|min:3|regex:/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix',
            'role' => 'required',
            'mobile_no' => 'required|unique:users,mobile_no,'.$request->id.'|max:14|min:11|regex:/^(?:\+?88)?01[11-9]\d{8}$/u',
            'address' => 'nullable|regex:/^([a-zA-Z0-9_ "\.\-\s\,\;\:\/\&\$\%\(\)]+\s)*[a-zA-Z0-9_ "\.\-\s\,\;\:\/\&\$\%\(\)]+$/u',
            'designation' => 'nullable|regex:/^([a-zA-Z0-9_ "\.\-\s\,\;\:\/\&\$\%\(\)]+\s)*[a-zA-Z0-9_ "\.\-\s\,\;\:\/\&\$\%\(\)]+$/u',
            'department' => 'nullable|regex:/^([a-zA-Z0-9_ "\.\-\s\,\;\:\/\&\$\%\(\)]+\s)*[a-zA-Z0-9_ "\.\-\s\,\;\:\/\&\$\%\(\)]+$/u',
        ]);
        $user = User::find($request->id);
        if ($request->file('image')) {
            $request->validate([
                'image' => 'image|max:2048',
            ]);

            // --- Image resize And upload in public
            $oldUserImage = $user->image;
            $userImage = $request->file('image');
            $name = $userImage->getClientOriginalName();
            $uploadPath = 'upload/user_images/';
            $uploadPathOriginal = 'upload/original_user_images/';
            $imageName = time().$name;
            $imageUrl = $uploadPath.$imageName;
            $imageOriginalUrl = $uploadPathOriginal.time().$name;
            // --resize image upload in public--//
            Image::make($userImage)->resize(100, 100)->save($imageUrl);
            // --original image upload in public--//
            $request->image->move(public_path($uploadPathOriginal), $imageName);

            $user->image = $imageName;
        }

        if ($request->file('signature')) {

            // --- Image resize And upload in public
            $oldUserSignature = $user->signature;
            $userSignature = $request->file('signature');
            $name = $userSignature->getClientOriginalName();
            $uploadPath = 'upload/user_signatures/';
            $uploadPathOriginal = 'upload/original_user_signatures/';
            $signatureName = time().$name;
            $signatureUrl = $uploadPath.$signatureName;
            $signatureOriginalUrl = $uploadPathOriginal.time().$name;
            // --resize signature upload in public--//
            Image::make($userSignature)->resize(100, 100)->save($signatureUrl);
            // --original signature upload in public--//
            $request->signature->move(public_path($uploadPathOriginal), $signatureName);

            $user->signature = $signatureName;
        }

        $user->name = $request->name;
        $user->email = $request->email;
        $user->role = $request->role;
        $user->designation = $request->designation;
        $user->department = $request->department;
        $user->address = $request->address;

        $user->mobile_no = $request->mobile_no;
        $user->status = $request->status;
        $user->updated_by = auth()->user()->id;
        $user->updated_date = date('Y-m-d H:i:s');
        $user->save();

        $user->roles()->detach();
        if ($request->role) {
            $user->assignRole($request->role);
        }

        return response()->json(['success' => 'User updated successfully']);
    }

    // Delete User
    public function delete(Request $request)
    {
        $user = User::find($request->id);
        $user->deleted = 'Yes';
        $user->name = $user->name.'Deleted'.$request->id;
        $user->email = $user->email.'Deleted'.$request->id;
        $user->mobile_no = $user->mobile_no.'Deleted'.$request->id;
        $user->deleted_by = auth()->user()->id;
        $user->deleted_date = date('Y-m-d H:i:s');
        $user->save();

        return response()->json(['Success' => 'Deleted successfully']);
    }

    // Change User Password
    public function changePassword(Request $request)
    {
        $password = Hash::make($request->password);

        $user = User::find($request->userId);
        $user->password = $password;
        $user->updated_by = auth()->user()->id;
        $user->updated_date = Carbon::now();
        $user->save();

        return response()->json(['success' => 'User password updated successfully']);
    }
}
