<?php

namespace App\Http\Controllers\Admin\Inventory;

use App\Http\Controllers\Controller;
use App\Models\Accounts\ChartOfAccounts;
use App\Models\Accounts\Voucher;
use App\Models\Accounts\VoucherDetails;
use App\Models\inventory\Emi_sale;
use App\Models\inventory\Party;
use App\Models\inventory\PaymentVoucher;
use App\Models\inventory\WorkOrder;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use PDF;

use function PHPSTORM_META\type;

class VoucherController extends Controller
{
    public function index($type, Request $request)
    {
        $type = ucfirst($type);
        $parties = Party::where('deleted', '=', 'No')->get();
        $suppliers = Party::where('deleted', '=', 'No')->get();

        $sortBy = $request->sort_by ?? 'payment_vouchers.id';
        $sortDir = $request->sort_direction ?? 'DESC';
        $limit = $request->limit ?? 10;

        if ($type == 'Payment') {
            $query = DB::table('payment_vouchers')
                ->leftjoin('parties', 'payment_vouchers.party_id', '=', 'parties.id')
                ->leftjoin('purchases', 'payment_vouchers.purchase_id', '=', 'purchases.id')
                ->whereRaw("(payment_vouchers.type=? or payment_vouchers.type='Payable') and (payment_vouchers.deleted='No')", [$type])
                ->whereNull('expense_id')
                ->select(
                    'payment_vouchers.id',
                    'payment_vouchers.purchase_id',
                    'payment_vouchers.sales_id',
                    'payment_vouchers.type as voucherType',
                    'payment_vouchers.amount',
                    'payment_vouchers.payment_method',
                    'payment_vouchers.paymentDate',
                    'payment_vouchers.voucherNo',
                    'parties.name',
                    'parties.code',
                    'parties.contact',
                    'parties.alternate_contact',
                    'payment_vouchers.remarks',
                    'purchases.purchase_no as invoiceNo',
                    'purchases.date',
                    'purchases.grand_total'
                );
            $voucherType = 'Purchase';
            $amountStatus = 'Payment';
        } elseif ($type == 'Payment Received') {
            $query = DB::table('payment_vouchers')
                ->join('parties', 'payment_vouchers.party_id', '=', 'parties.id')
                ->leftjoin('sales', 'payment_vouchers.sales_id', '=', 'sales.id')
                ->whereRaw("(payment_vouchers.type=? or payment_vouchers.type='Party Payable') and (payment_vouchers.deleted='No')", [$type])
                ->select(
                    'payment_vouchers.id',
                    'payment_vouchers.purchase_id',
                    'payment_vouchers.sales_id',
                    'payment_vouchers.type as voucherType',
                    'payment_vouchers.amount',
                    'payment_vouchers.payment_method',
                    'payment_vouchers.paymentDate',
                    'payment_vouchers.voucherNo',
                    'parties.name',
                    'parties.code',
                    'parties.contact',
                    'parties.alternate_contact',
                    'payment_vouchers.remarks',
                    'sales.sale_no as invoiceNo',
                    'sales.date',
                    'sales.grand_total'
                );
            $voucherType = 'Sale';
            $amountStatus = 'Received';
        } elseif ($type == 'Discount') {
            $query = DB::table('payment_vouchers')
                ->join('parties', 'payment_vouchers.party_id', '=', 'parties.id')
                ->leftjoin('sales', 'payment_vouchers.sales_id', '=', 'sales.id')
                ->whereRaw("(payment_vouchers.type='Discount' and payment_vouchers.deleted='No')")
                ->select(
                    'payment_vouchers.id',
                    'payment_vouchers.purchase_id',
                    'payment_vouchers.sales_id',
                    'payment_vouchers.type as voucherType',
                    'payment_vouchers.amount',
                    'payment_vouchers.payment_method',
                    'payment_vouchers.paymentDate',
                    'payment_vouchers.voucherNo',
                    'parties.name',
                    'parties.code',
                    'parties.contact',
                    'parties.alternate_contact',
                    'payment_vouchers.remarks',
                    'sales.sale_no as invoiceNo',
                    'sales.date',
                    'sales.grand_total'
                );
            $voucherType = '';
            $amountStatus = 'Discount';
        }

        if ($search = $request->q) {
            $query->where(function ($q) use ($search) {
                $q->where('payment_vouchers.voucherNo', 'like', "%{$search}%")
                    ->orWhere('parties.name', 'like', "%{$search}%");
            });
        }

        $vouchers = $query->orderBy($sortBy, $sortDir)->paginate($limit)->appends($request->all());

        return view('admin.inventory.voucher.view-voucher', compact('type', 'parties', 'suppliers', 'vouchers', 'voucherType', 'amountStatus'));
    }

    public function getProjects(Request $request)
    {
        $projects = DB::table('projects')->get();
        $data = "<option value='0' selected>Select Project</option>";
        foreach ($projects as $project) {
            $data .= "<option value='".$project->id."'>".$project->name.'</option>';
        }

        return $data;
    }

    public function loadWorkOrder(Request $request)
    {
        $getorders = WorkOrder::where('project_id', '=', $request->project_id)->where('deleted', '=', 'No')->get();
        $data = "<option value='0' selected>Select Work Order</option>";
        foreach ($getorders as $getorder) {
            $data .= "<option value='".$getorder->id."'>".$getorder->order_no.'</option>';
        }

        return $data;
    }

    public function loadParties(Request $request)
    {
        // return $request->type;
        if ($request->type == 'Payment') {
            $getparties = DB::table('purchases')
                ->join('parties', 'purchases.supplier_id', '=', 'parties.id')
                ->select('parties.*', 'purchases.work_order_id')
                ->where('purchases.work_order_id', '=', $request->work_order_id)
                ->where('parties.deleted', '=', 'No')
                ->get();
            $data .= "<option value='0' selected>Select Party</option>";
            foreach ($getparties as $getparty) {
                $data .= "<option value='".$getparty->id."'>".$getparty->name.'</option>';
            }
        } else {
            $getparties = DB::table('work_orders')
                ->join('parties', 'work_orders.party_id', '=', 'parties.id')
                ->select('parties.*', 'work_orders.id', 'work_orders.party_id')
                ->where('work_orders.id', '=', $request->work_order_id)
                ->where('parties.deleted', '=', 'No')
                ->get();
            $data = "<option value='0' selected>Select Party</option>";
            foreach ($getparties as $getparty) {
                $data .= "<option value='".$getparty->party_id."'>".$getparty->name.'</option>';
            }
        }

        return $data;
    }

    public function loadPartyDue(Request $request)
    {
        /*$getDue=WorkOrder::find($request->work_order_id);
        if($getDue->due == null){
            return $getDue->amount;
        }else{
            return $getDue->due;
        }*/
        $paymentVouchers = DB::table('payment_vouchers')
            // ->join('parties','payment_vouchers.party_id','=','parties.id')
            // ->leftjoin('','.id','=','payment_vouchers.project_id')
            // ->leftjoin(',.id','=','payment_vouchers.work_order_id')
            // ->leftjoin('users', 'payment_vouchers.entryBy', '=', 'users.id')
            /*->select('payment_vouchers.id','payment_vouchers.purchase_id','payment_vouchers.sales_id','payment_vouchers.type','payment_vouchers.amount','payment_vouchers.payment_method',
        				            'payment_vouchers.paymentDate','payment_vouchers.voucherNo','users.name as entryBy','payment_vouchers.remarks','parties.name','parties.code','parties.contact',
        				            'parties.alternate_contact','parties.address','payment_vouchers.remarks','.project_name',.order_no')
    						->orderby('payment_vouchers.id','DESC')*/
            ->select('payment_vouchers.amount', 'payment_vouchers.type')
            ->where('payment_vouchers.work_order_id', $request->work_order_id)
            ->where('payment_vouchers.deleted', 'No')
            ->get();
        $due = 0;
        foreach ($paymentVouchers as $paymentVoucher) {
            if ($paymentVoucher->type == 'Party Payable') {
                $due += floatval($paymentVoucher->amount);
            } elseif ($paymentVoucher->type == 'Payment Received') {
                $due -= floatval($paymentVoucher->amount);
            } elseif ($paymentVoucher->type == 'Discount') {
                $due -= floatval($paymentVoucher->amount);
            }
        }

        return $due;
    }

    public function store(Request $request)
    {
        $request->validate([
            'amount' => 'required|max:13|regex:/^([a-zA-Z0-9_ "\.\-\s\,\;\:\/\&\$\%\(\)]+\s)*[a-zA-Z0-9_ "\.\-\s\,\;\:\/\&\$\%\(\)]+$/u',
            'type' => 'required',
            'paymentDate' => 'required',
            'remarks' => 'nullable|regex:/^([a-zA-Z0-9_ "\.\-\s\,\;\:\/\&\$\%\(\)]+\s)*[a-zA-Z0-9_ "\.\-\s\,\;\:\/\&\$\%\(\)]+$/u',
            'party_id' => 'required',
            'payment_method' => 'required',
        ]);
        DB::beginTransaction();

        try {
            $request->type = ucfirst($request->type);
            $maxCode = PaymentVoucher::where('deleted', 'No')->max('voucherNo');
            $maxCode++;
            $maxCode = str_pad($maxCode, 6, '0', STR_PAD_LEFT);
            $PaymentVoucher = new PaymentVoucher;
            $PaymentVoucher->party_id = $request->party_id;
            $PaymentVoucher->voucherNo = $maxCode;
            $PaymentVoucher->amount = $request->amount;
            $PaymentVoucher->entryBy = auth()->user()->id;
            $PaymentVoucher->payment_method = $request->payment_method;
            $PaymentVoucher->paymentDate = $request->paymentDate;
            $PaymentVoucher->type = $request->type;

            $PaymentVoucher->remarks = 'Voucher Entry for ___'.$request->remarks.'___'.$request->type.'_['.$maxCode.']';

            $PaymentVoucher->save();

            $party = Party::find($request->party_id);
            if ($request->type == 'Payment Received') {
                $party->decrement('current_due', $request->amount);
            } elseif ($request->type == 'Payment') {
                $party->increment('current_due', $request->amount);
            } elseif ($request->type == 'Discount') {
                if ($party->party_type == 'Customer') {
                    $party->decrement('current_due', $request->amount);
                } elseif ($party->party_type == 'Supplier') {
                    $party->increment('current_due', $request->amount);
                } elseif ($party->party_type == 'Walkin_Customer') {
                    $party->decrement('current_due', $request->amount);
                }
            }

            if ($request->type == 'Payment Received') {
                $configId = ChartOfAccounts::where('slug', 'cash')->first();
                $configId->increment('amount', $request->amount);
            }
            if ($request->type == 'Payment') {
                $configId = ChartOfAccounts::where('slug', 'cash')->first();
                $configId->decrement('amount', $request->amount);
            }

            /* $party = Party::find($request->party_id);
            if ($request->type == 'Payment Received') {
                $party->decrement('current_due', $request->amount);
                $configId = ChartOfAccounts::where('name', '=', 'Accrued Income')->first();
            } else if ($request->type == 'Payment') {
                $party->increment('current_due', $request->amount);
                $configId = ChartOfAccounts::where('name', '=', 'Accrued Expense')->first();
            } else if ($request->type == 'Discount') {
                if ($party->party_type == "Customer") {
                    $party->decrement('current_due', $request->amount);
                    $configId = ChartOfAccounts::where('slug', '=', 'discount-received')->first();
                } else if ($party->party_type == "Supplier") {
                    $party->increment('current_due', $request->amount);
                    $configId = ChartOfAccounts::where('slug', '=', 'discount-allow')->first();
                } elseif ($party->party_type == 'Walkin_Customer') {
                    $party->decrement('current_due', $request->amount);
                    $configId = ChartOfAccounts::where('slug', '=', 'discount-received')->first();
                }
            } */

            $voucher = new Voucher;
            $voucher->vendor_id = $request->party_id;
            $voucher->transaction_date = $request->paymentDate;
            $voucher->amount = floatval($request->amount);
            $voucher->payment_method = $request->payment_method;
            $voucher->particulars = $request->remarks;
            $voucher->deleted = 'No';
            $voucher->status = 'Active';
            $voucher->created_by = Auth::user()->id;
            $voucher->created_date = date('Y-m-d h:s');
            $voucher->payment_voucher_id = $PaymentVoucher->id;
            $voucher->save();
            $voucherId = $voucher->id;

            $voucherDetails = new VoucherDetails;
            $voucherDetails->tbl_acc_voucher_id = $voucherId;
            $voucherDetails->tbl_acc_coa_id = $configId->id;
            $voucherDetails->transaction_date = $request->paymentDate;
            $voucherDetails->payment_voucher_id = $PaymentVoucher->id;

            if ($request->type == 'Payment Received') {
                $voucherDetails->debit = floatval($request->amount);
                $voucherDetails->voucher_title = 'Payment received with voucher Code '.$maxCode;
            } elseif ($request->type == 'Payment') {
                $voucherDetails->credit = floatval($request->amount);
                $voucherDetails->voucher_title = 'Pyment paid with voucher Code '.$maxCode;
            } elseif ($request->type == 'Discount') {
                if ($party->party_type == 'Customer') {
                    $voucherDetails->debit = floatval($request->amount);
                    $voucherDetails->voucher_title = 'Customer discount with voucher  code '.$maxCode;
                } elseif ($party->party_type == 'Walkin_Customer') {
                    $voucherDetails->debit = floatval($request->amount);
                    $voucherDetails->voucher_title = 'Walkin Customer discount with voucher code '.$maxCode;
                } elseif ($party->party_type == 'Supplier') {
                    $voucherDetails->credit = floatval($request->amount);
                    $voucherDetails->voucher_title = 'Supplier discount with voucher code '.$maxCode;
                }
            }
            $voucherDetails->particulars = $request->remarks;
            $voucherDetails->deleted = 'No';
            $voucherDetails->status = 'Active';
            $voucherDetails->created_by = Auth::user()->id;
            $voucherDetails->created_date = date('Y-m-d h:s');
            $voucherDetails->save();

            DB::commit();

            return response()->json(['success' => $request->type.' Voucher saved successfully']);
        } catch (\Exception $e) {
            DB::rollback();

            return response()->json(['success' => $e->getMessage()]);
        }
    }

    public function voucherDelete(Request $request)
    {

        $PaymentVoucher = PaymentVoucher::find($request->id);
        $PaymentVoucher->deleted = 'Yes';
        $PaymentVoucher->status = 'Inactive';
        $PaymentVoucher->deleted_date = Carbon::now();
        $PaymentVoucher->deleted_by = auth()->user()->id;
        $PaymentVoucher->save();

        $vouchers = Voucher::where('payment_voucher_id', '=', $request->id)->first();
        $vouchers->deleted = 'Yes';
        $vouchers->status = 'Inactive';
        $vouchers->deleted_date = Carbon::now();
        $vouchers->deleted_by = auth()->user()->id;
        $vouchers->save();

        $voucherDetails = VoucherDetails::where('payment_voucher_id', '=', $request->id)->first();
        $voucherDetails->deleted = 'Yes';
        $voucherDetails->status = 'Inactive';
        $voucherDetails->deleted_date = Carbon::now();
        $voucherDetails->deleted_by = auth()->user()->id;
        $voucherDetails->save();

        if ($PaymentVoucher->type == 'Payment Received') {
            $party = Party::find($PaymentVoucher->party_id);
            $party->increment('current_due', $PaymentVoucher->amount);
        } elseif ($PaymentVoucher->type == 'Payment') {
            $party = Party::find($PaymentVoucher->party_id);
            $party->decrement('current_due', $PaymentVoucher->amount);
        }
    }

    public function emiPaymentView(Request $request)
    {
        $type = 'Payment Received';

        $sortBy = $request->sort_by ?? 'sales.id';
        $sortDir = $request->sort_direction ?? 'DESC';
        $limit = $request->limit ?? 10;

        $query = DB::table('sales')
            ->join('parties', 'sales.customer_id', '=', 'parties.id')
            ->join('emi_sales', 'emi_sales.sale_id', '=', 'sales.id')
            ->select(
                'parties.id',
                'parties.name',
                'parties.contact',
                'parties.alternate_contact',
                'emi_sales.sale_id',
                'sales.total_amount',
                'sales.date',
                'sales.sale_no',
                'sales.grand_total',
                'sales.discount',
                'sales.no_of_tenure'
            )
            ->where('sales.deleted', 'No')
            ->where('sales.emi_status', 'Yes')
            ->where('emi_sales.is_paid', '!=', 'Yes')
            ->where('emi_sales.deleted', 'Yes')
            ->distinct();

        if ($search = $request->q) {
            $query->where(function ($q) use ($search) {
                $q->where('sales.sale_no', 'like', "%{$search}%")
                    ->orWhere('parties.name', 'like', "%{$search}%");
            });
        }

        $customers = $query->orderBy($sortBy, $sortDir)->paginate($limit)->appends($request->all());

        return view('admin.inventory.voucher.view-emi-voucher', compact('type', 'customers'));
    }

    public function addEmiVoucher()
    {
        $customers = DB::table('sales')
            ->join('parties', 'sales.customer_id', '=', 'parties.id')
            ->join('emi_sales', 'emi_sales.sale_id', '=', 'sales.id')
            ->select('parties.id', 'parties.name', 'parties.contact', 'parties.alternate_contact')
            ->where('sales.deleted', 'No')
            ->where('sales.emi_status', 'Yes')
            ->where('emi_sales.is_paid', 'No')
            ->where('emi_sales.deleted', 'No')
            ->orderBy('sales.id', 'DESC')
            ->distinct()
            ->get();

        return view('admin.inventory.voucher.emi-payment-voucher', ['customers' => $customers]);
    }

    public function getEmiInvoice(Request $request)
    {

        $customerId = $request->partyId;
        $invoices = DB::table('sales')
            ->join('parties', 'sales.customer_id', '=', 'parties.id')
            ->join('emi_sales', 'emi_sales.sale_id', '=', 'sales.id')
            ->select('sales.id', 'sales.sale_no', 'sales.date')
            ->where('sales.deleted', 'No')
            ->where('sales.emi_status', 'Yes')
            ->where('emi_sales.is_paid', 'No')
            ->where('emi_sales.deleted', 'No')
            ->where('sales.customer_id', $customerId)
            ->orderBy('sales.id', 'DESC')
            ->distinct()
            ->get();

        return $invoices;
    }

    public function fetchEMI(Request $request)
    {

        $saleId = $request->saleId;
        $salesEmi = DB::table('emi_sales')
            ->where('emi_sales.sale_id', $saleId)
            ->orderBy('emi_sales.id')
            ->get();

        return $salesEmi;
    }

    // single or multiple (checkbox) EMI payment
    public function payEmiStore(Request $request)
    {
        $emiIdsArray = explode(',', $request->emiIdsArray);
        $customerId = $request->customerId;
        $emiPayment = 0;
        $result = 0;
        $PaymentVoucher = new PaymentVoucher;
        if (is_array($emiIdsArray)) {
            foreach ($emiIdsArray as $emiId) {
                $saleEmipayment = Emi_sale::find($emiId);
                $emiPayment = $saleEmipayment->per_tenur_amount;
                $saleEmipayment->paid_date = Carbon::now();
                $saleEmipayment->is_paid = 'Yes';
                $result = $saleEmipayment->save();

                $maxCode = PaymentVoucher::max('voucherNo');
                $maxCode++;
                $maxCode = str_pad($maxCode, 6, '0', STR_PAD_LEFT);
                $PaymentVoucher->party_id = $customerId;
                $PaymentVoucher->voucherNo = $maxCode;
                $PaymentVoucher->amount = $emiPayment;
                $PaymentVoucher->entryBy = auth()->user()->id;
                $PaymentVoucher->payment_method = 'Cash';
                $PaymentVoucher->paymentDate = Carbon::now();
                $PaymentVoucher->type = 'Payment Received';
                $PaymentVoucher->remarks = 'Voucher Entry for EMI ';
                $PaymentVoucher->save();
                //
                $party = Party::find($customerId);
                $party->decrement('current_due', $emiPayment);
            }
        } else {
            $saleEmipayment = Emi_sale::find($emiIdsArray);
            $emiPayment = $saleEmipayment->per_tenur_amount;

            $saleEmipayment->paid_date = Carbon::now();
            $saleEmipayment->is_paid = 'Yes';
            $result = $saleEmipayment->save();

            $maxCode = PaymentVoucher::max('voucherNo');
            $maxCode++;
            $maxCode = str_pad($maxCode, 6, '0', STR_PAD_LEFT);
            $PaymentVoucher->party_id = $customerId;
            $PaymentVoucher->voucherNo = $maxCode;
            $PaymentVoucher->amount = $emiPayment;
            $PaymentVoucher->entryBy = auth()->user()->id;
            $PaymentVoucher->payment_method = 'Cash';
            $PaymentVoucher->paymentDate = Carbon::now();
            $PaymentVoucher->type = 'Payment Received';
            $PaymentVoucher->remarks = 'Voucher Entry for EMI ';
            $PaymentVoucher->save();
            //
            $party = Party::find($customerId);
            $party->decrement('current_due', $emiPayment);
        }

        if ($result) {
            return response()->json(['success' => $emiPayment]);
        } else {
            return response()->json(['error' => 'EMI Payment Not Saved ']);
        }
    }

    // EMI payment with Adjusment, Due
    public function emiPaymentStore(Request $request)
    {
        $emidIdsArray = explode(',', $request->emidIds);
        $saleId = $request->saleId;
        $customerId = $request->customerId;
        $paymentType = $request->paymentType;
        $emiDuesAmount = $request->emiDuesAmount;
        $totalAmount = $request->totalAmount - $emiDuesAmount;
        $emiPayment = $request->emiPayment;
        $paymentDate = $request->paymentDate;

        foreach ($emidIdsArray as $emidId) {
            $saleEmipayment = Emi_sale::find($emidId);
            $saleEmipayment->paid_date = $paymentDate;
            $saleEmipayment->deleted = 'Yes';
            $saleEmipayment->deleted_by = auth()->user()->id;
            $saleEmipayment->deleted_date = Carbon::now();
            $result = $saleEmipayment->save();
        }
        /*

          */
        //
        $saleEmipayment = new Emi_sale;
        $saleEmipayment->paid_date = $paymentDate;
        $saleEmipayment->sale_id = $saleId;
        $saleEmipayment->per_tenur_amount = $emiPayment;
        $saleEmipayment->tenure_payment_date = $paymentDate;
        $saleEmipayment->adjust_amount = $emiPayment;
        $saleEmipayment->is_paid = 'Yes';
        $saleEmipayment->created_by = auth()->user()->id;
        $saleEmipayment->save();
        //
        // $maxCode = PaymentVoucher::max('voucherNo');
        // $maxCode++;
        // $maxCode=str_pad($maxCode, 6, '0', STR_PAD_LEFT);;
        // $PaymentVoucher = new PaymentVoucher();
        // $PaymentVoucher->party_id = $customerId;
        // $PaymentVoucher->voucherNo =$maxCode;
        // $PaymentVoucher->amount = 	$emiPayment;
        // $PaymentVoucher->entryBy = auth()->user()->id;
        // $PaymentVoucher->payment_method = "Cash";
        // $PaymentVoucher->paymentDate = $paymentDate;
        // $PaymentVoucher->type = "Payment Received";
        // $PaymentVoucher->remarks = "Voucher Entry for EMI ";
        // $PaymentVoucher->save();
        // //
        // $party = Party::find($customerId);
        // $party->decrement('current_due',$emiPayment);

        if ($paymentType == 'Adjustment') {
            $saleEmipayment = new Emi_sale;
            $saleEmipayment->paid_date = $paymentDate;
            $saleEmipayment->sale_id = $saleId;
            $saleEmipayment->per_tenur_amount = $emiDuesAmount;
            $saleEmipayment->tenure_payment_date = $paymentDate;
            $saleEmipayment->adjust_amount = $emiDuesAmount;
            $saleEmipayment->is_paid = 'Adjusted';
            $saleEmipayment->created_by = auth()->user()->id;
            $saleEmipayment->save();

            $maxCode = PaymentVoucher::max('voucherNo');
            $maxCode++;
            $maxCode = str_pad($maxCode, 6, '0', STR_PAD_LEFT);
            $PaymentVoucher = new PaymentVoucher;
            $PaymentVoucher->party_id = $customerId;
            $PaymentVoucher->voucherNo = $maxCode;
            $PaymentVoucher->amount = $emiDuesAmount;
            $PaymentVoucher->entryBy = auth()->user()->id;
            $PaymentVoucher->payment_method = 'Cash';
            $PaymentVoucher->paymentDate = $paymentDate;
            $PaymentVoucher->type = 'Discount';
            $PaymentVoucher->remarks = 'Voucher Entry for EMI auto Discount';
            $PaymentVoucher->save();
            //
            $party = Party::find($customerId);
            $party->decrement('current_due', $emiDuesAmount);
        } elseif ($paymentType == 'Due') {
            $saleEmipayment = new Emi_sale;
            $saleEmipayment->sale_id = $saleId;
            $saleEmipayment->per_tenur_amount = $emiDuesAmount;
            $saleEmipayment->tenure_payment_date = $paymentDate;
            $saleEmipayment->created_by = auth()->user()->id;
            $saleEmipayment->save();
        }/*elseif($paymentType == "Discount"){

            $maxCode = PaymentVoucher::max('voucherNo');
                $maxCode++;
                $maxCode=str_pad($maxCode, 6, '0', STR_PAD_LEFT);;
                $PaymentVoucher = new PaymentVoucher();
                $PaymentVoucher->party_id = $customerId;
                $PaymentVoucher->voucherNo =$maxCode;
                $PaymentVoucher->amount = 	$emiDuesAmount;
                $PaymentVoucher->entryBy = auth()->user()->id;
                $PaymentVoucher->payment_method = "Cash";
                $PaymentVoucher->paymentDate = $paymentDate;
                $PaymentVoucher->type = "Discount";
                $PaymentVoucher->remarks = "Voucher Entry for ".$paymentType;
                $PaymentVoucher->save();

                $party = Party::find($customerId);
                $party->decrement('current_due',$emiDuesAmount);

        }*/

        $customer = Party::find($customerId);
        $customer->current_due = $customer->current_due - $totalAmount;
        $result = $customer->save();
        if ($result) {
            return response()->json(['success' => 'EMI Paid  successfully.']);
        } else {
            return response()->json(['error' => 'EMI payment Not Saved ']);
        }
    }

    public function createPDF($id)
    {

        $paymentVouchers = DB::table('payment_vouchers')
            ->join('parties', 'payment_vouchers.party_id', '=', 'parties.id')
            ->leftjoin('users', 'payment_vouchers.entryBy', '=', 'users.id')
            ->select(
                'payment_vouchers.id',
                'payment_vouchers.purchase_id',
                'payment_vouchers.sales_id',
                'payment_vouchers.type',
                'payment_vouchers.amount',
                'payment_vouchers.payment_method',
                'payment_vouchers.paymentDate',
                'payment_vouchers.voucherNo',
                'users.name as entryBy',
                'payment_vouchers.remarks',
                'parties.name',
                'parties.code',
                'parties.contact',
                'parties.alternate_contact',
                'parties.address',
                'payment_vouchers.remarks'

            )
            ->orderby('payment_vouchers.id', 'DESC')
            ->where('payment_vouchers.id', '=', $id)
            ->get();

        // dd($paymentVouchers);
        /* $purchaseId  = $id;
                    $purchases = DB::table('purchases')
                    ->where('id', $purchaseId)
                    ->get();

                    $userId  = auth()->user()->id;
                    $userName = User::where('id', $userId)->pluck('name')->first();

        session(['userName' => $userName]); */

        // return view('admin.inventory.voucher.paymentReceivedVoucher', ['paymentVouchers'=> $paymentVouchers]);

        $pdf = PDF::loadView('admin.inventory.voucher.paymentReceivedVoucher', ['paymentVouchers' => $paymentVouchers]);

        return $pdf->stream('payment-received-pdf.pdf', ['Attachment' => false]);
    }

    public function getSupplierDue(Request $request)
    {
        $due = Party::find($request->partyId);

        return $due;
    }
}
