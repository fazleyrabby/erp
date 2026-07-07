<?php

namespace App\Http\Controllers\Admin\PayRoll\Group;

use App\Http\Controllers\Controller;
use App\Models\payroll\Group;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GroupController extends Controller
{
    public function index()
    {

        return view('admin.payroll.groups.groupsView');
        /**/
        // $count= Email::where('replied_by',NULL)->count();
        // return view('admin.payroll.groups.groupsView',['group'=>$group]);
    }

    public function getGroups()
    {

        $groups = Group::where('deleted', '=', 'No')->orderBy('id', 'ASC')->get();
        $output = ['data' => []];
        $i = 1;
        foreach ($groups as $group) {
            $status = '';
            if ($group->status == 'Active') {
                $status = '<center><i class="fas fa-check-circle" style="color:green; font-size:16px;" title="'.$group->status.'"></i></center>';
            } else {
                $status = '<center><i class="fas fa-times-circle" style="color:red; font-size:16px;" title="'.$group->status.'"></i></center>';
            }
            /*$button = '<button type="button"  class="btn btn-xs btn-warning btnEdit" title="Edit Party" ><i class="fa fa-edit"> </i></button>
                        <button type="button" title="Delete" id="delete" class="btn btn-xs btn-danger btnDelete" onclick="" title="Delete Party"><i class="fa fa-trash"> </i></button>';*/
            $button = '<div class="btn-group">
            <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
                <i class="fas fa-cog"></i>  <span class="caret"></span></button>
                <ul class="dropdown-menu dropdown-menu-right" style="border: 1px solid gray;" role="menu">

<li class="action"><a href="#/" onclick="editGroup('.$group->id.')" class="btn"><i class="fas fa-edit"></i> Edit </a></li>
<li class="action"><a href="#/" class="btn" onclick="confirmDelete('.$group->id.')"><i class="fas fa-trash"></i> Delete </a></li>
                </li>

                </ul>
            </div>';
            $output['data'][] = [
                $i++.'<input type="hidden" name="id" id="id" value="'.$group->id.'" />',
                $group->name,
                $group->note,
                $status,
                $button,
            ];
        }

        return $output;
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
