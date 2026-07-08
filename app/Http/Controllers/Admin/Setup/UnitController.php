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

    public function index(Request $request)
    {
        $query = Unit::where('deleted', 'No');

        if ($search = $request->q) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            });
        }

        $sortBy = $request->sort_by ?? 'id';
        $sortDir = $request->sort_direction ?? 'DESC';
        $limit = $request->limit ?? 10;

        $units = $query->orderBy($sortBy, $sortDir)->paginate($limit)->appends($request->all());

        return view('admin.setups.units.view-units', compact('units'));
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
