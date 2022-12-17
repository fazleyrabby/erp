<?php
use NumberToWords\NumberToWords;
use Carbon\Carbon;
use App\Models\Accounts\Voucher;
use App\Models\Accounts\VoucherDetails;
use App\Models\Accounts\AccountConfiguration;
use App\Models\Accounts\ChartOfAccounts;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

   

    
    function enterPurchaseVoucher(){

        /* $voucher = new Voucher();
        $voucher->vendor_id=$request->supplier_id;
        $voucher->transaction_date=$request->date;
        $voucher->amount=$request->$grand_total;
        $voucher->payment_method =$request->payment_method;
        $voucher->deleted="No";
        $voucher->status="Active";
        $voucher->created_by=Auth::user()->id;
        $voucher->created_date=date('Y-m-d h:s');       
        $voucher->save();       
        $voucherId=$voucher->id;
        
        $configId=AccountConfiguration::where('name','=','Purchase')->select('tbl_acc_coa_id')->first();
        
        $voucherDetails = new VoucherDetails();
        $voucherDetails->tbl_acc_voucher_id=$voucherId;
        $voucherDetails->tbl_acc_coa_id=$configId;
        $voucherDetails->debit= floatval($request->grand_total);
        $voucherDetails->voucher_title='Purchase created with voucher '.$voucherId;
        $voucherDetails->deleted="No";
        $voucherDetails->status="Active";
        $voucherDetails->created_by=Auth::user()->id;
        $voucherDetails->created_date=date('Y-m-d h:s');       
        $voucherDetails->save(); 

        if($request->payment > 0){
            $voucherDetails = new VoucherDetails();
            $voucherDetails->tbl_acc_voucher_id=$voucherId;
            $voucherDetails->tbl_acc_coa_id=$configId;
            $voucherDetails->credit=$current_payment;
            $voucherDetails->voucher_title='Purchase amount paid with voucher '.$voucherId;
            $voucherDetails->deleted="No";
            $voucherDetails->status="Active";
            $voucherDetails->created_by=Auth::user()->id;
            $voucherDetails->created_date=date('Y-m-d h:s');       
            $voucherDetails->save();
        } */
        /* accounts part start */
			$voucher = new Voucher();
			$voucher->vendor_id=$request->supplier_id;
			$voucher->transaction_date=$request->date;
			$voucher->amount=floatval($request->grand_total);
			$voucher->payment_method =$request->payment_method;
			$voucher->deleted="No";
			$voucher->status="Active";
			$voucher->created_by=Auth::user()->id;
			$voucher->created_date=date('Y-m-d h:s');       
			$voucher->save();       
			$voucherId=$voucher->id;

			$configId=AccountConfiguration::where('name','=','Purchase')->select('tbl_acc_coa_id')->first();

			$voucherDetails = new VoucherDetails();
			$voucherDetails->tbl_acc_voucher_id=$voucherId;
			$voucherDetails->tbl_acc_coa_id=$configId->id;
			$voucherDetails->debit= floatval($request->grand_total);
			$voucherDetails->voucher_title='Purchase created with voucher '.$voucherId;
			$voucherDetails->deleted="No";
			$voucherDetails->status="Active";
			$voucherDetails->created_by=Auth::user()->id;
			$voucherDetails->created_date=date('Y-m-d H:i:s');       
			$voucherDetails->save(); 

			if($request->current_payment > 0){
				$voucherDetails = new VoucherDetails();
				$voucherDetails->tbl_acc_voucher_id=$voucherId;
				$voucherDetails->tbl_acc_coa_id=$configId->id;
				$voucherDetails->credit=floatval($request->current_payment);
				$voucherDetails->voucher_title='Purchase amount paid with voucher '.$voucherId;
				$voucherDetails->deleted="No";
				$voucherDetails->status="Active";
				$voucherDetails->created_by=Auth::user()->id;
				$voucherDetails->created_date=date('Y-m-d h:s');       
				$voucherDetails->save();
			}   
		/* accounts part end */
        return enterPurchaseVoucher();
    }



    function enterSalesVoucher($request){

        $voucher = new Voucher();
        $voucher->vendor_id=$request->vendor_id;
        $voucher->transaction_date=$request->transaction_date;
        $voucher->amount=$request->sales_amount;
        $voucher->payment_method =$request->payment_method;
        $voucher->deleted="No";
        $voucher->status="Active";
        $voucher->created_by=Auth::user()->id;
        $voucher->created_date=date('Y-m-d h:s');       
        $voucher->save();       
        $voucherId=$voucher->id;

        $configId=AccountConfiguration::where('name','=','Sales')->select('tbl_acc_coa_id')->first();
        
        $voucherDetails = new VoucherDetails();
        $voucherDetails->tbl_acc_voucher_id=$voucherId;
        $voucherDetails->tbl_acc_coa_id=$configId;
        $voucherDetails->credit=$request->purchase_amount;
        $voucherDetails->voucher_title='Sales created with voucher '.$voucherId;
        $voucherDetails->deleted="No";
        $voucherDetails->status="Active";
        $voucherDetails->created_by=Auth::user()->id;
        $voucherDetails->created_date=date('Y-m-d h:s');       
        $voucherDetails->save();

        if($request->payment > 0){
            $voucherDetails = new VoucherDetails();
            $voucherDetails->tbl_acc_voucher_id=$voucherId;
            $voucherDetails->tbl_acc_coa_id=$configId;
            $voucherDetails->debit=$request->payment;
            $voucherDetails->voucher_title='Sales amount received with voucher '.$voucherId;
            $voucherDetails->deleted="No";
            $voucherDetails->status="Active";
            $voucherDetails->created_by=Auth::user()->id;
            $voucherDetails->created_date=date('Y-m-d h:s');       
            $voucherDetails->save();
        }
   
    }