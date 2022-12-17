<?php

namespace App\Models\Accounts;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ChartOfAccounts extends Model
{
    use HasFactory;
    protected $table="tbl_acc_coas";

     public static function incomeFunctionAmounts($dateFrom,$dateTo,$incomeId){
        $incomeAmounts=DB::table('tbl_acc_voucher_details')
                    ->join('tbl_acc_vouchers','tbl_acc_voucher_details.tbl_acc_voucher_id','=','tbl_acc_vouchers.id')
                    ->select('tbl_acc_voucher_details.*','tbl_acc_vouchers.transaction_date')
                    ->where('tbl_acc_vouchers.transaction_date','>=',$dateFrom)
                    ->where('tbl_acc_vouchers.transaction_date','<=',$dateTo)
                    ->where('tbl_acc_voucher_details.tbl_acc_coa_id','=',$incomeId)
                    ->where('tbl_acc_vouchers.deleted','=','No')
                    ->where('tbl_acc_vouchers.status','=','Active')
                    ->get();
        return $incomeAmounts;
    } 
}
