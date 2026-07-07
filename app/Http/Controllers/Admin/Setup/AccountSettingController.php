<?php

namespace App\Http\Controllers\Admin\Setup;

use App\Http\Controllers\Controller;
use App\Models\Accounts\ChartOfAccounts;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AccountSettingController extends Controller
{
    public function index()
    {
        $coas = ChartOfAccounts::where('deleted', '=', 'No')
            ->where('status', '=', 'Active')
            ->where('parent_id', '=', '0')
            ->get();

        return view('admin.setups.accountSetting.accountSetting', ['coas' => $coas]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'account_id' => 'required',
            'limit_from' => 'required',
            'limit_to' => 'required',
        ]);

        for ($i = 0; $i < count($request->account_id); $i++) {
            $item_array = [
                'limit_from' => $request->limit_from[$i],
                'our_code' => $request->limit_from[$i],
                'limit_to' => $request->limit_to[$i],
            ];

            DB::table('tbl_acc_coas')
                ->where('id', $request->account_id[$i])
                ->update($item_array);
        }

        return redirect('account/settings/view')->with('message', 'Setting updated sucessfully');
    }
}
