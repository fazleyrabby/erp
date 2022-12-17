<?php

namespace App\Http\Controllers\Admin\Setup;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateCompanySetting;
use Illuminate\Http\Request;
use App\Models\CompanySetting;
use Illuminate\Support\Facades\Session;


class CompanySettingController extends Controller
{

	function __construct()
    {
        $this->middleware('permission:companySetting.view', ['only' => ['index', 'edit']]);
        $this->middleware('permission:companySetting.update', ['only' => ['update']]);
    }

    public function index(){
    	return view('admin.setups.companySettings.company-settings');
	}

    public function edit(Request $request){
    	$shop =CompanySetting::find($request->id);
    	return $shop;
    }
    
    public function update(UpdateCompanySetting $request)
    {
		$company = CompanySetting::find($request->id);
		$company->name = $request->companyName;
		$company->email = $request->companyEmail;
		$company->phone = $request->companyPhone;
		$company->address = $request->companyAddress;
		$company->website = $request->companyWebsite;
		$company->month_year = $request->month_year;
		$company->report_header = $request->company_report_header;
		$company->report_footer =$request->company_report_footer;
		$company->terms_conditions = $request->company_terms_conditions;
		$company->manage_stock_to_sale = $request->companyStockManage;
		$company->barcode_exists = $request->companyBarcode;
		$company->default_party = $request->default_party;
		$company->currency = $request->currency;
      	if($request->hasFile('companyWatermark')){
          	$companyWatermark = $request->file('companyWatermark');
        	$name = $companyWatermark->getClientOriginalName();
          	$uploadPath = 'upload/images/';
	        $imageUrl = $uploadPath.$name;
	        $imageName = time().$name;
	        $companyWatermark->move($uploadPath, $imageName);
	        $company->watermark=$imageName;
	    }
    	if($request->hasFile('companyLogo')){
          	$companyLogo = $request->file('companyLogo');
        	$name = $companyLogo->getClientOriginalName();
          	$uploadPath = 'upload/images/';
	        $imageUrl = $uploadPath.$name;
	        $imageName = time().$name;
	        $companyLogo->move($uploadPath, $imageName);
	        $company->logo=$imageName;
	    }
    	if($request->hasFile('companyLogo_vertical')){
          	$vertical_logo = $request->file('companyLogo_vertical');
        	$name = $vertical_logo->getClientOriginalName();
          	$uploadPath = 'upload/images/';
	        $imageUrl = $uploadPath.$name;
	        $imageName = time().$name;
	        $vertical_logo->move($uploadPath, $imageName);
	        $company->vertical_logo=$imageName;
	    }
		$company->updated_by = auth()->user()->id;
		$company->updated_date = date('Y-m-d H:i:s');
		$company->save();
		$companySettings = CompanySetting::find(1);
		Session::forget('companySettings');
		Session::push('companySettings', $companySettings);
		return response()->json(['success'=>'Company settings updated successfully']);
    }
}
