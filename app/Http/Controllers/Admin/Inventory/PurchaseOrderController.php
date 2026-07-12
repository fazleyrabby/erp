<?php

namespace App\Http\Controllers\Admin\Inventory;

use App\Http\Controllers\Controller;
use App\Models\inventory\PurchaseOrder;
use App\Models\inventory\PurchaseOrderProduct;
use App\Models\inventory\Party;
use App\Models\inventory\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class PurchaseOrderController extends Controller
{
    public function __construct()
    {
        // $this->middleware('permission:purchase_order.view', ['only' => ['index']]);
        // $this->middleware('permission:purchase_order.add', ['only' => ['add', 'store']]);
        // $this->middleware('permission:purchase_order.approve', ['only' => ['approve']]);
    }

    public function index(Request $request)
    {
        $limit = $request->limit ?? 10;
        $purchaseOrders = PurchaseOrder::with('supplier', 'creator', 'approver')
            ->orderBy('id', 'DESC')
            ->paginate($limit);

        return view('admin.inventory.purchase_order.index', compact('purchaseOrders'));
    }

    public function add()
    {
        $suppliers = Party::where('deleted', 'No')->where('status', 'Active')->whereIn('party_type', ['Supplier', 'Both'])->get();
        $products = Product::where('deleted', 'No')->where('status', 'Active')->where('type', '!=', 'service')->get();
        
        return view('admin.inventory.purchase_order.add', compact('suppliers', 'products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'supplier_id' => 'required|exists:parties,id',
            'date' => 'required|date',
            'products' => 'required|array',
            'products.*.product_id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|numeric|min:1',
            'products.*.unit_price' => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();
        try {
            $po_number = 'PO-' . date('Ymd') . '-' . rand(1000, 9999);
            
            $purchaseOrder = PurchaseOrder::create([
                'po_number' => $po_number,
                'date' => $request->date,
                'supplier_id' => $request->supplier_id,
                'notes' => $request->notes,
                'status' => 'Pending',
                'created_by' => Auth::id(),
            ]);

            $total_amount = 0;
            foreach ($request->products as $item) {
                $total_price = $item['quantity'] * $item['unit_price'];
                $total_amount += $total_price;
                
                PurchaseOrderProduct::create([
                    'purchase_order_id' => $purchaseOrder->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'total_price' => $total_price,
                ]);
            }

            $purchaseOrder->update([
                'total_amount' => $total_amount,
            ]);

            DB::commit();
            return redirect()->route('purchase_orders.index')->with('success', 'Purchase Order created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }

    public function approve($id)
    {
        $purchaseOrder = PurchaseOrder::findOrFail($id);
        
        if ($purchaseOrder->status !== 'Pending') {
            return back()->with('error', 'Only pending Purchase Orders can be approved.');
        }

        $purchaseOrder->update([
            'status' => 'Approved',
            'approved_by' => Auth::id(),
        ]);

        return back()->with('success', 'Purchase Order approved successfully.');
    }

    public function view($id)
    {
        $po = PurchaseOrder::with(['products.product', 'supplier', 'creator', 'approver'])->findOrFail($id);
        return view('admin.inventory.purchase_order.view', compact('po'));
    }

    public function convertToPurchase($id)
    {
        $po = PurchaseOrder::with('products')->findOrFail($id);
        
        if ($po->status !== 'Approved') {
            return back()->with('error', 'Only approved Purchase Orders can be converted.');
        }

        $warehouse = \App\Models\inventory\Warehouse::where('deleted', 'No')->first();
        if (!$warehouse) {
            return back()->with('error', 'No warehouse found in the system. Please create a warehouse first.');
        }
        
        \Illuminate\Support\Facades\Session::forget('purchase_cart_array');
        
        $cartArray = [];
        foreach ($po->products as $item) {
            $productInfo = \App\Models\inventory\Product::find($item->product_id);
            if (!$productInfo) continue;
            
            $cartArray[] = [
                'product_id' => $productInfo->id,
                'product_name' => $productInfo->name.' - '.$productInfo->code,
                'product_image' => $productInfo->image,
                'available_qty' => 0,
                'product_price' => $item->unit_price,
                'product_quantity' => $item->quantity,
                'product_discount' => 0,
                'barcode_no' => $productInfo->barcode_no,
                'warehouse_id' => $warehouse->id,
                'warehouse_name' => $warehouse->wareHouseName,
                'product_type' => $productInfo->type,
                'items_in_box' => $productInfo->items_in_box ?? 1,
                'serialNumbers' => [],
                'stockQuantities' => [],
            ];
        }
        
        \Illuminate\Support\Facades\Session::put('purchase_cart_array', $cartArray);
        
        $po->update(['status' => 'Converted']);
        
        return redirect()->route('purchase.add')->with('success', 'Purchase Order items loaded into cart. Please review and Save Purchase.');
    }
}
