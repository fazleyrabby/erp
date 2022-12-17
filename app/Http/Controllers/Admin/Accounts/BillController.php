<?php

namespace App\Http\Controllers\Admin\Accounts;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Bill\Bill;
use App\Models\Bill\BillDetails;
use App\Models\Bill\BillPayment;
use App\Models\Bill\BillPaymentDetails;
use App\Models\Accounts\ChartOfAccounts;
use App\Models\Crm\Party;
use App\Models\Accounts\Voucher;
use App\Models\Accounts\PaymentVoucher;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use PDF;
use Carbon\Carbon;
use Illuminate\Support\Facades\Session;

class BillController extends Controller
{
    
    public function index(){
        return view('admin.bills.billView');
    }





    public function create(){
        $expense=ChartOfAccounts::where('name','=','Expense')->first();
        $expense_id=$expense->id;
        $coas=ChartOfAccounts::where('deleted','=','No')->where('status','=','Active')->orderBy('code', 'asc')->where('parent_id','=',$expense_id )->get();
        $suppliers=Party::where('party_type','=','Supplier')->where('deleted','=','No')->where('status','=','Active')->get();
        
        return view('admin.bills.billCreate',['coas'=>$coas,'suppliers'=>$suppliers]);
    }




    public function getBill(){

        $bills= DB::table('tbl_acc_bills')
            ->leftjoin('parties','parties.id','=','tbl_acc_bills.tbl_crm_vendor_id')
            ->select('tbl_acc_bills.*','parties.name')
            ->where('tbl_acc_bills.deleted','=','No')
            ->orderby('tbl_acc_bills.id','Desc')
            ->get();
        $output = array('data' => array());
        $i=1;
        foreach ($bills as $bill) {
        $status = "";
        if($bill->status == 'Active'){
        $status = '<center><i class="fas fa-check-circle" style="color:green; font-size:16px;" title="'.$bill->status.'"></i></center>';
        }else{
        $status = '<center><i class="fas fa-times-circle" style="color:red; font-size:16px;" title="'.$bill->status.'"></i></center>';
        }

        $button = '<div class="btn-group">
            <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
            <i class="fas fa-cog"></i>  <span class="caret"></span></button>
            <ul class="dropdown-menu dropdown-menu-right" style="border: 1px solid gray;" role="menu">
            <li class="action"><a href="#/" onclick="seeBills('.$bill->id.')" class="btn"><i class="fas fa-print"></i> See Details </a></li>

        </li>

        </ul>
        </div>';         

        if($bill->payment_status == 'Due'){
            $paymentStatus='<span class="text-danger" >'.$bill->payment_status.'</span>';
        }else{
            $paymentStatus='<span class="text-success" >'.$bill->payment_status.'</span>';
        }


        $output['data'][] = array(
        $i++. '<input type="hidden" name="id" id="id" value="'.$bill->id.'" />',
        $bill->name,
        $bill->transaction_date,
        $bill->particulars,
        $bill->amount,
        $paymentStatus,
        $status,
        $button
        );            
        }
        return $output;

    }





    public function store(Request $request){
        $request->validate([ 
            'bill_date'             => 'required',
            'due_date'              => 'required',
            'vendor_id'             => 'required',
            'reference'             => 'nullable',
            'particulars'           => 'nullable',
            'address'               => 'nullable',
            'amountTotal'           => 'required'
        ]);

        $bills = new Bill();
        $bills->transaction_date=$request->bill_date;
        $bills->due_date=$request->due_date;
        $bills->tbl_crm_vendor_id=$request->vendor_id;
        $bills->reference=$request->reference;
        $bills->particulars=$request->particulars;
        $bills->amount=$request->amountTotal;
        $bills->due_amount=$request->amountTotal;
        $bills->address=$request->address;
        $bills->payment_status="Due";
        $bills->deleted="No";
        $bills->status="Active";
        $bills->created_by=Auth::user()->id;
        $bills->created_date=date('Y-m-d h:s');
        $bills->save();
        $last_id=$bills->id;

        /* bill details */
        for($i=0;$i < count($request->account);$i++){
            $item_array = [
                'tbl_acc_bill_id'       => $last_id,
                'transaction_date'      => $request->bill_date,
                'tbl_acc_coa_id'        => $request->account[$i],
                'particulars'           => $request->particular[$i],
                'amount'                => $request->amount[$i],
                'deleted'               => 'No',
                'status'                => 'Active',
                'created_by'            => Auth::user()->id,
                'created_date'          => date('Y-m-d h:s')
              ];
              DB::table('tbl_acc_bill_details')->insert($item_array);
        } 

        /* voucher entry */
        $voucher=new Voucher();
        $voucher->vendor_id=$request->vendor_id;
        $voucher->amount=$request->amountTotal;
        $voucher->transaction_date=$request->bill_date;
        $voucher->type_no=$last_id;
        $voucher->type='Bill created';
        $voucher->deleted="No";
        $voucher->status="Active";
        $voucher->created_by=Auth::user()->id;
        $voucher->created_date=date('Y-m-d h:s');
        $voucher->save();
        $voucherId=$voucher->id;

        /* voucher details entry */
        for($j=0;$j < count($request->account);$j++){  
            $item_array = [
                'tbl_acc_voucher_id'    => $voucherId,
                'tbl_acc_coa_id'        => $request->account[$j],
                'debit'                 => $request->amount[$j],
                'particulars'           => $request->particular[$j],
                'voucher_title'         => 'Bill created with voucher '.$voucherId,
                'deleted'               => 'No',
                'status'                => 'Active',
                'created_by'            => Auth::user()->id,
                'created_date'          => date('Y-m-d h:s')
            ];
            DB::table('tbl_acc_voucher_details')->insert($item_array);

            $expense=ChartOfAccounts::find($request->account[$j]);
            $expense->increment('amount',$request->amount[$j]);
        }


        /* Payment voucher  */
        $maxCode = PaymentVoucher::max('voucherNo');
        $maxCode++;
        $maxCodes=str_pad($maxCode, 6, '0', STR_PAD_LEFT);
        $PaymentVoucher = new PaymentVoucher();
        $PaymentVoucher->party_id = $request->vendor_id;
        $PaymentVoucher->voucherNo =$maxCodes;
        $PaymentVoucher->amount =$request->amountTotal;
        $PaymentVoucher->created_by = Auth::user()->id;
        $PaymentVoucher->paymentDate = $request->bill_date;
        $PaymentVoucher->bill_id = $last_id;
        $PaymentVoucher->type = "Payable";
        $PaymentVoucher->voucherType = "Bill";
        $PaymentVoucher->customerType = "Party";

        if($request->particulars){
            $PaymentVoucher->remarks=$request->particulars;
        }else{
            $PaymentVoucher->remarks = "Bill created for billing";
        }

        $PaymentVoucher->deleted="No";
        $PaymentVoucher->status="Active";
        $PaymentVoucher->created_by=Auth::user()->id;
        $PaymentVoucher->created_date=date('Y-m-d h:s');
        $PaymentVoucher->save();

            return  redirect('account/bills/view')->with('message','Bill saved sucessfully');
    }






    public function seeDetails($id){
        
        $details = DB::table('tbl_acc_bill_details')
            ->leftjoin('tbl_acc_coas','tbl_acc_coas.id','=','tbl_acc_bill_details.tbl_acc_coa_id')
            ->select('tbl_acc_bill_details.*','tbl_acc_coas.name as coa_name')
            ->where('tbl_acc_bill_details.deleted','No')
            ->where('tbl_acc_bill_details.tbl_acc_bill_id', $id)
            ->get();
            
        $bills=Bill::find($id);
        $payment=0;
        $paymentDetails=BillPaymentDetails::where('tbl_acc_bill_id','=',$id)->get();
        foreach($paymentDetails as $payments){
            $payment +=$payments->amount;
        }
        $party=Party::find($bills->tbl_crm_vendor_id);
        $pdf = PDF::loadView('admin.bills.billPdf',  ['details'=>$details,'bills'=>$bills,'party'=>$party,'payment'=>$payment]);
        return $pdf->stream('bill-report-pdf.pdf', array("Attachment" => false)); 

    }












    public function payBills(){
        $expense=ChartOfAccounts::where('name','=','Expense')->first();
        $expense_id=$expense->id;
        $bank=ChartOfAccounts::where('name','=','Bank')->first();
        $bank_id=$bank->id;
        $coas=ChartOfAccounts::where('deleted','=','No')->where('status','=','Active')->orderBy('code', 'asc')->where('parent_id','=',$expense_id )->get();
        $suppliers=Party::where('party_type','=','Supplier')->where('deleted','=','No')->where('status','=','Active')->get();
        $banks=ChartOfAccounts::where('deleted','=','No')->where('status','=','Active')->orderBy('code', 'asc')->where('parent_id','=',$bank_id )->where('name','!=','Cash')->get();
        $cashId=ChartOfAccounts::where('name','=','Cash')->first();
        return view('admin.bills.billPay',['coas'=>$coas,'suppliers'=>$suppliers,'banks'=>$banks,'cashId'=>$cashId]);
    }




    public function getBillData(Request $request){
        $bills=Bill::where('tbl_crm_vendor_id','=',$request->vendor_id)
                    ->where('payment_status','=','Due')
                    ->where('deleted','=','No')
                    ->where('status','=','Active')
                    ->get();
            $output='';
            $tfoot='';
            $totalAmount=0;

            foreach($bills as $bill){

            $output.='<tr class="row0">
                        <td><input type="hidden" name="billId[]" value='.$bill->id.'>'.$bill->id.'</td>
                        <td>'.$bill->due_date.'</td>
                        <td><input type="hidden" name="totalAmount[]" id="totalAmount" value='.$bill->amount.'>'.$bill->amount.'</td>
                        <td><input class="form-control" type="hidden" name="dueAmount[]" id="dueAmountInput" value='.$bill->due_amount.' style="text-align:right;"><span id="dueAmount">'.$bill->due_amount.'</span></td>
                        <td><input type="number" class="form-control" name="amount[]" id="amount"  oninput="totalBalance()" onchange="dueTotal()" style="text-align:right;" value='.$bill->due_amount.' ></td>
                        <td>
                            <label style="display:none;">.</label><br><br>
                            <a href="#/" class="text-danger" onclick="remove_btn(this)"><i class="fas fa-trash"></i></a>
                        </td>
                    </tr>';
                    $totalAmount += $bill->due_amount;
            }
            $tfoot .='<tr>
                        <td>#</td>
                        <td colspan="3" style="text-align:right;">Total:</td>
                        <td>
                            <input type="text" class="form-control" name="amountTotal" id="amountTotal"   style="text-align:right;" value='.$totalAmount.' readonly>
                        </td>
                        <td></td>
                    </tr>';
          $data =array(
            'output'    =>$output,
            'tfoot'     => $tfoot
          );
        return $data;
    }






public function billPayStore(Request $request){

        $request->validate([ 
            'payment_date'          => 'required',
            'vendor_id'             => 'required',
            'reference'             => 'nullable',
            'payment_method'        => 'required',
            'account_status'        => 'required',
            'transaction_id'        => 'nullable',
        ]);

            $billPayments=new BillPayment();
            $billPayments->tbl_acc_vendor_id=$request->vendor_id;
            $billPayments->payment_date=$request->payment_date;
            $billPayments->reference=$request->reference;
            $billPayments->payment_method=$request->payment_method;
            $billPayments->account_status=$request->account_status;
            $billPayments->save();
            $lastId=$billPayments->id;
        
    
    /* bill payment details */
    for($i=0;$i < count($request->billId);$i++){
        $item_array = [
            'tbl_acc_billPayment_id' => $lastId,
            'tbl_acc_bill_id'        => $request->billId[$i],
            'amount'                 => $request->amount[$i],
            'deleted'                => 'No',
            'status'                 => 'Active',
            'created_by'             => Auth::user()->id,
            'created_date'           => date('Y-m-d h:s')
          ];
        DB::table('tbl_acc_bill_payment_details')->insert($item_array);
        
        if($request->amount == $request->dueAmount){
            $bills=Bill::find($request->billId[$i]);
            $bills->due_amount=$request->dueAmount[$i] - $request->amount[$i];
            $bills->payment_status='Paid';
            $bills->save();
        }else{
            $bills=Bill::find($request->billId[$i]);
            $bills->due_amount=$request->dueAmount[$i] - $request->amount[$i];
            $bills->payment_status='Due';
            $bills->save();
        }
    }

    /* voucher  entry */
    for($k=0;$k < count($request->amount);$k++){ 
            $vouchers=new Voucher(); 
            $vouchers->vendor_id=$request->vendor_id;
            $vouchers->amount=$request->amount[$k];
            $vouchers->transaction_date=date('Y-m-d h:s');
            $vouchers->payment_method=$request->payment_method;
            $vouchers->type_no=$lastId;
            $vouchers->type='Bill paid';
            $vouchers->deleted="No";
            $vouchers->status="Active";
            $vouchers->created_by=Auth::user()->id;
            $vouchers->created_date=date('Y-m-d h:s');
            $vouchers->save();
            $voucherId=$vouchers->id;
    }

     /* voucher details entry */
     for($j=0;$j < count($request->amount);$j++){  
        $item_array = [
            'tbl_acc_voucher_id'    => $voucherId,
            'tbl_acc_coa_id'        => $request->payment_method,
            'credit'                => $request->amount[$j],
            'voucher_title'         => 'Bill paid with voucher '.$voucherId,
            'deleted'               => 'No',
            'status'                => 'Active',
            'created_by'            => Auth::user()->id,
            'created_date'          => date('Y-m-d h:s')
        ];
        DB::table('tbl_acc_voucher_details')->insert($item_array);

            $expense=ChartOfAccounts::find($request->account_status);
            $expense->decrement('amount',$request->amountTotal);
    }

        /* Payment voucher  */
        $maxCode = PaymentVoucher::max('voucherNo');
            $maxCode++;
            $maxCodes=str_pad($maxCode, 6, '0', STR_PAD_LEFT);
        $PaymentVoucher = new PaymentVoucher();
            $PaymentVoucher->party_id = $request->vendor_id;
            $PaymentVoucher->voucherNo =$maxCodes;
            $PaymentVoucher->amount =$request->amountTotal;
            $PaymentVoucher->payment_method = $request->payment_method;
            $PaymentVoucher->accountNo =$request->account_status;
            $PaymentVoucher->created_by = Auth::user()->id;
            $PaymentVoucher->paymentDate = $request->payment_date;
            $PaymentVoucher->bill_id = $lastId;
            $PaymentVoucher->type = "Payment";
            $PaymentVoucher->voucherType = "Bill";
            $PaymentVoucher->customerType = "Party";
            if($request->particulars){
                $PaymentVoucher->remarks=$request->particulars;
            }else{
                $PaymentVoucher->remarks = "Bill paid for bill no";
            }
            $PaymentVoucher->deleted="No";
            $PaymentVoucher->status="Active";
            $PaymentVoucher->created_by=Auth::user()->id;
            $PaymentVoucher->created_date=date('Y-m-d h:s');
            $PaymentVoucher->save();

    return  redirect('account/bills/view')->with('message','Bill paid successfully');
}




}
