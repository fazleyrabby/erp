<?php

namespace App\Http\Controllers\Admin\Accounts;

use App\Http\Controllers\Controller;
use App\Models\Accounts\ChartOfAccounts;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BankController extends Controller
{
    public function index(Request $request)
    {
        $searchTerm = $request->q;
        $sortBy = $request->sort_by ?? 'id';
        $sortDirection = $request->sort_direction ?? 'DESC';
        $limit = $request->limit ?? 10;

        $config = DB::table('tbl_acc_configurations')
            ->where('tbl_acc_configurations.name', '=', 'Bank')
            ->first();
        $configId = $config->tbl_acc_coa_id;

        $banks = ChartOfAccounts::where('parent_id', $configId)
            ->where('deleted', 'No')
            ->where('status', 'Active');

        if ($searchTerm) {
            $banks->where('name', 'like', "%{$searchTerm}%");
        }

        $banks = $banks->orderBy($sortBy, $sortDirection)
            ->paginate($limit)
            ->appends($request->all());

        $bankChildsData = [];
        foreach ($banks as $bank) {
            $bankChildsData[$bank->id] = ChartOfAccounts::where('parent_id', $bank->id)
                ->where('deleted', 'No')
                ->where('status', 'Active')
                ->get();
        }

        return view('admin.banks.bankView', compact('banks', 'bankChildsData'));
    }

    public function geData()
    {

        $config = DB::table('tbl_acc_configurations')
            ->where('tbl_acc_configurations.name', '=', 'Bank')
            ->first();

        $configId = $config->tbl_acc_coa_id;

        $banks = ChartOfAccounts::where('parent_id', '=', $configId)->where('deleted', '=', 'No')->where('status', '=', 'Active')->get();
        // $banks=ChartOfAccounts::where('our_code','>','500000000')->where('our_code','<','600000000')->where('deleted','=','No')->where('status','=','Active')->get();
        $output = ['data' => []];
        $i = 1;

        foreach ($banks as $bank) {

            $bankChilds = ChartOfAccounts::where('parent_id', '=', $bank->id)
                ->where('deleted', '=', 'No')
                ->where('status', '=', 'Active')
                ->get();

            $status = '';
            if ($bank->status == 'Active') {
                $status = '<center><i class="fas fa-check-circle" style="color:green; font-size:16px;" title="'.$bank->status.'"></i></center>';
            } else {
                $status = '<center><i class="fas fa-times-circle" style="color:red; font-size:16px;" title="'.$bank->status.'"></i></center>';
            }

            $button = '<div class="btn-group">
                        <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
                        <i class="fas fa-cog"></i>  <span class="caret"></span></button>
                        <ul class="dropdown-menu dropdown-menu-right" style="border: 1px solid gray;" role="menu">
                            <li class="action"><a href="#/" onclick="seeBills('.$bank->id.')" class="btn"><i class="fa fa-file-pdf-o"></i> See Details </a></li>
                        </ul>
                    </div>';

            $childName = '';
            foreach ($bankChilds as $child) {
                $childName = $childName.'<br>'.$child->name.'--<b>Amount:</b>'.$child->amount;
            }

            $output['data'][] = [
                $i++.'<input type="hidden" name="id" id="id" value="'.$bank->id.'" />',
                '<span>'.$bank->name.''.$childName.'</span>',
                $status,
            ];

        }

        return $output;
    }
}
