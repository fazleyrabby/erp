<?php

namespace App\Http\Controllers\Admin\Inventory;

use App\Http\Controllers\Controller;
use App\Models\inventory\Quotation;
use App\Models\inventory\QuotationProduct;
use App\Models\inventory\Party;
use App\Models\inventory\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class QuotationController extends Controller
{
    public function index(Request $request)
    {
        $limit = $request->limit ?? 10;
        $quotations = Quotation::with('customer')
            ->where('deleted', 'No')
            ->orderBy('id', 'DESC')
            ->paginate($limit);

        return view('admin.inventory.quotation.index', compact('quotations'));
    }

    public function add()
    {
        $customers = Party::where('deleted', 'No')->where('status', 'Active')->whereIn('party_type', ['Customer', 'Both'])->get();
        $products = Product::where('deleted', 'No')->where('status', 'Active')->where('type', '!=', 'service')->get();
        
        return view('admin.inventory.quotation.add', compact('customers', 'products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_id' => 'required|exists:parties,id',
            'date' => 'required|date',
            'products' => 'required|array',
            'products.*.product_id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|numeric|min:1',
            'products.*.unit_price' => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();
        try {
            $quotation_no = 'QT-' . date('Ymd') . '-' . rand(1000, 9999);
            
            $quotation = Quotation::create([
                'quotation_no' => $quotation_no,
                'date' => $request->date,
                'customer_id' => $request->customer_id,
                'description' => $request->description,
                'discount' => $request->discount ?? 0,
                'carrying_cost' => $request->carrying_cost ?? 0,
                'vat' => $request->vat ?? 0,
                'ait' => $request->ait ?? 0,
                'status' => 'Pending',
                'created_by' => Auth::id(),
            ]);

            $total_amount = 0;
            foreach ($request->products as $item) {
                $total_price = $item['quantity'] * $item['unit_price'];
                $total_amount += $total_price;
                
                QuotationProduct::create([
                    'quotation_id' => $quotation->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'total_price' => $total_price,
                ]);
            }

            $grand_total = ($total_amount - $quotation->discount) + $quotation->carrying_cost + $quotation->vat + $quotation->ait;
            $quotation->update([
                'total_amount' => $total_amount,
                'grand_total' => $grand_total,
            ]);

            DB::commit();
            return redirect()->route('quotations.index')->with('success', 'Quotation created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }

    public function createPDF($id)
    {
        $quotation = Quotation::with(['customer', 'products.product', 'creator'])->findOrFail($id);
        
        $pdf = \PDF::loadView('admin.inventory.quotation.invoice', compact('quotation'));
        return $pdf->stream('quotation-'.$quotation->quotation_no.'.pdf');
    }

    public function convertToSaleOrder($id)
    {
        $quotation = Quotation::with('products')->findOrFail($id);

        if ($quotation->status === 'Converted') {
            return back()->with('error', 'Quotation is already converted.');
        }

        DB::beginTransaction();
        try {
            $saleOrderNo = 'SO-' . date('Ymd') . '-' . rand(1000, 9999);
            
            $saleOrder = \App\Models\inventory\SaleOrder::create([
                'sale_order_no' => $saleOrderNo,
                'customer_id' => $quotation->customer_id,
                'date' => date('Y-m-d'),
                'total_amount' => $quotation->total_amount,
                'discount' => $quotation->discount,
                'carrying_cost' => $quotation->carrying_cost,
                'vat' => $quotation->vat,
                'ait' => $quotation->ait,
                'grand_total' => $quotation->grand_total,
                'status' => 'Pending',
                'created_by' => Auth::id(),
            ]);

            foreach ($quotation->products as $item) {
                \App\Models\inventory\SaleOrderProduct::create([
                    'sale_order_id' => $saleOrder->id,
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                    'unit_price' => $item->unit_price,
                    'total_price' => $item->total_price,
                ]);
            }

            $quotation->update(['status' => 'Converted']);

            DB::commit();
            return redirect()->route('SaleOrders')->with('success', 'Quotation converted to Sale Order successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }
}
