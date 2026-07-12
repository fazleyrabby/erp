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
}
