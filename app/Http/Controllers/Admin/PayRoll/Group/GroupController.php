<?php

namespace App\Http\Controllers\Admin\PayRoll\Group;

use App\Http\Controllers\Controller;
use App\Models\payroll\Group;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GroupController extends Controller
{
    public function index(Request $request)
    {
        $searchTerm = $request->q;
        $sortBy = $request->sort_by ?? 'id';
        $sortDirection = $request->sort_direction ?? 'DESC';
        $limit = $request->limit ?? 10;

        $query = Group::where('deleted', 'No');

        if ($searchTerm) {
            $query->where(function ($q) use ($searchTerm) {
                $q->where('name', 'like', "%{$searchTerm}%");
            });
        }

        $groups = $query->orderBy($sortBy, $sortDirection)
            ->paginate($limit)
            ->appends($request->all());

        return view('admin.payroll.groups.groupsView', compact('groups'));
    }

    public function create()
    {

        $count = Email::where('replied_by', null)->count();

        return view('admin.payroll.groups.groupsCreate', ['count' => $count]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255|unique:groups,name',
            'note' => 'nullable|max:255|regex:/^([a-zA-Z0-9_ "\.\-\s\,\;\:\/\&\$\%\(\)]+\s)*[a-zA-Z0-9_ "\.\-\s\,\;\:\/\&\$\%\(\)]+$/u',
        ]);
        $group = new Group;
        $group->name = $request->name;
        $group->note = $request->note;
        $group->created_by = Auth::user()->id;
        $group->status = 'Active';
        $group->deleted = 'No';
        $group->save();

        return response()->json(['success' => $request->name.' Saved successfully']);

    }

    public function edit(Request $request)
    {
        $group = Group::find($request->id);

        return $group;
        // $count= Email::where('replied_by',NULL)->count();
        // return view('admin.payroll.groups.groupEdit',['group'=>$group,'count'=>$count]);
    }

    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255|regex:/^([a-zA-Z0-9_ "\.\-\s\,\;\:\/\&\$\%\(\)]+\s)*[a-zA-Z0-9_ "\.\-\s\,\;\:\/\&\$\%\(\)]+$/u|unique:groups,name,'.$request->id,
            'note' => 'nullable|max:255|regex:/^([a-zA-Z0-9_ "\.\-\s\,\;\:\/\&\$\%\(\)]+\s)*[a-zA-Z0-9_ "\.\-\s\,\;\:\/\&\$\%\(\)]+$/u',
        ]);
        $group = Group::find($request->id);
        $group->name = $request->name;
        $group->note = $request->note;
        $group->status = $request->status;
        $group->last_updated_by = Auth::user()->id;
        $result = $group->save();

        return response()->json(['success' => $request->name.' updated successfully']);
    }

    public function delete(Request $request)
    {

        $group = Group::find($request->id);
        $group->name = $group->name.'deleted'.$request->id;
        $group->status = 'Inactive';
        $group->deleted = 'Yes';
        $group->deleted_by = Auth::user()->id;
        $group->deleted_date = date('Y-m-d H:i:s');
        $group->save();

        return response()->json(['success' => 'Group deleted successfully']);
        /*if($result){
            return back()->with('message', 'Group deleted!');

        } else {
            return back()->with('message', 'Something went wrong!');

        }

        return redirect('groupIndex')->with('message', 'Group In-Active secessfully');*/
    }
}
