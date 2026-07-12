<?php

namespace App\Http\Controllers\Admin\PayRoll\Setting;

use App\Http\Controllers\Controller;
use App\Models\payroll\PayrollSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PayrollSettingController extends Controller
{
    public function index()
    {
        $datas = PayrollSetting::first();

        return view('admin.payroll.PayrollSetting.payrollSettingView', ['datas' => $datas]);
    }

    public function deductDayUpdate(Request $request)
    {
        $validated = $request->validate([
            'absent' => 'numeric',
            'deduct_amount_for_absent' => 'numeric',
        ]);

        $data = PayrollSetting::find($request->id);
        $data->absent = $request->absent;
        $data->activation = $request->activation;
        $data->deduct_amount_for_absent = $request->deduct_amount_for_absent;
        $data->last_updated_by = Auth::user()->id;

        $result = $data->save();

        if ($result) {

            return redirect()->route('settingIndex')->with('message', '  Updated successfully!');

        } else {

            return back()->with('message', ' Something went wrong!');
        }

    }

    public function activationStatus(Request $request)
    {
        $settings = PayrollSetting::find(1);
        $settings->activation = $request->activation;
        $settings->save();

        return $settings->activation;
    }
}
