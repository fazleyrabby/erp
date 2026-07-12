<?php

namespace App\Http\Controllers\Admin\Accounts;

use App\Http\Controllers\Controller;
use App\Models\Accounts\ChartOfAccounts;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AccountController extends Controller
{
    public function index(Request $request)
    {
        $searchTerm = $request->q;
        $sortBy = $request->sort_by ?? 'code';
        $sortDirection = $request->sort_direction ?? 'asc';
        $limit = $request->limit ?? 10;

        $coas = ChartOfAccounts::where('deleted', 'No')->where('status', 'Active');

        if ($searchTerm) {
            $coas->where(function ($q) use ($searchTerm) {
                $q->where('name', 'like', "%{$searchTerm}%")
                  ->orWhere('code', 'like', "%{$searchTerm}%");
            });
        }

        $coas = $coas->orderBy($sortBy, $sortDirection)
            ->paginate($limit)
            ->appends($request->all());

        $allCoas = ChartOfAccounts::where('deleted', 'No')->where('status', 'Active')->orderBy('code', 'asc')->get();

        return view('admin.account.chartOfAccount', compact('coas', 'allCoas'));
    }

    public function getCOA()
    {
        $coas = ChartOfAccounts::where('deleted', '=', 'No')->orderBy('code', 'asc')->get();
        $output = ['data' => []];
        $i = 1;
        foreach ($coas as $coa) {
            $status = '';
            if ($coa->status == 'Active') {
                $status = '<center><i class="fas fa-check-circle" style="color:green; font-size:16px;" title="'.$coa->status.'"></i></center>';
            } else {
                $status = '<center><i class="fas fa-times-circle" style="color:red; font-size:16px;" title="'.$coa->status.'"></i></center>';
            }
            /*$button = '<button type="button"  class="btn btn-xs btn-warning btnEdit" title="Edit Party" ><i class="fa fa-edit"> </i></button>
                        <button type="button" title="Delete" id="delete" class="btn btn-xs btn-danger btnDelete" onclick="" title="Delete Party"><i class="fa fa-trash"> </i></button>';*/
            $button = '<div class="btn-group">
            <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
                <i class="fas fa-cog"></i>  <span class="caret"></span></button>
                <ul class="dropdown-menu dropdown-menu-right" style="border: 1px solid gray;" role="menu">

<li class="action"><a href="#/" onclick="editCOA('.$coa->id.')" class="btn"><i class="fas fa-edit"></i> Edit </a></li>
<li class="action"><a href="#/" class="btn" onclick="confirmDelete('.$coa->id.')"><i class="fas fa-trash"></i> Delete </a></li>
                </li>

                </ul>
            </div>';
            if ($coa->parent_id == '0') {
                $text = 'font-weight-bold';
            } else {
                $text = 'font-weight-normal';
            }
            $output['data'][] = [
                $i++.'<input type="hidden" name="id" id="id" value="'.$coa->id.'" />',
                '<span class='.$text.'>'.$coa->name.'</span>',
                $coa->code,
                $status,
                $button,
            ];
        }

        return $output;
    }

    public function getCodeRange(Request $request)
    {
        $ranges = ChartOfAccounts::find($request->parent_id);

        return $ranges;
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255|regex:/^([a-zA-Z0-9_ "\.\-\s\,\;\:\/\&\$\%\(\)]+\s)*[a-zA-Z0-9_ "\.\-\s\,\;\:\/\&\$\%\(\)]+$/u',
            'slug' => 'required',
            'code' => 'numeric|required',
            'parent_id' => 'numeric|required',
        ]);

        $coas = new ChartOfAccounts;
        $coas->name = $request->name;
        $coas->slug = $request->slug;
        $coas->code = $request->code;
        $coas->parent_id = $request->parent_id;

        if ($request->parent_id == '0' || $request->parent_id == 'null') {
            $assetCode = '';
            $assets = ChartOfAccounts::select(DB::raw('IFNULL(MAX(substr(our_code, 1,1)),0)+1 as code'))
                ->WHERE('parent_id', '0')
                ->WHERE('deleted', 'No')
                ->toSql();

            return $assets;
            foreach ($assets as $asset) {
                $assetCode = $asset->code;
            }
            if ($assetCode < 10) {
                $trimedAsset = str_pad($assetCode, 9, '0');
            } else {
                return 'Its not possible to generate more then 9 nonparent chart of accounts head';
            }
        } else {
            $parent = ChartOfAccounts::find($request->parent_id);
            $parentCode = $parent->our_code;
            $trimedParentCode = rtrim($parentCode, '0');
            $bStart = $trimedParentCode.'01';
            $bEnd = $trimedParentCode.'99';

            $startRange = str_pad($bStart, 9, '0');
            $endRange = str_pad($bEnd, 9, '0');
            // $b=1;
            $assets = ChartOfAccounts::where('deleted', '=', 'No')
                ->where('status', '=', 'Active')
                ->where('parent_id', '=', $request->parent_id)
                // ->whereBetween('our_code', [$startRange, $endRange])
                ->max('our_code');

            if ($assets == null) {
                $trimedAsset = str_pad(($trimedParentCode.'01'), 9, '0');
            } else {
                $trimedAsset = rtrim($assets, '0');
                $trimedAsset++;
                $trimedAsset = str_pad($trimedAsset, 9, '0');
            }
        }

        $coas->our_code = $trimedAsset;
        if ($request->parent_id == '0') {
            $coas->unused = 'No';
        } else {
            $coas->unused = 'Yes';
        }
        $coas->deleted = 'No';
        $coas->status = 'Active';
        $coas->created_by = Auth::user()->id;
        $coas->save();
        if ($request->parent_id != '0') {
            $change_parents_status = ChartOfAccounts::find($request->parent_id);
            $change_parents_status->unused = 'No';
            $change_parents_status->save();
        }

        return response()->json(['success' => $request->name.' saved successfully']);
    }

    public function edit(Request $request)
    {
        $coas = ChartOfAccounts::find($request->id);

        return $coas;
    }

    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255|regex:/^([a-zA-Z0-9_ "\.\-\s\,\;\:\/\&\$\%\(\)]+\s)*[a-zA-Z0-9_ "\.\-\s\,\;\:\/\&\$\%\(\)]+$/u',
            'slug' => 'required',
            'code' => 'numeric|required',
            'parent_id' => 'numeric|required',
        ]);

        $coas = ChartOfAccounts::find($request->id);
        $coas->name = $request->name;
        $coas->slug = $request->slug;
        $coas->code = $request->code;
        $coas->parent_id = $request->parent_id;
        if ($request->status) {
            $coas->status = $request->status;
        }
        $coas->last_updated_by = Auth::user()->id;
        $coas->save();

        return response()->json(['success' => $request->name.' saved successfully']);
    }

    public function delete(Request $request)
    {

        $coas = ChartOfAccounts::find($request->id);
        $coas->status = 'Inactive';
        $coas->deleted = 'Yes';
        $coas->deleted_date = date('Y-m-d h:s');
        $coas->deleted_by = Auth::user()->id;
        $coas->save();

        return response()->json(['success' => 'Deleted successfully']);
    }
}
