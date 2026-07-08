<?php

namespace App\Http\Controllers\Admin\Expense;

use App\Http\Controllers\Controller;
use App\Models\Accounts\ChartOfAccounts;
use App\Models\Accounts\PaymentVoucher;
use App\Models\Accounts\Voucher;
use App\Models\Expense\Expense;
use App\Models\payroll\OurTeam;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use PDF;

class ExpenseController extends Controller
{
    public function index(Request $request)
    {
        $searchTerm = $request->q;
        $sortBy = $request->sort_by ?? 'id';
        $sortDirection = $request->sort_direction ?? 'DESC';
        $limit = $request->limit ?? 10;

        $expenses = DB::table('tbl_acc_expenses')
            ->leftjoin('our_teams', 'tbl_acc_expenses.tbl_crm_vendor_id', '=', 'our_teams.id')
            ->select('tbl_acc_expenses.*', 'our_teams.member_name')
            ->where('tbl_acc_expenses.deleted', 'No');

        if ($searchTerm) {
            $expenses->where(function ($q) use ($searchTerm) {
                $q->where('tbl_acc_expenses.particulars', 'like', "%{$searchTerm}%")
                  ->orWhere('our_teams.member_name', 'like', "%{$searchTerm}%")
                  ->orWhere('tbl_acc_expenses.amount', 'like', "%{$searchTerm}%");
            });
        }

        $expenses = $expenses->orderBy($sortBy, $sortDirection)
            ->paginate($limit)
            ->appends($request->all());

        return view('admin.expense.expenseView', compact('expenses'));
    }

    public function getExpense()
    {

        $expenses = DB::table('tbl_acc_expenses')
            ->leftjoin('our_teams', 'tbl_acc_expenses.tbl_crm_vendor_id', '=', 'our_teams.id')
            ->select('tbl_acc_expenses.*', 'our_teams.member_name')
            ->where('tbl_acc_expenses.deleted', '=', 'No')
            ->orderby('tbl_acc_expenses.id', 'Desc')
            ->get();

        $output = ['data' => []];
        $i = 1;

        foreach ($expenses as $expense) {
            $status = '';
            if ($expense->status == 'Active') {
                $status = '<center><i class="fas fa-check-circle" style="color:green; font-size:16px;" title="'.$expense->status.'"></i></center>';
            } else {
                $status = '<center><i class="fas fa-times-circle" style="color:red; font-size:16px;" title="'.$expense->status.'"></i></center>';
            }

            $button = '<div class="btn-group">
                <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
                    <i class="fas fa-cog"></i>  
                    <span class="caret"></span>
                </button>
                <ul class="dropdown-menu dropdown-menu-right" style="border: 1px solid gray;" role="menu">
                    <li class="action">
                        <a href="#/" onclick="seeExpense('.$expense->id.')" class="btn">
                        <i class="fa fa-file-pdf"></i> See Details 
                        </a>
                    </li>
                </ul>
            </div>';
            $output['data'][] = [
                $i++.'<input type="hidden" name="id" id="id" value="'.$expense->id.'" />',
                $expense->member_name,
                $expense->transaction_date,
                $expense->particulars,
                $expense->amount,
                $status,
                $button,
            ];
        }

        return $output;
    }

    public function create()
    {
        $expense = ChartOfAccounts::where('name', '=', 'Expense')->first();
        $expense_id = $expense->id;
        $bank = ChartOfAccounts::where('name', '=', 'Bank')->first();
        $bank_id = $bank->id;
        $coas = ChartOfAccounts::where('deleted', '=', 'No')
            ->where('status', '=', 'Active')
            ->orderBy('our_code', 'asc')
            ->where('our_code', '>', '400000000')
            ->where('our_code', '<', '499999999')
            ->get();

        $suppliers = OurTeam::where('deleted', '=', 'No')->where('status', '=', 'Active')->get();
        $banks = ChartOfAccounts::where('deleted', '=', 'No')->where('status', '=', 'Active')->orderBy('id', 'asc')->where('parent_id', '=', $bank_id)->where('name', '!=', 'Cash')->get();
        $cashId = ChartOfAccounts::where('name', '=', 'Cash')->first();
        $methods = ChartOfAccounts::where('name', '=', 'Cash & Bank')->get();

        return view('admin.expense.expenseCreate', ['coas' => $coas, 'suppliers' => $suppliers, 'methods' => $methods, 'cashId' => $cashId]);
    }

    public function getAccountStatus(Request $request)
    {

        $coas = ChartOfAccounts::where('deleted', '=', 'No')
            ->where('status', '=', 'Active')
            ->orderBy('code', 'asc')
            ->where('parent_id', '=', $request->payment_method)
            ->get();

        $coa_data = "<option value='' selected disabled> Select accounts</option  >";
        foreach ($coas as $coa) {
            $coa_data .= "<option value='".$coa->id."'>".$coa->name.'</option>';
        }

        $status = $coa_data;

        $data = [
            'status' => $status,
        ];

        return $data;
    }

    public function getAmount(Request $request)
    {

        $coas = ChartOfAccounts::find($request->account_status);

        return $coas->amount;

    }

    public function store(Request $request)
    {

        $request->validate([
            'transaction_date' => 'required',
            'vendor_id' => 'required',
            'account.*' => ['required'],
            'reference' => 'nullable',
            'particulars' => 'nullable',
            'payment_method' => 'required',
            'account_status' => 'required',
            'credit_amount' => 'numeric',
            'address' => 'nullable',
        ]);

        $expense = new Expense;
        $expense->transaction_date = $request->transaction_date;
        $expense->tbl_crm_vendor_id = $request->vendor_id;
        $expense->reference = $request->reference;
        $expense->particulars = $request->particulars;
        $expense->payment_method = $request->payment_method;
        $expense->amount = $request->amountTotal;
        $expense->transaction_no = $request->transaction_id;
        $expense->address = $request->address;
        $expense->deleted = 'No';
        $expense->status = 'Active';
        $expense->created_by = Auth::user()->id;
        $expense->created_date = date('Y-m-d h:s');
        $expense->save();
        $last_id = $expense->id;

        /* add to expense details */
        for ($i = 0; $i < count($request->account); $i++) {
            $item_array = [
                'tbl_acc_expense_id' => $last_id,
                'tbl_acc_coa_id' => $request->account[$i],
                'particulars' => $request->particular[$i],
                'amount' => $request->amount[$i],
                'deleted' => 'No',
                'status' => 'Active',
                'created_by' => Auth::user()->id,
                'created_date' => date('Y-m-d h:s'),
            ];
            DB::table('tbl_acc_expense_details')->insert($item_array);
        }

        /* add to voucher */
        $voucher = new Voucher;
        $voucher->vendor_id = $request->vendor_id;
        $voucher->amount = $request->amountTotal;
        $voucher->transaction_date = $request->transaction_date;
        $voucher->payment_method = $request->payment_method;
        $voucher->type_no = $last_id;
        $voucher->type = 'Expense';
        $voucher->deleted = 'No';
        $voucher->status = 'Active';
        $voucher->created_by = Auth::user()->id;
        $voucher->created_date = date('Y-m-d h:s');
        $voucher->save();
        $voucherId = $voucher->id;

        /* voucher details table entry */
        for ($j = 0; $j < count($request->account); $j++) {
            $item_array = [
                'tbl_acc_voucher_id' => $voucherId,
                'tbl_acc_coa_id' => $request->account[$j],
                'credit' => $request->amount[$j],
                'particulars' => $request->particular[$j],
                'voucher_title' => 'Expense created with voucher '.$voucherId,
                'deleted' => 'No',
                'status' => 'Active',
                'created_by' => Auth::user()->id,
                'created_date' => date('Y-m-d h:s'),
            ];
            DB::table('tbl_acc_voucher_details')->insert($item_array);

            $expense = ChartOfAccounts::find($request->account[$j]);
            $expense->increment('amount', $request->amount[$j]);
        }

        $item_array_single = [
            'tbl_acc_voucher_id' => $voucherId,
            'tbl_acc_coa_id' => $request->payment_method,
            'debit' => $request->amountTotal,
            'voucher_title' => 'Expense paid with voucher '.$voucherId,
            'deleted' => 'No',
            'status' => 'Active',
            'created_by' => Auth::user()->id,
            'created_date' => date('Y-m-d h:s'),
        ];
        DB::table('tbl_acc_voucher_details')->insert($item_array_single);

        $expense = ChartOfAccounts::find($request->account_status);
        $expense->decrement('amount', $request->amountTotal);

        /* Payment voucher */
        $maxCode = PaymentVoucher::max('voucherNo');
        $maxCode++;
        $maxCodes = str_pad($maxCode, 6, '0', STR_PAD_LEFT);
        $PaymentVoucher = new PaymentVoucher;
        $PaymentVoucher->party_id = $request->vendor_id;
        $PaymentVoucher->voucherNo = $maxCodes;
        $PaymentVoucher->amount = $request->amountTotal;
        $PaymentVoucher->accountNo = $request->account_status;
        $PaymentVoucher->created_by = Auth::user()->id;
        $PaymentVoucher->payment_method = $request->payment_method;
        $PaymentVoucher->paymentDate = $request->transaction_date;
        $PaymentVoucher->expense_id = $last_id;
        $PaymentVoucher->type = 'Payment';
        $PaymentVoucher->voucherType = 'Expense';
        $PaymentVoucher->customerType = 'Party';
        if ($request->particulars) {
            $PaymentVoucher->remarks = $request->particulars;
        } else {
            $PaymentVoucher->remarks = 'Voucher Entry for Expense Cause';
        }
        $PaymentVoucher->deleted = 'No';
        $PaymentVoucher->status = 'Active';
        $PaymentVoucher->created_by = Auth::user()->id;
        $PaymentVoucher->entryBy = Auth::user()->id;
        $PaymentVoucher->created_date = date('Y-m-d h:s');
        $PaymentVoucher->save();

        return redirect('expense/View/')->with('message', 'Expense saved sucessfully');

    }

    public function seeDetails($id)
    {

        $details = DB::table('tbl_acc_expense_details')
            ->leftjoin('tbl_acc_coas', 'tbl_acc_coas.id', '=', 'tbl_acc_expense_details.tbl_acc_coa_id')
            ->select('tbl_acc_expense_details.*', 'tbl_acc_coas.name as coa_name')
            ->where('tbl_acc_expense_details.deleted', 'No')
            ->where('tbl_acc_expense_details.tbl_acc_expense_id', $id)
            ->get();

        $expenses = Expense::find($id);
        $party = OurTeam::find($expenses->tbl_crm_vendor_id);

        $pdf = PDF::loadView('admin.expense.expensePdf',  ['details' => $details, 'expenses' => $expenses, 'party' => $party]);

        return $pdf->stream('expense-report-pdf.pdf', ['Attachment' => false]);

    }
}
