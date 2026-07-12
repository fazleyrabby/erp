<?php

namespace App\Http\Controllers\Admin\Accounts;

use App\Http\Controllers\Controller;
use App\Models\Accounts\AccountConfiguration;
use App\Models\Accounts\ChartOfAccounts;
use App\Models\Accounts\Transactions;
use App\Models\Accounts\Voucher;
use App\Models\Accounts\VoucherDetails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $searchTerm = $request->q;
        $sortBy = $request->sort_by ?? 'id';
        $sortDirection = $request->sort_direction ?? 'DESC';
        $limit = $request->limit ?? 10;

        $transactions = Transactions::query()
            ->with('coaTo')
            ->where('deleted', 'No')
            ->where('status', 'Active');

        if ($searchTerm) {
            $transactions->where(function ($q) use ($searchTerm) {
                $q->where('tbl_acc_transactions.transaction_id', 'like', "%{$searchTerm}%")
                    ->orWhere('tbl_acc_coas.name', 'like', "%{$searchTerm}%")
                    ->orWhere('tbl_acc_transactions.amount', 'like', "%{$searchTerm}%")
                    ->orWhere('tbl_acc_transactions.remarks', 'like', "%{$searchTerm}%");
            });
        }

        $transactions = $transactions->orderBy($sortBy, $sortDirection)
            ->paginate($limit)
            ->appends($request->all());

        $config = ChartOfAccounts::where('name', 'Bank')->first();
        $configId = $config->id;
        $banks = ChartOfAccounts::where('parent_id', $configId)
            ->where('deleted', 'No')
            ->where('status', 'Active')
            ->get();

        return view('admin.banks.transactions.transactionView', compact('transactions', 'banks'));
    }

    public function geData()
    {
        $transactions = Transactions::with('coaTo')
            ->where('deleted', 'No')
            ->where('status', 'Active')
            ->get();

        // $transactions='';
        $output = ['data' => []];
        $i = 1;

        foreach ($transactions as $transaction) {

            $status = '';
            if ($transaction->status == 'Active') {
                $status = '<center><i class="fas fa-check-circle" style="color:green; font-size:16px;" title="'.$transaction->status.'"></i></center>';
            } else {
                $status = '<center><i class="fas fa-times-circle" style="color:red; font-size:16px;" title="'.$transaction->status.'"></i></center>';
            }

            $button = '<div class="btn-group">
                        <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
                        <i class="fas fa-cog"></i>  <span class="caret"></span></button>
                        <ul class="dropdown-menu dropdown-menu-right" style="border: 1px solid gray;" role="menu">
                            <li class="action"><a href="#/" onclick="confirmDelete('.$transaction->id.')" class="btn"><i class="fas fa-trash"></i> Delete</a></li>
                        </ul>
                    </div>';

            $output['data'][] = [
                $i++.'<input type="hidden" name="id" id="id" value="'.$transaction->id.'" />',
                $transaction->transaction_id,
                $transaction->coaTo->name ?? '',
                $transaction->amount,
                $transaction->remarks,
                $transaction->transaction_date,
                $status,
                $button,
            ];
        }

        return $output;
    }

    public function getTransferFromSource(Request $request)
    {
        $sources = ChartOfAccounts::where('parent_id', '=', $request->payment_method)->toBase()->get();
        $data = '';
        $data .= '<option value=""selected >Select Source</option>';
        foreach ($sources as $source) {
            $data .= '<option value="'.$source->id.'">'.$source->name.'</option>';
        }

        return $data;
    }

    public function getTransferToSource(Request $request)
    {
        $sources = ChartOfAccounts::where('parent_id', '=', $request->payment_method)->toBase()->get();
        $data = '';
        $data .= '<option value=""selected >Select Source</option>';
        foreach ($sources as $source) {
            $data .= '<option value="'.$source->id.'">'.$source->name.'</option>';
        }

        return $data;
    }

    public function getTransferFromAcNo(Request $request)
    {
        $acNos = ChartOfAccounts::where('parent_id', '=', $request->source)->toBase()->get();

        $data = '';
        $data .= '<option value=""selected >Select Source</option>';
        foreach ($acNos as $ac) {
            $data .= '<option value="'.$ac->id.'">'.$ac->name.'</option>';
        }

        return $data;
    }

    public function getTransferToAcNo(Request $request)
    {
        $acNos = ChartOfAccounts::where('parent_id', '=', $request->source2)->where('parent_id', '!=', $request->source1)->toBase()->get();

        $data = '';
        $data .= '<option value=""selected >Select Source</option>';
        foreach ($acNos as $ac) {
            $data .= '<option value="'.$ac->id.'">'.$ac->name.'</option>';
        }

        return $data;
    }

    public function getFromAmount(Request $request)
    {
        $amount = ChartOfAccounts::where('id', '=', $request->transfer_from)->first();
        $fromAmount = $amount->amount;

        $config = ChartOfAccounts::where('name', 'Bank')->first();

        $configId = $config->id;

        $banks = ChartOfAccounts::where('parent_id', '=', $configId)
            ->where('deleted', '=', 'No')
            ->where('status', '=', 'Active')
            ->get();
        $data = '<option value=""selected >Select transfer to</option>';
        foreach ($banks as $bank) {
            $data .= '<option value="'.$bank->id.'">'.$bank->name.'</option>';
        }

        $output = [
            'fromAmount' => $fromAmount,
            'data' => $data,
        ];

        return $output;
    }

    public function getToAmount(Request $request)
    {
        $amount = ChartOfAccounts::where('id', '=', $request->transfer_to)->first();
        $toAmount = $amount->amount;

        $config = AccountConfiguration::where('name', 'Bank')->first();

        $configId = $config->tbl_acc_coa_id;

        $banks = ChartOfAccounts::where('our_code', '>', '500000000')
            ->where('parent_id', '!=', $configId)
            ->where('id', '!=', $request->transfer_from)
            ->where('our_code', '<', '600000000')
            ->where('deleted', '=', 'No')
            ->where('status', '=', 'Active')
            ->get();
        $data = '<option value=""selected disabled>Select transfer to</option>';
        foreach ($banks as $bank) {
            $data .= '<option value="'.$bank->id.'">'.$bank->name.'</option>';
        }

        $output = [
            'toAmount' => $toAmount,
            'data' => $data,
        ];

        return $output;
    }

    public function checkTransferLimit(Request $request)
    {
        $button = '';
        $text = '';
        $data = '';
        if ($request->amount != 0 && $request->amount > 0) {

            if ($request->transfer_from || $request->amount = null) {
                $transferFrom = ChartOfAccounts::find($request->transfer_from);
                $amount = $transferFrom->amount;

                if ($amount > $request->amount && $amount != 0) {
                    $button .= '<button type="submit" class="btn btn-primary btnSave" onclick="saveTransactions()"><i class="fas fa-save"></i> Save</button>';
                    $text .= '<span class="text-success">Now you can transfer this amount</span>';
                } else {
                    $button .= '<button type="submit" class="btn btn-secondary"  disabled><i class="fas fa-save"></i> Save</button>';
                    $text .= '<span class="text-danger">You do not have enough balance..</span>';
                }

            } else {
                $button .= '<button type="submit" class="btn btn-secondary"  disabled><i class="fas fa-save"></i> Save</button>';
                $text .= '<span class="text-danger">You did not select any COA</span>';
            }

        } else {
            $button .= '<button type="submit" class="btn btn-secondary"  disabled><i class="fas fa-save"></i> Save</button>';
            $text .= '<span class="text-danger">Minimum transaction 01.00 Tk</span>';
        }

        $data = [
            'button' => $button,
            'text' => $text,
        ];

        return $data;

    }

    public function store(Request $request)
    {

        $request->validate([
            'remarks' => 'nullable|regex:/^([a-zA-Z0-9_ "\.\-\s\,\;\:\/\&\$\%\(\)]+\s)*[a-zA-Z0-9_ "\.\-\s\,\;\:\/\&\$\%\(\)]+$/u',
            'transfer_from' => 'required',
            'tbl_coa_to_id' => 'required',
            'amount' => 'required|numeric',
            'transaction_date' => 'required',
        ]);

        if ($request->from_amount_bofore_transaction > $request->amount) {
            $transactions = new Transactions;
            $transactions->tbl_coa_from_id = $request->transfer_from;
            $transactions->tbl_coa_to_id = $request->tbl_coa_to_id;
            $transactions->amount = $request->amount;
            $transactions->transaction_date = $request->transaction_date;
            $transactions->remarks = $request->remarks;
            $transactions->to_amount_bofore_transaction = $request->to_amount_bofore_transaction;
            $transactions->from_amount_bofore_transaction = $request->from_amount_bofore_transaction;
            $transactions->transaction_id = rand(1, 1000000000000000);
            $transactions->deleted = 'No';
            $transactions->status = 'Active';
            $transactions->created_by = Auth::user()->id;
            $transactions->created_date = date('Y-m-d h:s');
            $transactions->save();

            $coasFrom = ChartOfAccounts::find($request->transfer_from);
            $coasFrom->decrement('amount', $request->amount);

            $coasTo = ChartOfAccounts::find($request->tbl_coa_to_id);
            $coasTo->increment('amount', $request->amount);

            /* add to voucher */
            $voucher = new Voucher;
            $voucher->amount = $request->amount;
            $voucher->transaction_date = $request->transaction_date;
            $voucher->payment_method = $request->transfer_from;
            $voucher->type_no = $transactions->id;
            $voucher->type = 'Bank Transaction';
            $voucher->deleted = 'No';
            $voucher->status = 'Active';
            $voucher->created_by = Auth::user()->id;
            $voucher->created_date = date('Y-m-d h:s');
            $voucher->save();
            $voucherId = $voucher->id;

            /* voucher details table entry */
            /* credit */
            $item_array_debit = [
                'tbl_acc_voucher_id' => $voucherId,
                'tbl_acc_coa_id' => $request->tbl_coa_to_id,
                'debit' => $request->amount,
                'particulars' => $request->remarks,
                'voucher_title' => 'Transaction created from COA '.$request->tbl_coa_to_id.' with voucher '.$voucherId,
                'deleted' => 'No',
                'status' => 'Active',
                'created_by' => Auth::user()->id,
                'created_date' => date('Y-m-d h:s'),
            ];
            VoucherDetails::insert($item_array_debit);
            /* debit */
            $item_array_credit = [
                'tbl_acc_voucher_id' => $voucherId,
                'tbl_acc_coa_id' => $request->transfer_from,
                'credit' => $request->amount,
                'voucher_title' => 'Transaction created from COA '.$request->transfer_from.' with voucher '.$voucherId,
                'deleted' => 'No',
                'status' => 'Active',
                'created_by' => Auth::user()->id,
                'created_date' => date('Y-m-d h:s'),
            ];
            VoucherDetails::insert($item_array_credit);

            return response()->json(['success' => ' Transaction successfull']);
        } else {
            $request->validate([
                'amount' => 'required',
            ]);
        }

    }

    public function delete(Request $request)
    {
        $transactions = Transactions::find($request->id);
        $transactions->status = 'Inactive';
        $transactions->deleted = 'Yes';
        $transactions->deleted_by = Auth::user()->id;
        $transactions->deleted_date = date('Y-m-d h:s');
        $transactions->save();
        $fromId = $transactions->tbl_coa_from_id;
        $toId = $transactions->tbl_coa_to_id;
        $amount = $transactions->amount;

        $coasFrom = ChartOfAccounts::find($fromId);
        $coasFrom->increment('amount', $amount);

        $coasTo = ChartOfAccounts::find($toId);
        $coasTo->decrement('amount', $amount);

    }
}
