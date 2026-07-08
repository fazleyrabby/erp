<?php

namespace App\Http\Controllers\Admin\Reports;

use App\Http\Controllers\Controller;
use App\Models\Accounts\ChartOfAccounts;
use App\Models\Accounts\MonthlyReport;
use App\Models\Accounts\Voucher;
use App\Models\Crm\Party;
use App\Models\inventory\Purchase;
use App\Models\inventory\Purchase_Return;
use App\Models\inventory\Sale;
use App\Models\inventory\SaleOrder;
use App\Models\inventory\SaleReturn;
use App\Models\Report\DailyReport;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use PDF;

class ReportController extends Controller
{
    public function index()
    {
        $suppliers = Party::where('deleted', '=', 'No')->where('status', '=', 'Active')->get();

        return view('admin.reports.partyLedger', ['suppliers' => $suppliers]);
    }

    public function generateVoucher(Request $request)
    {

        $vouchers = DB::table('tbl_acc_voucher_details')
            ->join('tbl_acc_vouchers', 'tbl_acc_voucher_details.tbl_acc_voucher_id', '=', 'tbl_acc_vouchers.id')
            ->select('tbl_acc_voucher_details.*', 'tbl_acc_vouchers.transaction_date', 'tbl_acc_vouchers.amount', 'tbl_acc_vouchers.vendor_id', 'tbl_acc_vouchers.id as voucherId')
            ->where('tbl_acc_vouchers.vendor_id', '=', $request->vendor_id)
            ->where('tbl_acc_vouchers.transaction_date', '>=', $request->date_from)
            ->where('tbl_acc_vouchers.transaction_date', '<=', $request->date_to)
            ->where('tbl_acc_vouchers.deleted', '=', 'No')
            ->where('tbl_acc_vouchers.status', '=', 'Active')
            ->get();

        $debit = 0;
        $openingDebitArray = DB::table('tbl_acc_voucher_details')
            ->join('tbl_acc_vouchers', 'tbl_acc_voucher_details.tbl_acc_voucher_id', '=', 'tbl_acc_vouchers.id')
            ->select('tbl_acc_voucher_details.debit', 'tbl_acc_vouchers.transaction_date', 'tbl_acc_vouchers.vendor_id')
            ->where('tbl_acc_vouchers.vendor_id', '=', $request->vendor_id)
            ->where('tbl_acc_vouchers.transaction_date', '<', $request->date_from)
            ->where('tbl_acc_vouchers.deleted', '=', 'No')
            ->where('tbl_acc_vouchers.status', '=', 'Active')
            ->get();
        foreach ($openingDebitArray as $array) {
            $debit += $array->debit;
        }

        $credit = 0;
        $openingCreditArray = DB::table('tbl_acc_voucher_details')
            ->join('tbl_acc_vouchers', 'tbl_acc_voucher_details.tbl_acc_voucher_id', '=', 'tbl_acc_vouchers.id')
            ->select('tbl_acc_voucher_details.credit', 'tbl_acc_vouchers.transaction_date', 'tbl_acc_vouchers.vendor_id')
            ->where('tbl_acc_vouchers.vendor_id', '=', $request->vendor_id)
            ->where('tbl_acc_vouchers.transaction_date', '<', $request->date_from)
            ->where('tbl_acc_vouchers.deleted', '=', 'No')
            ->where('tbl_acc_vouchers.status', '=', 'Active')
            ->get();
        foreach ($openingCreditArray as $array) {
            $credit += $array->credit;
        }
        $debitToCredit = $debit - $credit;

        $data = '';
        $table = '';
        $total = '';
        $totalDebit = 0;
        $totalCredit = 0;
        $button = '';
        $i = 1;
        $balance = 0;

        $table .= '<tr>
                        <td colspan="5" class="text-right"><b>Opening Balance:</b></td>
                            <td class="text-right"><b>'.$debit.'.00</b></td>
                        <td class="text-right"><b>'.$credit.'.00</b></td>
                        <td class="text-right"><b>'.$debitToCredit.'.00</b></td>
                    </tr>';

        foreach ($vouchers as $voucher) {
            if ($voucher->debit) {
                $balance += $voucher->debit;
            } else {
                $balance -= $voucher->credit;
            }
            $table .= '<tr>
                            <td class="text-center">'.$i++.'</td>
                            <td class="text-center">'.$voucher->transaction_date.'</td>
                            <td>'.$voucher->voucher_title.'</td>
                            <td class="text-center"># '.$voucher->voucherId.'</td>
                            <td>'.$voucher->particulars.'</td>
                            <td class="text-right">'.$voucher->debit.'</td>
                            <td class="text-right">'.$voucher->credit.'</td>
                            <td class="text-right">'.$balance.'</td>
                        </tr>';
            $totalDebit += $voucher->debit;
            $totalCredit += $voucher->credit;
        }
        $due = $totalDebit - $totalCredit;
        $total .= '<tr>
                            <td colspan="5" class="text-right"><b>Total:</b></td>
                             <td class="text-right"><b>'.$totalDebit.'</b></td>
                            <td class="text-right"><b>'.$totalCredit.'</b></td>
                            <td class="text-right"><b>'.$due.'</b></td>
                        </tr>';

        if ($totalDebit < $totalCredit) {
            $aDebit = $due;
            $aCredit = '0';
        } elseif ($totalDebit > $totalCredit) {
            $aDebit = '0';
            $aCredit = $due;
        } else {
            $aDebit = '0';
            $aCredit = '0';
        }

        $total .= '<tr>
                            <td colspan="5" class="text-right"><b>Closing Balance:</b></td>
                             <td class="text-right"><b>'.$aDebit.'</b></td>
                            <td class="text-right"><b>'.$aCredit.'</b></td>
                            <td></td>
                        </tr>';

        $totalDebitWithDue = $totalDebit - $aDebit;
        $totalCreditWithDue = $totalCredit + $aCredit;

        $total .= '<tr>
                        <td colspan="5" class="text-right"></td>
                         <td class="text-right"><b>'.$totalDebitWithDue.'</b></td>
                        <td class="text-right"><b>'.$totalCreditWithDue.'</b></td>
                        <td></td>
                    </tr>';

        $button .= '<button class="btn btn-primary float-right" onclick="generateVoucherPdf()"><i class="fa fa-file-pdf"></i> Get pdf</button>';
        $data = [
            'table' => $table,
            'total' => $total,
            'button' => $button,
        ];

        return $data;
    }

    public function generatePdf($vendor_id, $date_from, $date_to)
    {
        $vouchers = DB::table('tbl_acc_voucher_details')
            ->join('tbl_acc_vouchers', 'tbl_acc_voucher_details.tbl_acc_voucher_id', '=', 'tbl_acc_vouchers.id')
            ->select('tbl_acc_voucher_details.*', 'tbl_acc_vouchers.transaction_date', 'tbl_acc_vouchers.amount', 'tbl_acc_vouchers.vendor_id', 'tbl_acc_vouchers.id as voucherId')
            ->where('tbl_acc_vouchers.vendor_id', '=', $vendor_id)
            ->where('tbl_acc_vouchers.transaction_date', '>=', $date_from)
            ->where('tbl_acc_vouchers.transaction_date', '<=', $date_to)
            ->where('tbl_acc_vouchers.deleted', '=', 'No')
            ->where('tbl_acc_vouchers.status', '=', 'Active')
            ->get();
        $party = Party::find($vendor_id);
        $debit = 0;
        $openingDebitArray = DB::table('tbl_acc_voucher_details')
            ->join('tbl_acc_vouchers', 'tbl_acc_voucher_details.tbl_acc_voucher_id', '=', 'tbl_acc_vouchers.id')
            ->select('tbl_acc_voucher_details.debit', 'tbl_acc_vouchers.transaction_date', 'tbl_acc_vouchers.vendor_id')
            ->where('tbl_acc_vouchers.vendor_id', '=', $vendor_id)
            ->where('tbl_acc_vouchers.transaction_date', '<', $date_from)
            ->where('tbl_acc_vouchers.deleted', '=', 'No')
            ->where('tbl_acc_vouchers.status', '=', 'Active')
            ->get();
        foreach ($openingDebitArray as $array) {
            $debit += $array->debit;
        }

        $credit = 0;
        $openingCreditArray = DB::table('tbl_acc_voucher_details')
            ->join('tbl_acc_vouchers', 'tbl_acc_voucher_details.tbl_acc_voucher_id', '=', 'tbl_acc_vouchers.id')
            ->select('tbl_acc_voucher_details.credit', 'tbl_acc_vouchers.transaction_date', 'tbl_acc_vouchers.vendor_id')
            ->where('tbl_acc_vouchers.vendor_id', '=', $vendor_id)
            ->where('tbl_acc_vouchers.transaction_date', '<', $date_from)
            ->where('tbl_acc_vouchers.deleted', '=', 'No')
            ->where('tbl_acc_vouchers.status', '=', 'Active')
            ->get();
        foreach ($openingCreditArray as $array) {
            $credit += $array->credit;
        }
        $debitToCredit = $debit - $credit;

        $pdf = PDF::loadView('admin.reports.partyLedgerPdf', ['vouchers' => $vouchers, 'party' => $party, 'date_from' => $date_from, 'date_to' => $date_to, 'debit' => $debit, 'credit' => $credit, 'debitToCredit' => $debitToCredit]);

        return $pdf->stream('party-ledger-pdf.pdf', ['Attachment' => false]);
    }

    public function accountsSummaryView()
    {
        return view('admin.reports.accountsSummaryLedger');
    }

    public function accountsSummaryGenerate(Request $request)
    {

        $time = strtotime($request->date_from);
        $month = date('F', $time);
        $year = date('Y', $time);
        $monthYear = date('F Y', $time);

        $income = ChartOfAccounts::where('name', '=', 'Income')->where('deleted', 'No')->first();
        $incomeId = $income->id;
        $allIncomes = ChartOfAccounts::where('parent_id', '=', $incomeId)->where('deleted', 'No')->get();

        $sales = ChartOfAccounts::where('name', '=', 'Sales')->where('deleted', 'No')->first();
        $salesId = $sales->id;
        $allsales = ChartOfAccounts::where('parent_id', '=', $salesId)->where('deleted', 'No')->get();

        $expense = ChartOfAccounts::where('name', '=', 'Expense')->where('deleted', 'No')->first();
        $expenseId = $expense ? $expense->id : null;

        $allExpense = $expenseId
            ? ChartOfAccounts::where('parent_id', '=', $expenseId)->where('deleted', 'No')->get()
            : collect();

        $purchase = ChartOfAccounts::where('name', '=', 'Purchase')->where('deleted', 'No')->first();
        $purchaseId = $purchase ? $purchase->id : null;
        $allpurchases = $purchaseId
            ? ChartOfAccounts::where('parent_id', '=', $purchaseId)->where('deleted', 'No')->get()
            : collect();

        $backDateFrom = date('0'.(date('m', strtotime($request->date_from)) - 1).'-d-Y');

        $CashInHandFrom = DB::table('tbl_acc_voucher_details')
            ->join('tbl_acc_vouchers', 'tbl_acc_voucher_details.tbl_acc_voucher_id', '=', 'tbl_acc_vouchers.id')
            ->select('tbl_acc_voucher_details.*', 'tbl_acc_vouchers.transaction_date')
            ->where('tbl_acc_vouchers.transaction_date', '>=', $backDateFrom)
            ->where('tbl_acc_vouchers.transaction_date', '<=', $request->date_from)
            ->where('tbl_acc_vouchers.deleted', '=', 'No')
            ->where('tbl_acc_vouchers.status', '=', 'Active')
            ->get();
        $salesCashFrom = 0;
        foreach ($CashInHandFrom as $debit) {
            $salesCashFrom += $debit->debit;
        }

        $CashInHandTo = DB::table('tbl_acc_voucher_details')
            ->join('tbl_acc_vouchers', 'tbl_acc_voucher_details.tbl_acc_voucher_id', '=', 'tbl_acc_vouchers.id')
            ->select('tbl_acc_voucher_details.*', 'tbl_acc_vouchers.transaction_date')
            ->where('tbl_acc_vouchers.transaction_date', '>=', $backDateFrom)
            ->where('tbl_acc_vouchers.transaction_date', '<=', $request->date_from)
            ->where('tbl_acc_vouchers.deleted', '=', 'No')
            ->where('tbl_acc_vouchers.status', '=', 'Active')
            ->get();
        $salesCashTo = 0;
        foreach ($CashInHandTo as $credit) {
            $salesCashTo += $credit->credit;
        }

        $previousMonthYear = date('F Y', strtotime('-1 month', $time));
        $openings = MonthlyReport::where('month_year', '=', $previousMonthYear)->where('deleted', 'No')->first();
        if ($openings != null) {
            $openingbalance = $openings->opening_balance;
        } else {
            $openingbalance = '0.00';
        }
        $openingCash = $salesCashFrom - $salesCashTo;

        $monthYearHeader = '';
        $monthYearHeader .= '';
        $table = '';
        $closingBtn = '';
        $table .= '<table class="table table-bordered" style="font-size:13px;" cellpadding="4">
                        <tr valign="top">
                        <td style="width:50%; padding:0;">
                            <table class="table table-bordered table-sm mb-0" style="font-size:13px;" cellpadding="4">
                            <thead class="table-dark">
                                <tr><th colspan="3" class="text-center">INCOME</th></tr>
                                <tr><th>Particulars</th><th class="text-right" style="width:30%;">Amount</th><th class="text-right" style="width:30%;">Net</th></tr>
                            </thead>
                            <tbody>';

        $totalIncome = 0;
        foreach ($allIncomes as $income) {
            $incomeAmounts = DB::table('tbl_acc_voucher_details')
                ->join('tbl_acc_vouchers', 'tbl_acc_voucher_details.tbl_acc_voucher_id', '=', 'tbl_acc_vouchers.id')
                ->select('tbl_acc_voucher_details.*', 'tbl_acc_vouchers.transaction_date')
                ->where('tbl_acc_vouchers.transaction_date', '>=', $request->date_from)
                ->where('tbl_acc_vouchers.transaction_date', '<=', $request->date_to)
                ->where('tbl_acc_voucher_details.tbl_acc_coa_id', '=', $income->id)
                ->where('tbl_acc_vouchers.deleted', '=', 'No')
                ->where('tbl_acc_vouchers.status', '=', 'Active')
                ->get();

            $amountDebitSum = 0;

            foreach ($incomeAmounts as $amount) {
                $amountDebitSum += $amount->debit;
            }
            $table .= '<tr>
                            <td>'.$income->name.'</td>
                            <td class="text-right">'.number_format($amountDebitSum).'</td>
                            <td class="text-right"></td>
                        </tr>';
            $totalIncome += $amountDebitSum;
        }

        // Sale Section
        $totalSales = 0;
        $totalSaleReturnAmount = 0;
        $count = count($allsales) - 1;

        foreach ($allsales as $key => $sale) {
            $saleAmounts = DB::table('tbl_acc_voucher_details')
                ->join('tbl_acc_vouchers', 'tbl_acc_voucher_details.tbl_acc_voucher_id', '=', 'tbl_acc_vouchers.id')
                ->whereBetween('tbl_acc_vouchers.transaction_date', [$request->date_from, $request->date_to])
                ->where('tbl_acc_voucher_details.tbl_acc_coa_id', '=', $sale->id)
                ->where('tbl_acc_vouchers.deleted', '=', 'No')
                ->where('tbl_acc_vouchers.status', '=', 'Active')
                ->sum('credit');

            $amountDebitSum = $saleAmounts;
            $totalSales += $saleAmounts;

            $amountDebitSum = $amountDebitSum;
            // Sales Return
            $totalSaleReturnAmount = 0;
            $setBracket = '';
            $setBracket2 = '';
            if ($sale->slug == 'sales-ruturn') {
                $amountDebitSum = SaleReturn::whereBetween('sale_return_date', [$request->date_from, $request->date_to])->where('deleted', 'No')->where('coa_id', $sale->id)->sum('grand_total');
                $totalSaleReturnAmount = $amountDebitSum;
                $setBracket = '(';
                $setBracket2 = ')';
            }
            $netSalesAmount = '';
            if ($count == $key) {
                $netSalesAmount = $totalSales - $totalSaleReturnAmount;
            }
            $table .= '<tr>
                           <td width="50%">'.$sale->name.'</td>
                           <td width="25%" class="text-right">'.$setBracket.$amountDebitSum.$setBracket2.'</td>
                           <td width="25%" class="text-right">'.$netSalesAmount.'</td>
                       </tr>';
        }

        $totalIncomeWithOpening = (($openingbalance + $totalIncome + $totalSales) - $totalSaleReturnAmount);

        $table .= '<tr class="font-weight-bold table-active">
                            <td>Total Income</td>
                            <td class="text-right">'.number_format($totalIncomeWithOpening).'</td>
                            <td class="text-right"></td>
                        </tr>';
        $table .= '</tbody></table></td>
                        <td style="width:50%; padding:0;">
                            <table class="table table-bordered table-sm mb-0" style="font-size:13px;" cellpadding="4">
                            <thead class="table-dark">
                                <tr><th colspan="3" class="text-center">EXPENDITURE</th></tr>
                                <tr><th>Particulars</th><th class="text-right" style="width:30%;">Amount</th><th class="text-right" style="width:30%;">Net</th></tr>
                            </thead>
                            <tbody>';

        // Purchase Section
        $purchaseSum = 0;
        $totalPurchases = 0;
        $totalPurchaseReturnAmount = 0;
        $count = count($allpurchases) - 1;

        foreach ($allpurchases as $key => $purchase) {
            $purchaseAmounts = DB::table('tbl_acc_voucher_details')
                ->join('tbl_acc_vouchers', 'tbl_acc_voucher_details.tbl_acc_voucher_id', '=', 'tbl_acc_vouchers.id')
                ->select('tbl_acc_voucher_details.*', 'tbl_acc_vouchers.transaction_date', 'tbl_acc_vouchers.type')
                ->whereBetween('tbl_acc_vouchers.transaction_date', [$request->date_from, $request->date_to])
                ->where('tbl_acc_voucher_details.tbl_acc_coa_id', '=', $purchase->id)
                ->where('tbl_acc_vouchers.deleted', '=', 'No')
                ->where('tbl_acc_vouchers.status', '=', 'Active')
                ->sum('debit');
            $amountSum = $purchaseAmounts;
            $totalPurchases += $purchaseAmounts;

            // Purchase Return
            $setBracket = '';
            $setBracket2 = '';
            $totalPurchaseReturnAmount = 0;
            if ($purchase->slug == 'purchase-return') {
                $amountSum = Purchase_Return::whereBetween('purchase_return_date', [$request->date_from, $request->date_to])
                    ->where('deleted', 'No')->where('coa_id', $purchase->id)->sum('grand_total');
                $totalPurchaseReturnAmount = $amountSum;
                $setBracket = '(';
                $setBracket2 = ')';
            }
            $netPurchaseAmount = '';
            if ($count == $key) {
                $netPurchaseAmount = $totalPurchases - $totalPurchaseReturnAmount;
            }

            $table .= '<tr>
                           <td>'.$purchase->name.'</td>
                           <td class="text-right">'.$setBracket.$amountSum.$setBracket2.'</td>
                           <td class="text-right">'.$netPurchaseAmount.'</td>
                        </tr>';
            if ($purchase->id != 43) {
                $purchaseSum += $amountSum;
            }
        }

        $table .= '<tr class=" text-center"><td colspan="3"></td></tr>';
        $expenseSum = 0;
        $totalExpenses = 0;
        $count = count($allExpense) - 1;
        foreach ($allExpense as $key => $expense) {
            $incomeAmounts = DB::table('tbl_acc_voucher_details')
                ->join('tbl_acc_vouchers', 'tbl_acc_voucher_details.tbl_acc_voucher_id', '=', 'tbl_acc_vouchers.id')
                ->select('tbl_acc_voucher_details.*', 'tbl_acc_vouchers.transaction_date', 'tbl_acc_vouchers.type')
                ->whereBetween('tbl_acc_vouchers.transaction_date', [$request->date_from, $request->date_to])
                ->where('tbl_acc_voucher_details.tbl_acc_coa_id', '=', $expense->id)
                ->where('tbl_acc_vouchers.deleted', '=', 'No')
                ->where('tbl_acc_vouchers.status', '=', 'Active')
                ->get();
            $amountSum = 0;
            foreach ($incomeAmounts as $amount) {
                $amountSum += $amount->credit;
                $totalExpenses += $amount->credit;
            }
            $tempTotalExpenses = '';
            if ($count == $key) {
                $tempTotalExpenses = $totalExpenses;
            }

            $table .= '<tr>
                           <td width="50%">'.$expense->name.'</td>
                           <td width="25%" class="text-right">'.number_format($amountSum).'</td>
                           <td width="25%" class="text-right">'.$tempTotalExpenses.'</td>
                        </tr>';
            $expenseSum += $amountSum;
        }

        /* $table .= '<tr>
                    <td width="75%" class="text-center" colspan="2">Total Expenses </td>
                    <td width="25%" class="text-right">' . (($totalExpenses + $totalPurchases) - $totalPurchaseReturnAmount) . '</td>
                </tr>'; */

        $totalExpense = (($expenseSum + $purchaseSum) - $totalPurchaseReturnAmount);
        $table .= '<tr class="font-weight-bold table-active">
                            <td>Total Expenditure</td>
                            <td class="text-right">'.number_format($totalExpense).'</td>
                            <td class="text-right"></td>
                        </tr>';
        $table .= '</tbody></table></td></tr></table>';

        $table .= '<table class="table table-bordered table-sm mt-3" style="font-size:13px;" cellpadding="4">
                        <tbody>
                            <tr class="font-weight-bold table-active">
                                <td style="width:25%;">Total Income</td>
                                <td class="text-right" style="width:25%;">'.number_format($totalIncomeWithOpening).'</td>
                                <td style="width:25%;">Total Expenditure</td>
                                <td class="text-right" style="width:25%;">'.number_format($totalExpense).'</td>
                            </tr>';
        $due = $totalIncomeWithOpening - $totalExpense;
        if ($totalIncomeWithOpening < $totalExpense) {
            $incomeClosing = $due;
            $expenseClosing = '0';
        } elseif ($totalIncomeWithOpening > $totalExpense) {
            $incomeClosing = '0';
            $expenseClosing = $due;
        } else {
            $incomeClosing = '0';
            $expenseClosing = '0';
        }

        $totalIncomeWithDue = $totalIncomeWithOpening - $incomeClosing;
        $totalExpenseWithDue = $totalExpense + $expenseClosing;
        $clss = 'bg-success';
        if ($totalIncomeWithOpening < $totalExpense) {
            $clss = 'bg-danger';
        }
        $table .= '<tr>
                        <td>
                            <table class="table table-vcenter table-bordered table-striped">
                                <tbody>
                                    <tr class="font-weight-bold">
                                        <td width="70%">Balance Closing: </td>
                                        <td width="30%" class="text-right"><strong>'.number_format($expenseClosing).'</strong></td>
                                    </tr>
                                </tbody>
                            </table>
                        </td>
                    </tr>';

        // Start Voucher Section
        $voucherSummary = DB::table('payment_vouchers')
            ->whereBetween('paymentDate', [$request->date_from, $request->date_to])
            ->where('deleted', 'No')
            ->where('status', 'Active')
            ->whereNull('purchase_id')
            ->whereNull('order_sale_id')
            ->whereNull('sales_id')
            ->whereNull('purchase_return_id')
            ->whereNull('sales_return_id')
            ->whereNull('expense_id')
            ->select(
                DB::raw('SUM(CASE WHEN type="Payment Received" THEN payment_vouchers.amount END) totalVoucherRcvAmount'),
                DB::raw('SUM(CASE WHEN type="Payment" THEN payment_vouchers.amount END) totalVoucherPaymentAmount'),
            )->first();
        $table .= '<tr>
                    <td>
                        <table class="table table-vcenter table-bordered table-striped">
                            <tbody>
                                <tr class="font-weight-bold">
                                    <td width="70%"> Voucher Recipient </td>
                                    <td width="30%" class="text-right"><strong>'.$voucherSummary->totalVoucherRcvAmount.'</strong></td>
                                </tr>
                                <tr class="font-weight-bold">
                                    <td width="70%"> Payment Voucher </td>
                                    <td width="30%" class="text-right"><strong>'.$voucherSummary->totalVoucherPaymentAmount.'</strong></td>
                                </tr>
                                <tr class="font-weight-bold">
                                    <td width="70%"> Cash In Hand </td>
                                    <td width="30%" class="text-right"><strong>'.number_format(($voucherSummary->totalVoucherRcvAmount - $voucherSummary->totalVoucherPaymentAmount)).'</strong></td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>';
        // End Voucher Section

        $lastTwoChar = substr(($request->date_to), -2);
        $time = strtotime($request->date_to);
        $month = date('F', $time);
        $year = date('Y', $time);
        $monthYear = date('F Y', $time);

        $month_value = date('m', strtotime($month));
        $month_days_count = cal_days_in_month(CAL_GREGORIAN, $month_value, $year);

        if ($lastTwoChar == $month_days_count) {
            $closingBtn = '<span class="text-success">Now you can save the closing balance.</span><button class="btn btn-primary float-right" onclick="closeBalance()">Close balance</button>';
        } else {
            $closingBtn = '<span class="text-danger">Select the last day of the month to close the balance</span><button class="btn btn-secondary float-right disabled" >Close balance</button>';
        }
        $pdf = '';
        $pdf = '<button class="btn btn-primary mt-2" onclick="generateAccountsSummaryPdf()"><i class="fas fa-print"></i> Print PDF</button>';

        /* $data = array(
            'table' => $table,
            'monthYearHeader' => $monthYearHeader,
            'closingBtn' => $closingBtn,
            'pdf' => $pdf
        ); */
        return response()->json(['table' => $table, 'monthYearHeader' => $monthYearHeader, 'closingBtn' => $closingBtn, 'pdf' => $pdf]);
    }

    public function closingBalanceStore(Request $request)
    {

        $lastTwoChar = substr(($request->date_to), -2);
        $time = strtotime($request->date_to);
        $month = date('F', $time);
        $year = date('Y', $time);
        $monthYear = date('F Y', $time);

        $checkPreviousMonthYear = '';
        $checkPreviousMonthYear = MonthlyReport::where('month_year', '=', $monthYear)->first();
        if ($checkPreviousMonthYear != null) {
            $checkPreviousMonthYear->from_date = $request->date_from;
            $checkPreviousMonthYear->to_date = $request->date_to;
            $checkPreviousMonthYear->month_year = $monthYear;
            $checkPreviousMonthYear->previous_month_closing = $request->previousMonthClosing;
            $checkPreviousMonthYear->opening_balance = $request->presentClosingBalance;
            $checkPreviousMonthYear->present_month_closing = $request->presentClosingBalance;
            $checkPreviousMonthYear->last_updated_by = Auth::user()->id;
            $checkPreviousMonthYear->save();

            return response()->json(['success' => 'Closing balance updated successfully']);
        } else {
            $closing = new MonthlyReport;
            $closing->from_date = $request->date_from;
            $closing->to_date = $request->date_to;
            $closing->previous_month_closing = $request->previousMonthClosing;
            $closing->opening_balance = $request->presentClosingBalance;
            $closing->present_month_closing = $request->presentClosingBalance;
            $closing->month_year = $monthYear;
            $closing->created_by = Auth::user()->id;
            $closing->deleted = 'No';
            $closing->status = 'Active';
            $closing->save();

            return response()->json(['success' => 'Closing balance saved successfully']);
        }
    }

    public function generateAccountsSummaryPdf($date_from, $date_to)
    {
        $time = strtotime($date_from);
        $month = date('F', $time);
        $year = date('Y', $time);
        $monthYear = date('F Y', $time);

        $income = ChartOfAccounts::where('name', '=', 'Income')->where('deleted', 'No')->first();
        $incomeId = $income ? $income->id : null;
        $allIncomes = $incomeId
            ? ChartOfAccounts::where('parent_id', '=', $incomeId)->where('deleted', 'No')->get()
            : collect();

        $sales = ChartOfAccounts::where('name', '=', 'Sales')->where('deleted', 'No')->first();
        $salesId = $sales ? $sales->id : null;
        $allsales = $salesId
            ? ChartOfAccounts::where('parent_id', '=', $salesId)->where('deleted', 'No')->get()
            : collect();

        $expense = ChartOfAccounts::where('name', '=', 'Expense')->where('deleted', 'No')->first();
        $expenseId = $expense ? $expense->id : null;

        $allExpense = $expenseId
            ? ChartOfAccounts::where('parent_id', '=', $expenseId)->where('deleted', 'No')->get()
            : collect();

        $purchase = ChartOfAccounts::where('name', '=', 'Purchase')->where('deleted', 'No')->first();
        $purchaseId = $purchase ? $purchase->id : null;
        $allpurchases = $purchaseId
            ? ChartOfAccounts::where('parent_id', '=', $purchaseId)->where('deleted', 'No')->get()
            : collect();

        $backDateFrom = date('0'.(date('m', strtotime($date_from)) - 1).'-d-Y');

        $CashInHandFrom = DB::table('tbl_acc_voucher_details')
            ->join('tbl_acc_vouchers', 'tbl_acc_voucher_details.tbl_acc_voucher_id', '=', 'tbl_acc_vouchers.id')
            ->select('tbl_acc_voucher_details.*', 'tbl_acc_vouchers.transaction_date')
            ->where('tbl_acc_vouchers.transaction_date', '>=', $backDateFrom)
            ->where('tbl_acc_vouchers.transaction_date', '<=', $date_from)
            ->where('tbl_acc_vouchers.deleted', '=', 'No')
            ->where('tbl_acc_vouchers.status', '=', 'Active')
            ->get();
        $salesCashFrom = 0;
        foreach ($CashInHandFrom as $debit) {
            $salesCashFrom += $debit->debit;
        }

        $CashInHandTo = DB::table('tbl_acc_voucher_details')
            ->join('tbl_acc_vouchers', 'tbl_acc_voucher_details.tbl_acc_voucher_id', '=', 'tbl_acc_vouchers.id')
            ->select('tbl_acc_voucher_details.*', 'tbl_acc_vouchers.transaction_date')
            ->where('tbl_acc_vouchers.transaction_date', '>=', $backDateFrom)
            ->where('tbl_acc_vouchers.transaction_date', '<=', $date_from)
            ->where('tbl_acc_vouchers.deleted', '=', 'No')
            ->where('tbl_acc_vouchers.status', '=', 'Active')
            ->get();
        $salesCashTo = 0;
        foreach ($CashInHandTo as $credit) {
            $salesCashTo += $credit->credit;
        }

        $previousMonthYear = date('F Y', strtotime('-1 month', $time));
        $openings = MonthlyReport::where('month_year', '=', $previousMonthYear)->where('deleted', 'No')->first();
        if ($openings != null) {
            $openingbalance = $openings->opening_balance;
        } else {
            $openingbalance = '0.00';
        }
        $openingCash = $salesCashFrom - $salesCashTo;

        $monthYearHeader = '';
        $monthYearHeader .= '';
        $table = '';
        $closingBtn = '';
        $table .= '<table class="table table-bordered" style="font-size:13px;" cellpadding="4">
                        <tr valign="top">
                        <td style="width:50%; padding:0;">
                            <table class="table table-bordered table-sm mb-0" style="font-size:13px;" cellpadding="4">
                            <thead class="table-dark">
                                <tr><th colspan="3" class="text-center">INCOME</th></tr>
                                <tr><th>Particulars</th><th class="text-right" style="width:30%;">Amount</th><th class="text-right" style="width:30%;">Net</th></tr>
                            </thead>
                            <tbody>';

        $totalIncome = 0;
        foreach ($allIncomes as $income) {
            $incomeAmounts = DB::table('tbl_acc_voucher_details')
                ->join('tbl_acc_vouchers', 'tbl_acc_voucher_details.tbl_acc_voucher_id', '=', 'tbl_acc_vouchers.id')
                ->select('tbl_acc_voucher_details.*', 'tbl_acc_vouchers.transaction_date')
                ->where('tbl_acc_vouchers.transaction_date', '>=', $date_from)
                ->where('tbl_acc_vouchers.transaction_date', '<=', $date_to)
                ->where('tbl_acc_voucher_details.tbl_acc_coa_id', '=', $income->id)
                ->where('tbl_acc_vouchers.deleted', '=', 'No')
                ->where('tbl_acc_vouchers.status', '=', 'Active')
                ->get();

            $amountDebitSum = 0;

            foreach ($incomeAmounts as $amount) {
                $amountDebitSum += $amount->debit;
            }
            $table .= '<tr>
                            <td>'.$income->name.'</td>
                            <td class="text-right">'.number_format($amountDebitSum).'</td>
                            <td class="text-right"></td>
                        </tr>';
            $totalIncome += $amountDebitSum;
        }

        // Sale Section
        $totalSales = 0;
        $totalSaleReturnAmount = 0;
        $count = count($allsales) - 1;

        foreach ($allsales as $key => $sale) {
            $saleAmounts = DB::table('tbl_acc_voucher_details')
                ->join('tbl_acc_vouchers', 'tbl_acc_voucher_details.tbl_acc_voucher_id', '=', 'tbl_acc_vouchers.id')
                ->whereBetween('tbl_acc_vouchers.transaction_date', [$date_from, $date_to])
                ->where('tbl_acc_voucher_details.tbl_acc_coa_id', '=', $sale->id)
                ->where('tbl_acc_vouchers.deleted', '=', 'No')
                ->where('tbl_acc_vouchers.status', '=', 'Active')
                ->sum('credit');

            $amountDebitSum = $saleAmounts;
            $totalSales += $saleAmounts;

            $amountDebitSum = $amountDebitSum;
            // Sales Return
            $totalSaleReturnAmount = 0;
            $setBracket = '';
            $setBracket2 = '';
            if ($sale->slug == 'sales-ruturn') {
                $amountDebitSum = SaleReturn::whereBetween('sale_return_date', [$date_from, $date_to])
                    ->where('deleted', 'No')->where('coa_id', $sale->id)->sum('grand_total');

                $totalSaleReturnAmount = $amountDebitSum;
                $setBracket = '(';
                $setBracket2 = ')';
            }
            $netSalesAmount = '';
            if ($count == $key) {
                $netSalesAmount = $totalSales - $totalSaleReturnAmount;
            }

            $table .= '<tr width="100%">
                           <td width="50%">'.$sale->name.'</td>
                           <td width="25%" class="text-right">'.$setBracket.$amountDebitSum.$setBracket2.'</td>
                           <td width="25%" class="text-right">'.$netSalesAmount.'</td>
                       </tr>';
        }

        $totalIncomeWithOpening = (($openingbalance + $totalIncome + $totalSales) - $totalSaleReturnAmount);

        $table .= '<tr class="font-weight-bold table-active">
                            <td>Total Income</td>
                            <td class="text-right">'.number_format($totalIncomeWithOpening).'</td>
                            <td class="text-right"></td>
                        </tr>';
        $table .= '</tbody></table></td>
                        <td style="width:50%; padding:0;">
                            <table class="table table-bordered table-sm mb-0" style="font-size:13px;" cellpadding="4">
                            <thead class="table-dark">
                                <tr><th colspan="3" class="text-center">EXPENDITURE</th></tr>
                                <tr><th>Particulars</th><th class="text-right" style="width:30%;">Amount</th><th class="text-right" style="width:30%;">Net</th></tr>
                            </thead>
                            <tbody>';

        $purchaseSum = 0;
        $totalPurchases = 0;
        $totalPurchaseReturnAmount = 0;
        $count = count($allpurchases) - 1;

        foreach ($allpurchases as $key => $purchase) {
            $purchaseAmounts = DB::table('tbl_acc_voucher_details')
                ->join('tbl_acc_vouchers', 'tbl_acc_voucher_details.tbl_acc_voucher_id', '=', 'tbl_acc_vouchers.id')
                ->select('tbl_acc_voucher_details.*', 'tbl_acc_vouchers.transaction_date', 'tbl_acc_vouchers.type')
                ->whereBetween('tbl_acc_vouchers.transaction_date', [$date_from, $date_to])
                ->where('tbl_acc_voucher_details.tbl_acc_coa_id', '=', $purchase->id)
                ->where('tbl_acc_vouchers.deleted', '=', 'No')
                ->where('tbl_acc_vouchers.status', '=', 'Active')
                ->sum('debit');
            $amountSum = $purchaseAmounts;
            $totalPurchases += $purchaseAmounts;

            // Purchase Return
            $setBracket = '';
            $setBracket2 = '';
            $totalPurchaseReturnAmount = 0;
            if ($purchase->slug == 'purchase-return') {
                $amountSum = Purchase_Return::whereBetween('purchase_return_date', [$date_from, $date_to])->where('deleted', 'No')->where('coa_id', $purchase->id)->sum('grand_total');
                $totalPurchaseReturnAmount = $amountSum;
                $setBracket = '(';
                $setBracket2 = ')';
            }
            $netPurchaseAmount = '';
            if ($count == $key) {
                $netPurchaseAmount = $totalPurchases - $totalPurchaseReturnAmount;
            }

            $table .= '<tr  width="100%">
                           <td width="50%">'.$purchase->name.'</td>
                           <td width="25%" class="text-right">'.$setBracket.$amountSum.$setBracket2.'</td>
                           <td width="25%" class="text-right">'.$netPurchaseAmount.'</td>
                        </tr>';
            if ($purchase->id != 43) {
                $purchaseSum += $amountSum;
            }
        }

        $table .= '<tr class=" text-center"><td colspan="3"></td></tr>';

        $expenseSum = 0;
        $totalExpenses = 0;
        $count = count($allExpense) - 1;
        foreach ($allExpense as $key => $expense) {
            $incomeAmounts = DB::table('tbl_acc_voucher_details')
                ->join('tbl_acc_vouchers', 'tbl_acc_voucher_details.tbl_acc_voucher_id', '=', 'tbl_acc_vouchers.id')
                ->select('tbl_acc_voucher_details.*', 'tbl_acc_vouchers.transaction_date', 'tbl_acc_vouchers.type')
                ->whereBetween('tbl_acc_vouchers.transaction_date', [$date_from, $date_to])
                ->where('tbl_acc_voucher_details.tbl_acc_coa_id', '=', $expense->id)
                ->where('tbl_acc_vouchers.deleted', '=', 'No')
                ->where('tbl_acc_vouchers.status', '=', 'Active')
                ->get();
            $amountSum = 0;
            foreach ($incomeAmounts as $amount) {
                $amountSum += $amount->credit;
                $totalExpenses += $amount->credit;
            }
            $tempTotalExpenses = '';
            if ($count == $key) {
                $tempTotalExpenses = $totalExpenses;
            }

            $table .= '<tr>
                           <td width="50%">'.$expense->name.'</td>
                           <td width="25%" class="text-right">'.number_format($amountSum).'</td>
                           <td width="25%" class="text-right">'.$tempTotalExpenses.'</td>
                        </tr>';
            $expenseSum += $amountSum;
        }

        $totalExpense = (($expenseSum + $purchaseSum) - $totalPurchaseReturnAmount);
        $table .= '<tr class="font-weight-bold table-active">
                            <td>Total Expenditure</td>
                            <td class="text-right">'.number_format($totalExpense).'</td>
                            <td class="text-right"></td>
                        </tr>';
        $table .= '</tbody></table></td></tr></table>';

        $table .= '<table class="table table-bordered table-sm mt-3" style="font-size:13px;" cellpadding="4">
                        <tbody>
                            <tr class="font-weight-bold table-active">
                                <td style="width:25%;">Total Income</td>
                                <td class="text-right" style="width:25%;">'.number_format($totalIncomeWithOpening).'</td>
                                <td style="width:25%;">Total Expenditure</td>
                                <td class="text-right" style="width:25%;">'.number_format($totalExpense).'</td>
                            </tr>';
        $due = $totalIncomeWithOpening - $totalExpense;
        if ($totalIncomeWithOpening < $totalExpense) {
            $incomeClosing = $due;
            $expenseClosing = '0';
        } elseif ($totalIncomeWithOpening > $totalExpense) {
            $incomeClosing = '0';
            $expenseClosing = $due;
        } else {
            $incomeClosing = '0';
            $expenseClosing = '0';
        }

        $totalIncomeWithDue = $totalIncomeWithOpening - $incomeClosing;
        $totalExpenseWithDue = $totalExpense + $expenseClosing;
        $clss = 'bg-success';
        if ($totalIncomeWithOpening < $totalExpense) {
            $clss = 'bg-danger';
        }
        $table .= '<tr class="font-weight-bold">
                        <td>Balance Closing</td>
                        <td class="text-right" colspan="3"><strong>'.number_format($expenseClosing).'</strong></td>
                    </tr>';

        // Start Voucher Section
        $voucherSummary = DB::table('payment_vouchers')
            ->whereBetween('paymentDate', [$date_from, $date_to])
            ->where('deleted', 'No')
            ->where('status', 'Active')
            ->whereNull('purchase_id')
            ->whereNull('order_sale_id')
            ->whereNull('sales_id')
            ->whereNull('purchase_return_id')
            ->whereNull('sales_return_id')
            ->whereNull('expense_id')
            ->select(
                DB::raw('SUM(CASE WHEN type="Payment Received" THEN payment_vouchers.amount END) totalVoucherRcvAmount'),
                DB::raw('SUM(CASE WHEN type="Payment" THEN payment_vouchers.amount END) totalVoucherPaymentAmount'),
            )->first();
        $table .= '<tr><td>Voucher Recipient</td><td class="text-right" colspan="3">'.number_format($voucherSummary->totalVoucherRcvAmount).'</td></tr>';
        $table .= '<tr><td>Payment Voucher</td><td class="text-right" colspan="3">'.number_format($voucherSummary->totalVoucherPaymentAmount).'</td></tr>';
        $table .= '<tr class="font-weight-bold table-active"><td>Cash In Hand</td><td class="text-right" colspan="3">'.number_format(($voucherSummary->totalVoucherRcvAmount - $voucherSummary->totalVoucherPaymentAmount)).'</td></tr>';
        $table .= '</tbody></table>';
        // End Voucher Section

        $lastTwoChar = substr(($date_to), -2);
        $time = strtotime($date_to);
        $month = date('F', $time);
        $year = date('Y', $time);
        $monthYear = date('F Y', $time);

        $month_value = date('m', strtotime($month));
        $month_days_count = cal_days_in_month(CAL_GREGORIAN, $month_value, $year);

        if ($lastTwoChar == $month_days_count) {
            $closingBtn = '<span class="text-success">Now you can save the closing balance.</span><button class="btn btn-primary float-right" onclick="closeBalance()">Close balance</button>';
        } else {
            $closingBtn = '<span class="text-danger">Select the last day of the month to close the balance</span><button class="btn btn-secondary float-right disabled" >Close balance</button>';
        }
        $pdf = '';
        $pdf = '<button class="btn btn-primary mt-2" onclick="generateAccountsSummaryPdf()"><i class="fas fa-print"></i> Print PDF</button>';
        $data = [
            'table' => $table,
            'date_from' => $date_from,
            'date_to' => $date_to,

        ];

        $pdf = PDF::loadView(
            'admin.reports.accountsSummaryPdf',
            [
                'data' => $data,
                'monthYear' => $monthYear,
            ]
        );

        return $pdf->stream('accounts-summary-pdf.pdf', ['Attachment' => false]);

        // //////////////===========================End===========================================////////////////
    }

    public function dailyAccountsLedger()
    {
        $lastDailyReport = DailyReport::where('deleted', 'No')->where('status', 'Active')->get()->last();
        if ($lastDailyReport != '') {
            $date = $lastDailyReport->date;

            $checkTransaction = DB::table('payment_vouchers')
                ->join('daily_reports', 'payment_vouchers.paymentDate', '!=', 'daily_reports.date')
                ->select('payment_vouchers.paymentDate')
                ->where('payment_vouchers.deleted', 'No')
                ->where('payment_vouchers.paymentDate', '>', $date)
                ->where('payment_vouchers.status', 'Active')
                ->distinct()
                ->get();

            $dateArray = [];
            $i = 0;

            foreach ($checkTransaction as $date) {

                $dateArray[$i] = $date->paymentDate; // store previous not closing date(s)
                $i++;
            }
            // end check preious days report saved or not//

            $purchasePaymentATotal = 0;
            $saleReceivedTotal = 0;
            $expanseAmount = 0;
        } else {
            $dateArray = [];
        }

        return view('admin.reports.accountsDailySummary', ['dateArray' => $dateArray]);

    }

    public function getDailyReport(Request $request)
    {
        $date = $request->date;
        $minusDaysFromDate = date_create($date)->modify('-1 days')->format('Y-m-d');

        $openingData = DailyReport::where('deleted', 'No')->where('date', '<', $date)->orderBy('date', 'DESC')->first();
        if ($openingData != null) {
            $lastDailyReport = DailyReport::where('deleted', 'No')->where('date', '<', $date)->orderBy('date', 'DESC')->first();
        } else {
            $lastDailyReport = 0;
        }

        // -----today payment, expense , payment received-------//
        $todayReport = DB::table('payment_vouchers')
            ->leftjoin('purchases', 'payment_vouchers.purchase_id', '=', 'purchases.id')
            ->leftjoin('sales', 'payment_vouchers.sales_id', '=', 'sales.id')
            ->select('payment_vouchers.*', 'sales.grand_total', 'purchases.total_amount')
            ->where('payment_vouchers.deleted', 'No')
            ->where('payment_vouchers.paymentDate', $date)
            ->where('payment_vouchers.status', 'Active')
            ->where('payment_vouchers.payment_method', 'Cash')
            ->where(function ($query) {
                $query->where('payment_vouchers.type', 'Payment')
                    ->orWhere('payment_vouchers.type', 'Payment Received');
            })
            ->get();

        $purchasePaymentTotal = 0;
        $paymentVoucher = 0;
        $paymentReceivedVoucher = 0;
        $saleReceivedTotal = 0;
        $expanseAmount = 0;

        foreach ($todayReport as $report) {
            if ($report->purchase_id > 0 && $report->type == 'Payment') {
                $purchasePaymentTotal += $report->amount;
            } elseif ($report->expense_id > 0 && $report->type == 'Payment') {
                $expanseAmount += $report->amount;
            } elseif ($report->sales_id > 0 && $report->type == 'Payment Received') {
                $saleReceivedTotal += $report->amount;
            } elseif ($report->type == 'Payment') {
                $paymentVoucher += $report->amount;
            } elseif ($report->type == 'Payment Received') {
                $paymentReceivedVoucher += $report->amount;
            }
        }
        $TotalPayment = $purchasePaymentTotal + $paymentVoucher;
        $todayBalance = ($saleReceivedTotal + $paymentReceivedVoucher) - ($TotalPayment + $expanseAmount);
        $todayReportArray = [$purchasePaymentTotal, $saleReceivedTotal, $expanseAmount,  $todayBalance];

        $todayReportTable = '<tr><td>Purchase Payment(-)</td><td>'.number_format($TotalPayment, 2).'</td></tr>
       <tr><td>Expense(-)</td><td>'.number_format($expanseAmount, 2).'</td></tr>
       <tr><td>Payment Received(+)</td><td>'.number_format($saleReceivedTotal + $paymentReceivedVoucher, 2).'</td></tr>
       <tr><td>Balance </td><td>'.number_format($todayBalance, 2).'</td></tr>';
        // -----End today payment, expense , payment received-------//

        return response()->json([$todayReportTable, $lastDailyReport, $todayReportArray]);
    }

    public function saveTodayReport(Request $request)
    {
        DB::beginTransaction();
        try {

            $date = $request->date;

            $dailyReport = DailyReport::where('date', $date)->where('deleted', 'No')->where('status', 'Active')->get()->last();
            if ($dailyReport) {
                $dailyReportId = $dailyReport->id;
                $isTodayDate = $dailyReport->date;
                if ($isTodayDate = $date) {
                    $dailyReport = DailyReport::find($dailyReportId);
                    $dailyReport->date = $date;
                    $dailyReport->previous_closing = $request->openingBalance; // (previous) openingBalance as (today) previous_closing
                    $dailyReport->today_closing = $request->totalAmount; // today credit
                    $dailyReport->opening_balance = $request->closingAmount;
                    $dailyReport->created_at = Carbon::now();
                    $dailyReport->created_by = auth()->user()->id;
                    $dailyReport->save();
                }
            } else {
                $dailyReport = new DailyReport;
                $dailyReport->date = $date;
                $dailyReport->previous_closing = $request->openingBalance; // (previous) openingBalance as (today) previous_closing
                $dailyReport->today_closing = $request->totalAmount; // today credit
                $dailyReport->opening_balance = $request->closingAmount;
                $dailyReport->status = 'Active';
                $dailyReport->deleted = 'No';
                $dailyReport->created_at = Carbon::now();
                $dailyReport->created_by = auth()->user()->id;
                $dailyReport->save();
            }

            DB::commit();

            return response()->json(['success' => 'report saved successfully.']);
        } catch (Exception $e) {
            DB::rollBack();

            return response()->json(['error' => 'report rollBack '.$e]);
        }
    }

    public function generateDailySummaryReport(Request $request)
    {

        $time = strtotime($request->date_from);
        $month = date('m', $time);
        $year = date('Y', $time);
        $day = date('d', $time);

        $date = $year.'-'.$month.'-'.$day;

        $income = ChartOfAccounts::where('name', '=', 'Income')->where('deleted', 'No')->first();
        $incomeId = $income ? $income->id : null;
        $allIncomes = $incomeId
            ? ChartOfAccounts::where('parent_id', '=', $incomeId)->where('deleted', 'No')->get()
            : collect();

        $sales = ChartOfAccounts::where('name', '=', 'Sales')->where('deleted', 'No')->first();
        $salesId = $sales ? $sales->id : null;
        $allsales = $salesId
            ? ChartOfAccounts::where('parent_id', '=', $salesId)->where('deleted', 'No')->get()
            : collect();

        $expense = ChartOfAccounts::where('name', '=', 'Expense')->where('deleted', 'No')->first();
        $expenseId = $expense ? $expense->id : null;

        $allExpense = $expenseId
            ? ChartOfAccounts::where('parent_id', '=', $expenseId)->where('deleted', 'No')->get()
            : collect();

        $purchase = ChartOfAccounts::where('name', '=', 'Purchase')->where('deleted', 'No')->first();
        $purchaseId = $purchase ? $purchase->id : null;
        $allpurchases = $purchaseId
            ? ChartOfAccounts::where('parent_id', '=', $purchaseId)->where('deleted', 'No')->get()
            : collect();

        $purchaseId = $purchase->id;
        $allpurchases = ChartOfAccounts::where('parent_id', '=', $purchaseId)->where('deleted', 'No')->get();

        $backDate = DailyReport::where('date', '<', $date)
            ->where('deleted', '=', 'No')
            ->where('status', '=', 'Active')
            ->orderBy('date', 'desc')
            ->first();
        if ($backDate != null) {
            $backDateFrom = $backDate->date;
        } else {
            $backDateFrom = date('Y-m-d', strtotime($date.' -1 day'));
        }

        $CashInHandFrom = DB::table('tbl_acc_voucher_details')
            ->join('tbl_acc_vouchers', 'tbl_acc_voucher_details.tbl_acc_voucher_id', '=', 'tbl_acc_vouchers.id')
            ->select('tbl_acc_voucher_details.*', 'tbl_acc_vouchers.transaction_date')
            ->where('tbl_acc_vouchers.transaction_date', '=', $backDateFrom)
            ->where('tbl_acc_vouchers.deleted', '=', 'No')
            ->where('tbl_acc_vouchers.status', '=', 'Active')
            ->get();
        $salesCashFrom = 0;
        foreach ($CashInHandFrom as $debit) {

            $salesCashFrom += $debit->debit;
        }

        $CashInHandTo = DB::table('tbl_acc_voucher_details')
            ->join('tbl_acc_vouchers', 'tbl_acc_voucher_details.tbl_acc_voucher_id', '=', 'tbl_acc_vouchers.id')
            ->select('tbl_acc_voucher_details.*', 'tbl_acc_vouchers.transaction_date')
            ->where('tbl_acc_vouchers.transaction_date', '=', $backDateFrom)

            ->where('tbl_acc_vouchers.deleted', '=', 'No')
            ->where('tbl_acc_vouchers.status', '=', 'Active')
            ->get();
        $salesCashTo = 0;
        foreach ($CashInHandTo as $credit) {
            $salesCashTo += $credit->credit;
        }

        /* start from here */
        $openings = DailyReport::where('date', '=', $backDateFrom)->where('deleted', '=', 'No')->where('status', '=', 'Active')->first();

        if ($openings != null) {
            $openingbalance = $openings->opening_balance;
        } else {
            $openingbalance = '0.00';
        }
        $openingCash = $salesCashFrom - $salesCashTo;
        $todayDateHeader = '';
        $todayDateHeader .= '<h4>Accounts summary of '.$date.'</h4>';
        $table = '';
        $closingBtn = '';
        $table .= '<table class="table table-bordered table-hover dataTable no-footer"  width="100%">
                    <tr>
                        <td>
                            <table class="table table-bordered table-hover dataTable no-footer">
                            <tbody>';

        $table .= '<tr>
                                            <td width="70%">Cash in hand</td>
                                            <td width="30%" class="text-right"><input type="hidden" id="previousDayClosing" value='.$openingbalance.'>'.number_format($openingbalance).'</td>
                                        </tr>';
        $totalIncome = 0;
        foreach ($allIncomes as $income) {
            $incomeAmounts = DB::table('tbl_acc_voucher_details')
                ->join('tbl_acc_vouchers', 'tbl_acc_voucher_details.tbl_acc_voucher_id', '=', 'tbl_acc_vouchers.id')
                ->select('tbl_acc_voucher_details.*', 'tbl_acc_vouchers.transaction_date')
                ->where('tbl_acc_vouchers.transaction_date', '=', $request->date_from)
                ->where('tbl_acc_voucher_details.tbl_acc_coa_id', '=', $income->id)
                ->where('tbl_acc_vouchers.deleted', '=', 'No')
                ->where('tbl_acc_vouchers.status', '=', 'Active')
                ->get();

            $amountDebitSum = 0;
            foreach ($incomeAmounts as $amount) {
                $amountDebitSum += $amount->debit;
            }

            $table .= '<tr>
                                                <td width="70%">'.$income->name.'</td>
                                                <td width="30%" class="text-right">'.number_format($amountDebitSum).'</td>
                                            </tr>';
            $totalIncome += $amountDebitSum;
        }

        //
        $totalSales = 0;
        foreach ($allsales as $sale) {
            $saleAmounts = DB::table('tbl_acc_voucher_details')
                ->join('tbl_acc_vouchers', 'tbl_acc_voucher_details.tbl_acc_voucher_id', '=', 'tbl_acc_vouchers.id')
                ->select('tbl_acc_voucher_details.*', 'tbl_acc_vouchers.transaction_date')
                ->where('tbl_acc_vouchers.transaction_date', '=', $request->date_from)
                ->where('tbl_acc_voucher_details.tbl_acc_coa_id', '=', $sale->id)
                ->where('tbl_acc_vouchers.deleted', '=', 'No')
                ->where('tbl_acc_vouchers.status', '=', 'Active')
                ->get();
            $amountDebitSum = 0;
            foreach ($saleAmounts as $amount) {
                $amountDebitSum += $amount->debit;
            }
            $table .= '<tr>
                                                <td width="70%">'.$sale->name.'</td>
                                                <td width="30%" class="text-right">'.number_format($amountDebitSum).'</td>
                                            </tr>';
            $totalSales += $amountDebitSum;
        }

        $totalIncomeWithOpening = $openingbalance + $totalIncome + $totalSales;

        $table .= '</tbody>
                            </table>
                        </td>
                        <td>
                            <table class="table table-bordered table-hover dataTable no-footer">
                            <tbody>';
        $billAmount = 0;
        $billpayments = DB::table('tbl_acc_vouchers')
            ->where('type', '=', 'Bill paid')
            ->where('tbl_acc_vouchers.transaction_date', '=', $request->date_from)
            ->where('tbl_acc_vouchers.deleted', '=', 'No')
            ->where('tbl_acc_vouchers.status', '=', 'Active')
            ->get();
        foreach ($billpayments as $bill) {
            $billAmount += $bill->amount;
        }
        $table .= '<tr>
                                                <td width="70%">Bill</td>
                                                <td width="30%" class="text-right">'.number_format($billAmount).'</td>
                                            </tr>';

        $purchaseSum = 0;
        foreach ($allpurchases as $purchase) {
            $purchaseAmounts = DB::table('tbl_acc_voucher_details')
                ->join('tbl_acc_vouchers', 'tbl_acc_voucher_details.tbl_acc_voucher_id', '=', 'tbl_acc_vouchers.id')
                ->select('tbl_acc_voucher_details.*', 'tbl_acc_vouchers.transaction_date', 'tbl_acc_vouchers.type')
                ->where('tbl_acc_vouchers.transaction_date', '=', $request->date_from)
                ->where('tbl_acc_voucher_details.tbl_acc_coa_id', '=', $purchase->id)
                ->where('tbl_acc_vouchers.deleted', '=', 'No')
                ->where('tbl_acc_vouchers.status', '=', 'Active')
                ->get();
            $amountSum = 0;
            foreach ($purchaseAmounts as $amount) {
                $amountSum += $amount->credit;
            }

            $table .= '<tr>
                                                <td width="70%">'.$purchase->name.'</td>
                                                <td width="30%" class="text-right">'.number_format($amountSum).'</td>
                                            </tr>';
            $purchaseSum += $amountSum;
        }

        $expenseSum = 0;
        foreach ($allExpense as $expense) {
            $incomeAmounts = DB::table('tbl_acc_voucher_details')
                ->join('tbl_acc_vouchers', 'tbl_acc_voucher_details.tbl_acc_voucher_id', '=', 'tbl_acc_vouchers.id')
                ->select('tbl_acc_voucher_details.*', 'tbl_acc_vouchers.transaction_date', 'tbl_acc_vouchers.type')
                ->where('tbl_acc_vouchers.transaction_date', '=', $request->date_from)
                ->where('tbl_acc_voucher_details.tbl_acc_coa_id', '=', $expense->id)
                ->where('tbl_acc_vouchers.deleted', '=', 'No')
                ->where('tbl_acc_vouchers.status', '=', 'Active')
                ->get();
            $amountSum = 0;

            foreach ($incomeAmounts as $amount) {
                $amountSum += $amount->credit;
            }

            $table .= '<tr>
                                                <td width="70%">'.$expense->name.'</td>
                                                <td width="30%" class="text-right">'.number_format($amountSum).'</td>
                                            </tr>';
            $expenseSum += $amountSum;
        }
        $totalExpense = $expenseSum + $billAmount + $purchaseSum;
        $table .= '</tbody>
                            </table>
                        </td>
                        
                    </tr>
                    <tr>
                        <td>
                            <table class="table table-bordered table-hover dataTable no-footer">
                                <tbody>
                                    <tr>
                                        <td width="70%">Total Income: </td>
                                        <td width="30%" class="text-right">'.number_format($totalIncomeWithOpening).'</td>
                                    </tr>
                                </tbody>
                            </table>
                        </td>
                        <td>
                            <table class="table table-bordered table-hover dataTable no-footer">
                                <tbody>
                                    <tr>
                                        <td width="70%">Total Expense: </td>
                                        <td width="30%" class="text-right">'.number_format($totalExpense).'</td>
                                    </tr>
                                </tbody>
                            </table>
                        </td>
                    </tr>';
        $due = $totalIncomeWithOpening - $totalExpense;
        if ($totalIncomeWithOpening < $totalExpense) {
            $incomeClosing = $due;
            $expenseClosing = '0';
        } elseif ($totalIncomeWithOpening > $totalExpense) {
            $incomeClosing = '0';
            $expenseClosing = $due;
        } else {
            $incomeClosing = '0';
            $expenseClosing = '0';
        }
        //  return $due;
        $table .= '<tr>
                        <td>
                            <table class="table table-bordered table-hover dataTable no-footer">
                                <tbody>
                                    <tr>
                                        <td width="70%">Balance Closing: </td> <input type="hidden" id="due" value='.$due.'>
                                        <td width="30%" class="text-right">'.number_format($incomeClosing).Session::get('companySettings')[0]['currency'].'</td>
                                    </tr>
                                </tbody>
                            </table>
                        </td>
                        <td>
                            <table class="table table-bordered table-hover dataTable no-footer">
                                <tbody>
                                    <tr>
                                        <td width="70%">Balance Closing: </td>
                                        <td width="30%" class="text-right">'.number_format($expenseClosing).Session::get('companySettings')[0]['currency'].'</td>
                                    </tr>
                                </tbody>
                            </table>
                        </td>
                    </tr>';
        $totalIncomeWithDue = $totalIncomeWithOpening - $incomeClosing;
        $totalExpenseWithDue = $totalExpense + $expenseClosing;
        $table .= '<tr>
                        <td>
                            <table class="table table-bordered table-hover dataTable no-footer">
                                <tbody>
                                    <tr>
                                        <td width="70%">Total : </td>
                                        <td width="30%" class="text-right">'.number_format($totalIncomeWithDue).Session::get('companySettings')[0]['currency'].'</td>
                                    </tr>
                                </tbody>
                            </table>
                        </td>
                        <td>
                            <table class="table table-bordered table-hover dataTable no-footer">
                                <tbody>
                                    <tr>
                                        <td width="70%">Total : </td>
                                        <td width="30%" class="text-right">'.number_format($totalExpenseWithDue).Session::get('companySettings')[0]['currency'].'</td>
                                    </tr>
                                </tbody>
                            </table>
                        </td>
                    </tr>
                </table>';
        $lastTwoChar = substr(($request->date_to), -2);
        $time = strtotime($request->date_to);
        $month = date('F', $time);
        $year = date('Y', $time);
        $monthYear = date('F Y', $time);

        $month_value = date('m', strtotime($month));
        $month_days_count = cal_days_in_month(CAL_GREGORIAN, $month_value, $year);

        /* if($lastTwoChar == $month_days_count){ */
        $closingBtn = '<button class="btn btn-primary float-right" onclick="closeBalance()">Close balance</button>';
        /*  }else{
                $closingBtn='<span class="text-danger">Select the last day of the month to close the balance</span><button class="btn btn-secondary float-right disabled" >Close balance</button>';
            } */
        $pdf = '';
        $pdf = '<button class="btn btn-primary" onclick="generateAccountsSummaryPdf()"><i class="fas fa-print"></i> Print PDF</button>';
        $data = [
            'table' => $table,
            'todayDateHeader' => $todayDateHeader,
            'closingBtn' => $closingBtn,
            'pdf' => $pdf,
        ];

        return $data;
    }

    public function closingDayBalanceStore(Request $request)
    {

        $date = $request->date_from;
        $checkPreviousDate = '';
        $checkPreviousDate = DailyReport::where('date', '=', $date)->first();

        if ($checkPreviousDate != null) {
            $checkPreviousDate->date = $date;
            $checkPreviousDate->previous_closing = $request->previousDayClosing;
            $checkPreviousDate->today_closing = $request->presentClosingBalance;
            $checkPreviousDate->opening_balance = $request->presentClosingBalance;
            $checkPreviousDate->last_updated_by = Auth::user()->id;
            $checkPreviousDate->save();

            return response()->json(['success' => 'Closing balance updated successfully']);
        } else {
            $closing = new DailyReport;
            $closing->date = $date;
            $closing->previous_closing = $request->previousDayClosing;
            $closing->opening_balance = $request->presentClosingBalance;
            $closing->today_closing = $request->presentClosingBalance;
            $closing->created_by = Auth::user()->id;
            $closing->deleted = 'No';
            $closing->status = 'Active';
            $closing->save();

            return response()->json(['success' => 'Closing balance saved successfully']);
        }
    }

    public function generateDailyAccountsSummaryPdf($date)
    {

        $time = strtotime($date);
        $month = date('m', $time);
        $year = date('Y', $time);
        $day = date('d', $time);

        $income = ChartOfAccounts::where('name', '=', 'Income')->first();
        $incomeId = $income ? $income->id : null;
        $allIncomes = $incomeId
            ? ChartOfAccounts::where('parent_id', '=', $incomeId)->get()
            : collect();

        $sales = ChartOfAccounts::where('name', '=', 'Sale')->first();
        $salesId = $sales ? $sales->id : null;
        $allsales = $salesId
            ? ChartOfAccounts::where('parent_id', '=', $salesId)->get()
            : collect();

        $expense = ChartOfAccounts::where('name', '=', 'Expense')->first();
        $expenseId = $expense ? $expense->id : null;
        $allExpense = $expenseId
            ? ChartOfAccounts::where('parent_id', '=', $expenseId)->get()
            : collect();

        $purchase = ChartOfAccounts::where('name', '=', 'Purchase')->first();
        $purchaseId = $purchase ? $purchase->id : null;
        $allpurchases = $purchaseId
            ? ChartOfAccounts::where('parent_id', '=', $purchaseId)->get()
            : collect();

        $backDate = DailyReport::where('date', '<', $date)
            ->where('deleted', '=', 'No')
            ->where('status', '=', 'Active')
            ->orderBy('date', 'desc')
            ->first();
        if ($backDate != null) {
            $backDateFrom = $backDate->date;
        } else {
            $backDateFrom = date('Y-m-d', strtotime($date.' -1 day'));
        }

        /* start from here */
        $openings = DailyReport::where('date', '=', $backDateFrom)->where('deleted', '=', 'No')->where('status', '=', 'Active')->first();

        if ($openings != null) {
            $openingbalance = $openings->opening_balance;
        } else {
            $openingbalance = '0.00';
        }

        $CashInHandFrom = 0;
        $CashInHandFrom = DB::table('tbl_acc_voucher_details')
            ->join('tbl_acc_vouchers', 'tbl_acc_voucher_details.tbl_acc_voucher_id', '=', 'tbl_acc_vouchers.id')
            ->select('tbl_acc_voucher_details.*', 'tbl_acc_vouchers.transaction_date')
            ->where('tbl_acc_vouchers.transaction_date', '=', $backDateFrom)
            ->where('tbl_acc_vouchers.deleted', '=', 'No')
            ->where('tbl_acc_vouchers.status', '=', 'Active')
            ->get();
        $salesCashFrom = 0;
        foreach ($CashInHandFrom as $debit) {
            $salesCashFrom += $debit->debit;
        }
        $CashInHandTo = DB::table('tbl_acc_voucher_details')
            ->join('tbl_acc_vouchers', 'tbl_acc_voucher_details.tbl_acc_voucher_id', '=', 'tbl_acc_vouchers.id')
            ->select('tbl_acc_voucher_details.*', 'tbl_acc_vouchers.transaction_date')
            ->where('tbl_acc_vouchers.transaction_date', '=', $backDateFrom)
            ->where('tbl_acc_vouchers.deleted', '=', 'No')
            ->where('tbl_acc_vouchers.status', '=', 'Active')
            ->get();
        $salesCashTo = 0;
        foreach ($CashInHandTo as $credit) {
            $salesCashTo += $credit->credit;
        }
        $openingCash = $salesCashFrom - $salesCashTo;

        $billpayments = DB::table('tbl_acc_vouchers')
            ->where('type', '=', 'Bill paid')
            ->where('tbl_acc_vouchers.transaction_date', '=', $date)
            ->where('tbl_acc_vouchers.deleted', '=', 'No')
            ->where('tbl_acc_vouchers.status', '=', 'Active')
            ->get();

        $billAmount = 0;
        foreach ($billpayments as $bill) {
            $billAmount += $bill->amount;
        }

        $pdf = PDF::loadView(
            'admin.reports.accountsDailySummaryPdf',
            [
                'openingCash' => $openingCash,
                'openingbalance' => $openingbalance,
                'allIncomes' => $allIncomes,
                'date' => $date,
                'allsales' => $allsales,
                'allpurchases' => $allpurchases,
                'billAmount' => $billAmount,
                'allExpense' => $allExpense,
            ]
        );

        return $pdf->stream('accounts-daily-summary-pdf.pdf', ['Attachment' => false]);
    }

    public function dailyServiceLedgerReport()
    {
        return view('admin.reports.serviceCenterDailySummary');
    }

    public function generateDailyServiceReport(Request $request)
    {

        $complete_orders = DB::table('sale_orders')
            ->leftjoin('parties', 'sale_orders.customer_id', '=', 'parties.id')
            ->select('sale_orders.*', 'parties.name as partyName', 'parties.contact')
            ->where('sale_orders.order_status', '=', 'Completed')
            ->where('sale_orders.completed_date', '=', $request->date_from)
            ->where('sale_orders.deleted', '=', 'No')
            ->where('sale_orders.status', '=', 'Active')
            ->get();
        $table = '';

        $table .= '<h3 class="text-center" style="padding-top:20px;">Completed Jobs</h3>
            <table  class="table table-bordered table-hover dataTable no-footer">
            <thead>';
        $table .= '<tr>
                    <td width="5%"><b>Sl.</b></td>
                    <td width="8%"><b>Job No.</b></td>
                    <td width="15%"><b>COA</b></td>
                    <td width="20%"><b>Party Info</b></td>
                    <td width="15%" class="text-right"><b>Job Amount</b></td>
                    <td width="15%" class="text-right"><b>Sale Amount</b></td>
                    <td width="15%" class="text-right"><b>Balance</b></td>
                    <td class="text-center" width="12%"><b>Status</b></td>
            </tr>
            </thead>
            <tbody>';
        $i = 1;
        $total_amount_sum = 0;
        $total_sale_amount_sum = 0;
        $pdfButton = '';
        foreach ($complete_orders as $order) {
            $coa = ChartOfAccounts::find($order->category);
            $coaName = $coa ? $coa->name : 'N/A';
            $table .= '<tr>
                        <td>'.$i++.'</td>
                        <td>'.$order->sale_no.'</td>
                        <td>'.$coaName.'</td>
                        <td>Name: '.$order->partyName.'<br>Contact: '.$order->contact.'</td>
                        <td class="text-right">'.number_format($order->grand_total).' '.Session::get('companySettings')[0]['currency'].'</td>
                        <td class="text-right">'.number_format($order->final_sale_amount).' '.Session::get('companySettings')[0]['currency'].'</td>
                        <td class="text-right">'.number_format(($order->grand_total) - ($order->final_sale_amount)).' '.Session::get('companySettings')[0]['currency'].'</td>
                        <td class="text-center">'.$order->order_status.'</td>
                    </tr>';
            $total_amount_sum += $order->grand_total;
            $total_sale_amount_sum += $order->final_sale_amount;
        }

        $table .= '<tr>
                        <td colspan="4" class="text-right"><b>Total: </b></td>
                        <td class="text-right"><b>'.number_format($total_amount_sum).' '.Session::get('companySettings')[0]['currency'].'</b></td>
                        <td class="text-right"><b>'.number_format($total_sale_amount_sum).' '.Session::get('companySettings')[0]['currency'].'</b></td>
                        <td class="text-right"><b>'.number_format(($total_amount_sum - $total_sale_amount_sum)).' '.Session::get('companySettings')[0]['currency'].'</b></td>
                        <td></td>
                  </tr>';

        $table .= '</tbody>';

        $table .= '</table>';

        $table .= '<h3 class="text-center" style="padding-top:30px;">Other Jobs</h3>';
        $table .= '<table class="table table-bordered table-hover dataTable no-footer">';

        $table .= '<thead>';
        $table .= '<tr>
                    <td width="5%"><b>SL</b></td>
                    <td width="8%"><b>Job No</b></td>
                    <td width="15%"><b>COA</b></td>
                    <td width="20%"><b>Party Info</b></td>
                    <td width="15%" class="text-right"><b>Amount</b></td>
                    <td width="15%" class="text-center"><b>Status</b></td>
                    <td width="23%"><b>Remarks</b></td>
            </tr>';
        $table .= '<thead>';

        $table .= '<tbody>';

        $other_orders = DB::table('sale_orders')
            ->leftjoin('parties', 'sale_orders.customer_id', '=', 'parties.id')
            ->select('sale_orders.*', 'parties.name as partyName', 'parties.contact')
            ->where('sale_orders.order_status', '!=', 'Completed')
            ->where('sale_orders.created_date', '=', $request->date_from)
            ->where('sale_orders.deleted', '=', 'No')
            ->where('sale_orders.status', '=', 'Active')
            ->get();

        $i - 1;
        $totalSum = 0;
        foreach ($other_orders as $order) {
            $coa = ChartOfAccounts::find($order->category);
            $coaName = $coa ? $coa->name : 'N/A';
            $table .= '<tr>
                    <td>'.$i++.'</td>
                    <td>'.$order->sale_no.'</td>
                    <td>'.$coaName.'</td>
                    <td>Name: '.$order->partyName.'<br>Contact: '.$order->contact.'</td>
                    <td class="text-right">'.$order->grand_total.' '.Session::get('companySettings')[0]['currency'].'</td>
                    <td class="text-center">'.$order->order_status.'</td>
                    <td></td>
            </tr>';
            $totalSum += $order->grand_total;
        }
        $table .= '<tr> 
                    <td colspan="4" class="text-right"><b>Total : </b></td>
                    <td class="text-right">'.number_format($totalSum).' '.Session::get('companySettings')[0]['currency'].'</td>
                    <td colspan="2" ></td>
                </tr>';
        $table .= '<tbody>';
        $table .= '</table>';

        $table .= '<h3 class="text-center" style="padding-top:30px;">WI Sales</h3>';
        $table .= '<table class="table table-bordered table-hover dataTable no-footer">';
        $table .= '<thead>';
        $table .= '<tr>
                <td width="5%"><b>SL</b></td>
                <td class="text-center" width="15%"><b>Sale No</b></td>
                <td class="text-center" width="20%"><b>COA</b></td>
                <td width="30%"><b>Party Info</b></td>
                <td width="15%" class="text-right"><b>Total</b></td>
                <td width="15%" class="text-right"><b>Paid</b></td>
            </tr>';
        $table .= '</thead>';
        $table .= '<tbody>';

        $sales = DB::table('sales')
            ->leftjoin('parties', 'sales.customer_id', '=', 'parties.id')
            ->leftjoin('tbl_acc_coas', 'sales.coa_id', '=', 'tbl_acc_coas.id')
            ->select('sales.*', 'parties.name as partyName', 'parties.contact', 'tbl_acc_coas.name as coaName')
            ->where('sales.status', '=', 'Active')
            ->where('sales.date', '=', $request->date_from)
            ->where('sales.type', '=', 'walkin')
            ->where('sales.deleted', '=', 'No')
            ->get();
        $i = 1;
        $grandTotalSum = 0;
        $paidTotalSum = 0;
        foreach ($sales as $sale) {
            $table .= '<tr>
                <td>'.$i++.'</td>
                <td class="text-center">'.$sale->sale_no.'</td>
                <td class="text-center">'.$sale->coaName.'</td>
                <td >Name: '.$sale->partyName.'<br>Contact: '.$sale->contact.'</td>
                <td class="text-right">'.$sale->grand_total.' '.Session::get('companySettings')[0]['currency'].'</td>
                <td class="text-right">'.$sale->current_payment.' '.Session::get('companySettings')[0]['currency'].'</td>
            </tr>';
            $grandTotalSum += $sale->grand_total;
            $paidTotalSum += $sale->current_payment;
        }
        $table .= '<tr>
                <td colspan="4" class="text-right">Total :</td>
                <td class="text-right">'.$grandTotalSum.' '.Session::get('companySettings')[0]['currency'].'</td>
                <td class="text-right">'.$paidTotalSum.' '.Session::get('companySettings')[0]['currency'].'</td>
            </tr>';
        $table .= '</tbody>';
        $table .= '</table>';

        $income = ChartOfAccounts::where('name', '=', 'Income')->where('deleted', 'No')->first();
        $incomeId = $income ? $income->id : 3;
        $coas = ChartOfAccounts::where('parent_id', '=', $incomeId)->where('name', '!=', 'Sales')->get();
        $table .= '<h3 class="text-center" style="padding-top:30px;">Total Jobs</h3>';
        $table .= '<table class="table table-bordered table-hover dataTable no-footer">';
        $table .= '<thead>';
        $table .= '<tr>
                <td>Service COA</td>
                <td class="text-center">Pending</td>
                <td class="text-center">Servicing</td>
                <td class="text-center">Ready For Delivery</td>
                <td class="text-center">Delivered</td>
                <td class="text-center">Completed</td>
            </tr>';
        $table .= '</thead>';
        $table .= '<tbody>';
        foreach ($coas as $coa) {

            $totalPending = SaleOrder::where('order_status', '=', 'Pending')->where('category', '=', $coa->id)->where('created_date', '=', $request->date_from)->count();
            $totalServicing = SaleOrder::where('order_status', '=', 'Servicing')->where('category', '=', $coa->id)->where('service_start_date', '=', $request->date_from)->count();
            $totalReady = SaleOrder::where('order_status', '=', 'ReadyToDeliverd')->where('category', '=', $coa->id)->where('ready_to_deliver_date', '=', $request->date_from)->count();
            $totalDelivered = SaleOrder::where('order_status', '=', 'Delivered')->where('category', '=', $coa->id)->where('delivered_date', '=', $request->date_from)->count();
            $totalCompleted = SaleOrder::where('order_status', '=', 'Completed')->where('category', '=', $coa->id)->where('completed_date', '=', $request->date_from)->count();

            $table .= '<tr>
                <td>'.$coa->name.'</td>
                <td class="text-center">'.$totalPending.'</td>
                <td class="text-center">'.$totalServicing.'</td>
                <td class="text-center">'.$totalReady.'</td>
                <td class="text-center">'.$totalDelivered.'</td>
                <td class="text-center">'.$totalCompleted.'</td>
            </tr>';
        }
        $table .= '</tbody>';
        $table .= '</table>';

        $pdfButton .= '<br><button class="btn btn-primary" onclick="generatePdf()"><i class="fas fa-print"></i> Generate</button>';

        $array = ['table' => $table, 'pdf' => $pdfButton];

        return $array;
    }

    public function ServiceLedgerReportPdf($date)
    {

        $orders = DB::table('sale_orders')
            ->leftjoin('parties', 'sale_orders.customer_id', '=', 'parties.id')
            ->select('sale_orders.*', 'parties.name as partyName', 'parties.contact')
            ->where('sale_orders.order_status', '=', 'Completed')
            ->where('sale_orders.completed_date', '=', $date)
            ->where('sale_orders.deleted', '=', 'No')
            ->where('sale_orders.status', '=', 'Active')
            ->get();

        $other_orders = DB::table('sale_orders')
            ->leftjoin('parties', 'sale_orders.customer_id', '=', 'parties.id')
            ->select('sale_orders.*', 'parties.name as partyName', 'parties.contact')
            ->where('sale_orders.order_status', '!=', 'Completed')
            ->where('sale_orders.created_date', '=', $date)
            ->where('sale_orders.deleted', '=', 'No')
            ->where('sale_orders.status', '=', 'Active')
            ->get();

        $income = ChartOfAccounts::where('name', '=', 'Income')->where('deleted', 'No')->first();
        $incomeId = $income ? $income->id : 3;
        $coas = ChartOfAccounts::where('parent_id', '=', $incomeId)->where('name', '!=', 'Sales')->get();
        $pdf = PDF::loadView(
            'admin.reports.serviceCenterDailyPdf',
            [
                'orders' => $orders, 'date' => $date,
                'other_orders' => $other_orders,
                'coas' => $coas,
            ]
        );

        return $pdf->stream('accounts-daily-summary-pdf.pdf', ['Attachment' => false]);
    }
}
