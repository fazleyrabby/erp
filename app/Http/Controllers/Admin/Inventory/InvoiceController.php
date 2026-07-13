<?php

namespace App\Http\Controllers\Admin\Inventory;

use App\Http\Controllers\Controller;
use App\Models\inventory\Invoice;
use App\Models\inventory\InvoiceItem;
use App\Models\inventory\InvoicePayment;
use App\Models\inventory\Party;
use App\Models\inventory\Product;
use App\Models\inventory\Sale;
use App\Models\inventory\Purchase;
use App\Models\Accounts\ChartOfAccounts;
use App\Models\Accounts\Voucher;
use App\Models\Accounts\VoucherDetails;
use App\Models\inventory\PaymentVoucher as InvPaymentVoucher;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use PDF;

class InvoiceController extends Controller
{
    public function index(Request $request)
    {
        $type = $request->type ?? 'Sales';
        $limit = $request->limit ?? 10;

        $query = Invoice::with('party', 'creator')
            ->where('deleted', 'No')
            ->where('invoice_type', $type);

        if ($search = $request->q) {
            $query->where(function ($q) use ($search) {
                $q->where('invoice_no', 'like', "%{$search}%")
                  ->orWhereHas('party', function ($p) use ($search) {
                      $p->where('name', 'like', "%{$search}%");
                  });
            });
        }

        if ($status = $request->status) {
            $query->where('status', $status);
        }

        $sortBy = $request->sort_by ?? 'id';
        $sortDir = $request->sort_direction ?? 'DESC';
        $invoices = $query->orderBy($sortBy, $sortDir)->paginate($limit)->appends($request->all());

        return view('admin.inventory.invoice.index', compact('invoices', 'type'));
    }

    public function add()
    {
        $parties = Party::where('deleted', 'No')->where('status', 'Active')
            ->whereIn('party_type', ['Customer', 'Both'])
            ->get();
        $products = Product::where('deleted', 'No')->where('status', 'Active')
            ->where('type', '!=', 'service')
            ->get();
        $sales = Sale::where('deleted', 'No')->orderBy('id', 'DESC')->get();
        $purchases = Purchase::where('deleted', 'No')->orderBy('id', 'DESC')->get();

        return view('admin.inventory.invoice.add', compact('parties', 'products', 'sales', 'purchases'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'party_id' => 'required|exists:parties,id',
            'invoice_type' => 'required|in:Sales,Purchase',
            'date' => 'required|date',
            'items' => 'required|array',
            'items.*.product_id' => 'nullable|exists:products,id',
            'items.*.quantity' => 'required|numeric|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();
        try {
            $prefix = $request->invoice_type === 'Sales' ? 'INV' : 'BILL';
            $last = Invoice::where('invoice_no', 'like', $prefix . '-%')->max('invoice_no');
            $num = $last ? (int) substr($last, strlen($prefix) + 1) + 1 : 1;
            $invoiceNo = $prefix . '-' . str_pad($num, 6, '0', STR_PAD_LEFT);

            $invoice = Invoice::create([
                'invoice_no' => $invoiceNo,
                'party_id' => $request->party_id,
                'invoice_type' => $request->invoice_type,
                'reference_type' => $request->reference_type,
                'reference_id' => $request->reference_id,
                'date' => $request->date,
                'due_date' => $request->due_date,
                'description' => $request->description,
                'discount' => $request->discount ?? 0,
                'carrying_cost' => $request->carrying_cost ?? 0,
                'vat' => $request->vat ?? 0,
                'ait' => $request->ait ?? 0,
                'notes' => $request->notes,
                'terms_conditions' => $request->terms_conditions,
                'status' => 'Draft',
                'created_by' => Auth::id(),
            ]);

            $totalAmount = 0;
            foreach ($request->items as $item) {
                $totalPrice = $item['quantity'] * $item['unit_price'];
                $totalAmount += $totalPrice;

                InvoiceItem::create([
                    'invoice_id' => $invoice->id,
                    'product_id' => $item['product_id'] ?? null,
                    'description' => $item['description'] ?? null,
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'total_price' => $totalPrice,
                ]);
            }

            $grandTotal = ($totalAmount - $invoice->discount) + $invoice->carrying_cost + $invoice->vat + $invoice->ait;
            $invoice->update([
                'total_amount' => $totalAmount,
                'grand_total' => $grandTotal,
            ]);

            DB::commit();
            return redirect()->route('invoices.show', $invoice->id)->with('success', 'Invoice created successfully.');
        } catch (Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage())->withInput();
        }
    }

    public function show($id)
    {
        $invoice = Invoice::with(['party', 'items.product', 'payments', 'creator'])->findOrFail($id);
        return view('admin.inventory.invoice.show', compact('invoice'));
    }

    public function edit($id)
    {
        $invoice = Invoice::with('items')->findOrFail($id);
        if ($invoice->status === 'Paid' || $invoice->status === 'Cancelled') {
            return back()->with('error', 'Cannot edit a ' . $invoice->status . ' invoice.');
        }
        $parties = Party::where('deleted', 'No')->where('status', 'Active')
            ->whereIn('party_type', ['Customer', 'Both', 'Supplier'])
            ->get();
        $products = Product::where('deleted', 'No')->where('status', 'Active')
            ->where('type', '!=', 'service')
            ->get();
        return view('admin.inventory.invoice.edit', compact('invoice', 'parties', 'products'));
    }

    public function update(Request $request, $id)
    {
        $invoice = Invoice::findOrFail($id);
        if ($invoice->status === 'Paid' || $invoice->status === 'Cancelled') {
            return back()->with('error', 'Cannot update a ' . $invoice->status . ' invoice.');
        }

        $request->validate([
            'party_id' => 'required|exists:parties,id',
            'date' => 'required|date',
            'items' => 'required|array',
            'items.*.product_id' => 'nullable|exists:products,id',
            'items.*.quantity' => 'required|numeric|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();
        try {
            $invoice->update([
                'party_id' => $request->party_id,
                'date' => $request->date,
                'due_date' => $request->due_date,
                'description' => $request->description,
                'discount' => $request->discount ?? 0,
                'carrying_cost' => $request->carrying_cost ?? 0,
                'vat' => $request->vat ?? 0,
                'ait' => $request->ait ?? 0,
                'notes' => $request->notes,
                'terms_conditions' => $request->terms_conditions,
                'updated_by' => Auth::id(),
            ]);

            $invoice->items()->delete();
            $totalAmount = 0;
            foreach ($request->items as $item) {
                $totalPrice = $item['quantity'] * $item['unit_price'];
                $totalAmount += $totalPrice;

                InvoiceItem::create([
                    'invoice_id' => $invoice->id,
                    'product_id' => $item['product_id'] ?? null,
                    'description' => $item['description'] ?? null,
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'total_price' => $totalPrice,
                ]);
            }

            $grandTotal = ($totalAmount - $invoice->discount) + $invoice->carrying_cost + $invoice->vat + $invoice->ait;
            $invoice->update([
                'total_amount' => $totalAmount,
                'grand_total' => $grandTotal,
            ]);

            DB::commit();
            return redirect()->route('invoices.show', $invoice->id)->with('success', 'Invoice updated successfully.');
        } catch (Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage())->withInput();
        }
    }

    public function delete(Request $request)
    {
        $invoice = Invoice::findOrFail($request->id);
        $invoice->update([
            'deleted' => 'Yes',
            'deleted_by' => Auth::id(),
            'deleted_date' => now(),
        ]);
        return redirect()->route('invoices.index', ['type' => $invoice->invoice_type])
            ->with('success', 'Invoice deleted successfully.');
    }

    public function updateStatus(Request $request, $id)
    {
        $invoice = Invoice::findOrFail($id);
        $status = $request->status;

        $allowed = ['Draft', 'Sent', 'Paid', 'Cancelled'];
        if (!in_array($status, $allowed)) {
            return back()->with('error', 'Invalid status.');
        }

        $invoice->update(['status' => $status, 'updated_by' => Auth::id()]);

        return back()->with('success', 'Invoice status updated to ' . $status . '.');
    }

    public function addPayment(Request $request, $id)
    {
        $invoice = Invoice::findOrFail($id);
        if ($invoice->status === 'Paid' || $invoice->status === 'Cancelled') {
            return back()->with('error', 'Cannot add payment to a ' . $invoice->status . ' invoice.');
        }

        $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'payment_date' => 'required|date',
            'payment_method' => 'required|string',
        ]);

        DB::beginTransaction();
        try {
            $payment = InvoicePayment::create([
                'invoice_id' => $invoice->id,
                'amount' => $request->amount,
                'payment_date' => $request->payment_date,
                'payment_method' => $request->payment_method,
                'reference' => $request->reference,
                'notes' => $request->notes,
            ]);

            $newPaid = $invoice->paid_amount + $request->amount;
            $newStatus = $newPaid >= $invoice->grand_total ? 'Paid' : 'Partial';
            $invoice->update([
                'paid_amount' => $newPaid,
                'status' => $newStatus,
                'updated_by' => Auth::id(),
            ]);

            if ($invoice->invoice_type === 'Sales') {
                $this->createAccountsEntry($invoice, $payment);
            }

            DB::commit();
            return back()->with('success', 'Payment recorded successfully.');
        } catch (Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }

    public function createPDF($id)
    {
        $invoice = Invoice::with(['party', 'items.product', 'payments', 'creator'])->findOrFail($id);
        $pdf = PDF::loadView('admin.inventory.invoice.pdf', compact('invoice'));
        return $pdf->stream('invoice-' . $invoice->invoice_no . '.pdf');
    }

    public function convertFromSale($id)
    {
        $sale = Sale::with('saleProducts.product')->findOrFail($id);

        $existing = Invoice::where('reference_type', 'Sale')
            ->where('reference_id', $id)
            ->where('deleted', 'No')
            ->first();
        if ($existing) {
            return redirect()->route('invoices.show', $existing->id)
                ->with('info', 'Invoice already exists for this sale.');
        }

        DB::beginTransaction();
        try {
            $last = Invoice::where('invoice_no', 'like', 'INV-%')->max('invoice_no');
            $num = $last ? (int) substr($last, 4) + 1 : 1;
            $invoiceNo = 'INV-' . str_pad($num, 6, '0', STR_PAD_LEFT);

            $invoice = Invoice::create([
                'invoice_no' => $invoiceNo,
                'party_id' => $sale->customer_id,
                'invoice_type' => 'Sales',
                'reference_type' => 'Sale',
                'reference_id' => $sale->id,
                'date' => $sale->date ?? date('Y-m-d'),
                'due_date' => $sale->date ? date('Y-m-d', strtotime($sale->date . ' +15 days')) : null,
                'total_amount' => $sale->total_amount ?? 0,
                'discount' => $sale->discount ?? 0,
                'carrying_cost' => $sale->carrying_cost ?? 0,
                'vat' => $sale->vat ?? 0,
                'ait' => $sale->ait ?? 0,
                'grand_total' => $sale->grand_total ?? 0,
                'paid_amount' => $sale->current_payment ?? 0,
                'status' => ($sale->current_payment ?? 0) > 0 ? 'Partial' : 'Draft',
                'created_by' => Auth::id(),
            ]);

            if ($sale->relationLoaded('saleProducts') && $sale->saleProducts) {
                foreach ($sale->saleProducts as $sp) {
                    InvoiceItem::create([
                        'invoice_id' => $invoice->id,
                        'product_id' => $sp->product_id,
                        'description' => null,
                        'quantity' => $sp->quantity ?? 1,
                        'unit_price' => $sp->unit_price ?? 0,
                        'total_price' => ($sp->quantity ?? 0) * ($sp->unit_price ?? 0),
                    ]);
                }
            }

            DB::commit();
            return redirect()->route('invoices.show', $invoice->id)
                ->with('success', 'Invoice created from Sale #' . $sale->id . '.');
        } catch (Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }

    public function convertFromPurchase($id)
    {
        $purchase = Purchase::findOrFail($id);

        $existing = Invoice::where('reference_type', 'Purchase')
            ->where('reference_id', $id)
            ->where('deleted', 'No')
            ->first();
        if ($existing) {
            return redirect()->route('invoices.show', $existing->id)
                ->with('info', 'Bill already exists for this purchase.');
        }

        DB::beginTransaction();
        try {
            $last = Invoice::where('invoice_no', 'like', 'BILL-%')->max('invoice_no');
            $num = $last ? (int) substr($last, 5) + 1 : 1;
            $invoiceNo = 'BILL-' . str_pad($num, 6, '0', STR_PAD_LEFT);

            $invoice = Invoice::create([
                'invoice_no' => $invoiceNo,
                'party_id' => $purchase->supplier_id,
                'invoice_type' => 'Purchase',
                'reference_type' => 'Purchase',
                'reference_id' => $purchase->id,
                'date' => $purchase->date ?? date('Y-m-d'),
                'due_date' => $purchase->date ? date('Y-m-d', strtotime($purchase->date . ' +15 days')) : null,
                'total_amount' => $purchase->total_amount ?? 0,
                'discount' => $purchase->discount ?? 0,
                'carrying_cost' => $purchase->carrying_cost ?? 0,
                'vat' => 0,
                'ait' => 0,
                'grand_total' => $purchase->grand_total ?? 0,
                'paid_amount' => $purchase->current_payment ?? 0,
                'status' => ($purchase->current_payment ?? 0) > 0 ? 'Partial' : 'Draft',
                'created_by' => Auth::id(),
            ]);

            $purchaseProducts = \App\Models\inventory\PurchaseProduct::where('purchase_id', $purchase->id)->get();
            foreach ($purchaseProducts as $pp) {
                InvoiceItem::create([
                    'invoice_id' => $invoice->id,
                    'product_id' => $pp->product_id,
                    'description' => null,
                    'quantity' => $pp->quantity ?? 1,
                    'unit_price' => $pp->unit_price ?? 0,
                    'total_price' => ($pp->quantity ?? 0) * ($pp->unit_price ?? 0),
                ]);
            }

            DB::commit();
            return redirect()->route('invoices.show', $invoice->id)
                ->with('success', 'Bill created from Purchase #' . $purchase->id . '.');
        } catch (Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }

    private function createAccountsEntry($invoice, $payment)
    {
        $voucherNo = 'INV-PMT-' . $payment->id;
        $voucher = Voucher::create([
            'vendor_id' => $invoice->party_id,
            'voucher_no' => $voucherNo,
            'transaction_date' => $payment->payment_date,
            'amount' => $payment->amount,
            'payment_method' => $payment->payment_method,
            'type' => 'Payment Received',
            'voucher_title' => 'Payment for Invoice ' . $invoice->invoice_no,
            'created_by' => Auth::id(),
            'created_date' => now(),
        ]);

        $cashCoa = ChartOfAccounts::where('slug', 'cash')->first();
        if ($cashCoa) {
            VoucherDetails::create([
                'tbl_acc_voucher_id' => $voucher->id,
                'tbl_acc_coa_id' => $cashCoa->id,
                'debit' => $payment->amount,
                'voucher_title' => 'Payment received for ' . $invoice->invoice_no,
                'created_by' => Auth::id(),
                'created_date' => now(),
            ]);
        }

        VoucherDetails::create([
            'tbl_acc_voucher_id' => $voucher->id,
            'tbl_acc_coa_id' => $invoice->party_id,
            'credit' => $payment->amount,
            'voucher_title' => 'Payment received for ' . $invoice->invoice_no,
            'created_by' => Auth::id(),
            'created_date' => now(),
        ]);
    }
}
