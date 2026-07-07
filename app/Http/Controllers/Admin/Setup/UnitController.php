<?php

namespace App\Http\Controllers\Admin\Setup;

use App\Http\Controllers\Controller;
use App\Models\inventory\Unit;
use Illuminate\Http\Request;

class UnitController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:units.view', ['only' => ['index', 'getUnits']]);
        $this->middleware('permission:units.store', ['only' => ['store']]);
        $this->middleware('permission:units.edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:units.delete', ['only' => ['delete']]);
    }

    public function index()
    {
        return view('admin.setups.units.view-units');
    }

    public function getUnits()
    {
        $data = '';
        $units = Unit::where('deleted', 'No')->orderBy('id', 'DESC')->get();
        $output = ['data' => []];
        $i = 1;
        foreach ($units as $unit) {
            $status = '';
            if ($unit->status == 'Active') {
                $status = '<center><i class="fas fa-check-circle" style="color:green; font-size:16px;" title="'.$unit->status.'"></i></center>';
            } else {
                $status = '<center><i class="fas fa-times-circle" style="color:red; font-size:16px;" title="'.$unit->status.'"></i></center>';
            }
            /* $button = '<button type="button" onclick="editUnit('.$unit->id.')" class="btn btn-xs btn-warning btnEdit" title="Edit Record" ><i class="fa fa-edit"> </i></button>
                        <button type="button" title="Delete" id="delete" class="btn btn-xs btn-danger btnDelete" onclick="confirmDelete('.$unit->id.')" title="Delete Record"><i class="fa fa-trash"> </i></button>'; */

            $button = '<td style="width: 12%;">
            <div class="btn-group">
                <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
                    <i class="fas fa-cog"></i>  <span class="caret"></span></button>
                    <ul class="dropdown-menu dropdown-menu-right" style="border: 1px solid gray;" role="menu">
                    <li class="action" onclick="editUnit('.$unit->id.')"  ><a  class="btn" ><i class="fas fa-edit"></i> Edit </a></li>
                    </li>
                </li> 
                    <li class="action"><a   class="btn"  onclick="confirmDelete('.$unit->id.')" ><i class="fas fa-trash-alt"></i> Delete </a></li>
                    </li>
                    </ul>
                </div>
            </td>';

            $output['data'][] = [
                $i++.'<input type="hidden" name="id" id="id" value="'.$unit->id.'" />',
                $unit->name,
                $status,
                $button,
            ];
        }

        return $output;
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255|unique:units,name|regex:/^([a-zA-Z0-9_ "\.\-\s\,\;\:\/\&\$\%\(\)]+\s)*[a-zA-Z0-9_ "\.\-\s\,\;\:\/\&\$\%\(\)]+$/u',
        ]);
        $unit = new Unit;
        $unit->name = $request->name;
        $unit->created_by = auth()->user()->id;
        $unit->created_date = date('Y-m-d H:i:s');
        $unit->deleted = 'No';
        $unit->save();

        return response()->json(['success' => 'Unit saved successfully']);
    }

    public function edit(Request $request)
    {
        $unit = Unit::find($request->id);

        return $unit;

    }

    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255|regex:/^([a-zA-Z0-9_ "\.\-\s\,\;\:\/\&\$\%\(\)]+\s)*[a-zA-Z0-9_ "\.\-\s\,\;\:\/\&\$\%\(\)]+$/u|unique:units,name,'.$request->id,
        ]);
        $unit = Unit::find($request->id);
        $unit->name = $request->name;
        $unit->status = $request->status;
        $unit->updated_by = auth()->user()->id;
        $unit->updated_date = date('Y-m-d H:i:s');
        $unit->save();

        return response()->json(['success' => 'Unit updated successfully']);
    }

    public function delete(Request $request)
    {
        $unit = Unit::find($request->id);
        $unit->deleted = 'Yes';
        $unit->name = $unit->name.'-Deleted-'.$request->id;
        $unit->deleted_by = auth()->user()->id;
        $unit->deleted_date = date('Y-m-d H:i:s');
        $unit->save();

        return response()->json(['success' => 'Unit deleted successfully']);
    }
}
