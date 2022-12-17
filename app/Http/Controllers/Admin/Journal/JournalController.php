<?php

namespace App\Http\Controllers\Admin\Journal;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Journal\Journal;
use App\Models\Journal\JournalDetails;
use App\Models\Accounts\ChartOfAccounts;
use App\Models\Accounts\Voucher;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use PDF;
use Carbon\Carbon;
use Illuminate\Support\Facades\Session;

class JournalController extends Controller
{
    
    public function index(){
        $coas=ChartOfAccounts::where('deleted','=','No')->where('status','=','Active')->get();
        return view('admin.journal.journalView',['coas'=>$coas]);
    }




    public function getJournalData(){

        $journals=Journal::where('deleted','=','No')->orderby('id','Desc')->get();
        $output = array('data' => array());
        $i=1;
        foreach ($journals as $journal) {
            $status = "";
            if($journal->status == 'Active'){
                $status = '<center><i class="fas fa-check-circle" style="color:green; font-size:16px;" title="'.$journal->status.'"></i></center>';
            }else{
                $status = '<center><i class="fas fa-times-circle" style="color:red; font-size:16px;" title="'.$journal->status.'"></i></center>';
            }
			/*$button = '<button type="button"  class="btn btn-xs btn-warning btnEdit" title="Edit Party" ><i class="fa fa-edit"> </i></button>
                        <button type="button" title="Delete" id="delete" class="btn btn-xs btn-danger btnDelete" onclick="" title="Delete Party"><i class="fa fa-trash"> </i></button>';*/
            $button = '<div class="btn-group">
            <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
                <i class="fas fa-cog"></i>  <span class="caret"></span></button>
                <ul class="dropdown-menu dropdown-menu-right" style="border: 1px solid gray;" role="menu">
                    <li class="action">
                        <a href="#/" onclick="journalDetails('.$journal->id.')" class="btn"><i class="fa fa-file-pdf"></i> See Details </a>
                    </li>
                </ul>
            </div>';            
			$output['data'][] = array(
				$i++. '<input type="hidden" name="id" id="id" value="'.$journal->id.'" />',
				$journal->transaction_date,
				$journal->reference,
				$journal->internal_information,
				$status,
				$button
			);            
        }
        return $output;
    }


    public function create(){
        $coas=ChartOfAccounts::where('deleted','=','No')->where('status','=','Active')->orderBy('code', 'asc')->get();
        return view('admin.journal.journalAdd',['coas'=>$coas]);
    }




    public function store(Request $request){
        
        $request->validate([
            'transaction_date' => 'required',
            'reference' => 'nullable',
            'internal_information' => 'nullable',
            'account' => 'required',
            'particular' => 'required',
            'debit' => 'required',
            'credit' => 'required',
            'debitTotal' => 'required',
            'creditTotal' => 'required'
        ]);

        if($request->debitTotal == $request->creditTotal){
            $journals=new Journal();
            $journals->transaction_date=$request->transaction_date;
            $journals->reference =$request->reference;
            $journals->internal_information=$request->internal_information;
            $journals->voucher_amount=$request->debitTotal;
            $journals->deleted="No";
            $journals->status="Active";
            $journals->created_by=Auth::user()->id;
            $journals->created_date=date('Y-m-d h:s');
            $journals->save();
            $last_id=$journals->id;

            /* journal details table entry */
            for($i=0;$i < count($request->account);$i++){
                $item_array = [
                    'tbl_acc_journal_id'    => $last_id,
                    'tbl_acc_coa_id'        => $request->account[$i],
                    'particulars'           => $request->particular[$i],
                    'debit'                 => $request->debit[$i],
                    'credit'                => $request->credit[$i],
                    'deleted'               => 'No',
                    'status'                => 'Active',
                    'created_by'            => Auth::user()->id,
                    'created_date'          => date('Y-m-d h:s')
                  ];
                  DB::table('tbl_acc_jornal_details')->insert($item_array);
            }

        /*  $voucherData = new Request();
            $voucherData->vendor_id = 0;
            $voucherData->transaction_date=$request->transaction_date;
            $voucherData->purchase_amount =$request->debitTotal;
            enterPurchaseVoucher($voucherData); 
        */

            $voucher=new Voucher();
            $voucher->amount=$request->debitTotal;
            $voucher->transaction_date=$request->transaction_date;
            $voucher->type_no=$last_id;
            $voucher->type='Journal';
            $voucher->deleted="No";
            $voucher->status="Active";
            $voucher->created_by=Auth::user()->id;
            $voucher->created_date=date('Y-m-d h:s');       
            $voucher->save();
            $voucherId=$voucher->id; 

           /* voucher table entry */
             for($j=0;$j < count($request->account);$j++){  
                $item_array = [
                    'tbl_acc_voucher_id'    => $voucherId,
                    'tbl_acc_coa_id'        => $request->account[$j],
                    'debit'                 => $request->debit[$j],
                    'credit'                => $request->credit[$j],
                    'particulars'           => $request->particular[$j],
                    'voucher_title'         => 'Journal created with voucher '.$voucherId,
                    'deleted'               => 'No',
                    'status'                => 'Active',
                    'created_by'            => Auth::user()->id,
                    'created_date'          => date('Y-m-d h:s')
                  ];
                  DB::table('tbl_acc_voucher_details')->insert($item_array);

                    $expense=ChartOfAccounts::find($request->account[$j]);
                    $expense->increment('amount',$request->debit[$j]);

                    $expense=ChartOfAccounts::find($request->account[$j]);
                    $expense->decrement('amount',$request->credit[$j]);
                } 

            return  redirect('journal/view')->with('message','Journal saved sucessfully');
        }else{
            return back()->with('message','Debit and Credit is not equal');
        }
    }







    public function seeDetails($id){
        $details = DB::table('tbl_acc_jornal_details')
        ->join('tbl_acc_jornals','tbl_acc_jornal_details.tbl_acc_journal_id','=','tbl_acc_jornals.id')
        ->leftjoin('tbl_acc_coas','tbl_acc_coas.id','=','tbl_acc_jornal_details.tbl_acc_coa_id')
        ->select('tbl_acc_jornal_details.*','tbl_acc_jornals.transaction_date','tbl_acc_jornals.reference','tbl_acc_jornals.internal_information','tbl_acc_jornals.voucher_amount','tbl_acc_coas.name' )
        ->where('tbl_acc_jornal_details.deleted','No')
        ->where('tbl_acc_jornal_details.tbl_acc_journal_id', $id)
        ->get();
        $journals=Journal::find($id);

    $pdf = PDF::loadView('admin.journal.journalPDF',  ['details'=>$details,'journals'=>$journals]);
    return $pdf->stream('journal-report-pdf.pdf', array("Attachment" => false)); 
    }






public function equalization(Request $request){
    $button='';
    $txt='';
    if($request->debitTotal == $request->creditTotal){
       $button='<button type="submit" class="btn btn-primary float-right  m-2" > <i class="fas fa-save"></i> Save</button>';
        $txt='<span class="text-success float-right">Debit and credit is equal now.You can save..</span>';
    }else{
        $button='<button type="submit" class="btn btn-secondary float-right  m-2" disabled> <i class="fas fa-save"></i> Save</button> ';
        $txt='<span class="text-danger float-right">Debit and credit is not equal.You can not save..</span>';
    }
    $data=array(
        'button' => $button,
        'text'   => $txt
    );
   return $data;
}




}
