<?php

namespace App\Http\Controllers\Admin\Inventory;

use App\Http\Controllers\Controller;
use App\Models\inventory\Currentstock;
use App\Models\inventory\GoodsReceipt;
use App\Models\inventory\GoodsReceiptProduct;
use App\Models\inventory\Product;
use App\Models\inventory\PurchaseOrder;
use App\Models\inventory\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class GoodsReceiptController extends Controller
{
    public function index(Request $request)
    {
        $limit = $request->limit ?? 10;
        $receipts = GoodsReceipt::with(['purchaseOrder', 'supplier', 'creator'])
            ->orderBy('id', 'DESC')
            ->paginate($limit);

        return view('admin.inventory.goods_receipt.index', compact('receipts'));
    }

    public function create()
    {
        $pendingPOs = \App\Models\inventory\PurchaseOrder::with('supplier')
            ->whereIn('status', ['Approved', 'Partial'])
            ->orderBy('id', 'DESC')
            ->get();

        return view('admin.inventory.goods_receipt.create', compact('pendingPOs'));
    }

    public function loadPoProducts($poId)
    {
        $po = \App\Models\inventory\PurchaseOrder::with(['products.product', 'supplier'])->findOrFail($poId);

        $totalReceived = \App\Models\inventory\GoodsReceiptProduct::whereHas('goodsReceipt', function ($q) use ($poId) {
            $q->where('purchase_order_id', $poId)->where('status', 'Received');
        })->get()->groupBy('purchase_order_product_id');

        return response()->json([
            'po' => $po,
            'received' => $totalReceived,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'purchase_order_id' => 'required|exists:purchase_orders,id',
            'date' => 'required|date',
            'products' => 'required|array',
            'products.*.purchase_order_product_id' => 'required',
            'products.*.product_id' => 'required',
            'products.*.ordered_quantity' => 'required|numeric|min:0',
            'products.*.received_quantity' => 'required|numeric|min:0',
            'products.*.unit_price' => 'required|numeric|min:0',
        ]);

        $po = \App\Models\inventory\PurchaseOrder::with('products')->findOrFail($request->purchase_order_id);

        DB::beginTransaction();
        try {
            $grnNumber = 'GRN-' . date('Ymd') . '-' . rand(1000, 9999);

            $grn = GoodsReceipt::create([
                'grn_number' => $grnNumber,
                'purchase_order_id' => $po->id,
                'supplier_id' => $po->supplier_id,
                'date' => $request->date,
                'notes' => $request->notes,
                'status' => 'Received',
                'created_by' => Auth::id(),
            ]);

            $warehouse = Warehouse::where('deleted', 'No')->first();
            if (!$warehouse) {
                return back()->with('error', 'No warehouse found. Please create a warehouse first.');
            }

            $allFullyReceived = true;

            foreach ($request->products as $item) {
                $receivedQty = floatval($item['received_quantity'] ?? 0);
                if ($receivedQty <= 0) continue;

                $poProduct = \App\Models\inventory\PurchaseOrderProduct::find($item['purchase_order_product_id']);
                if (!$poProduct) continue;

                GoodsReceiptProduct::create([
                    'goods_receipt_id' => $grn->id,
                    'purchase_order_product_id' => $poProduct->id,
                    'product_id' => $poProduct->product_id,
                    'ordered_quantity' => $poProduct->quantity,
                    'received_quantity' => $receivedQty,
                    'unit_price' => $poProduct->unit_price,
                ]);

                $product = Product::find($poProduct->product_id);
                if ($product) {
                    $product->increment('current_stock', $receivedQty);
                    $product->increment('purchase_quantity', $receivedQty);
                }

                $currentStock = Currentstock::where('tbl_productsId', $poProduct->product_id)
                    ->where('tbl_wareHouseId', $warehouse->id)
                    ->where('deleted', 'No');
                if ($currentStock->first()) {
                    $currentStock->increment('currentStock', $receivedQty);
                    $currentStock->increment('purchaseStock', $receivedQty);
                } else {
                    $cs = new Currentstock;
                    $cs->tbl_productsId = $poProduct->product_id;
                    $cs->tbl_wareHouseId = $warehouse->id;
                    $cs->currentStock = $receivedQty;
                    $cs->purchaseStock = $receivedQty;
                    $cs->entryBy = auth()->user()->id;
                    $cs->entryDate = now();
                    $cs->save();
                }
            }

            $po->update(['status' => 'Received']);

            DB::commit();

            return redirect()->route('goods_receipts.index')->with('success', "GRN {$grnNumber} created successfully.");
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to create GRN: ' . $e->getMessage())->withInput();
        }
    }

    public function show($id)
    {
        $grn = GoodsReceipt::with(['products.product', 'purchaseOrder', 'supplier', 'creator'])->findOrFail($id);
        return view('admin.inventory.goods_receipt.show', compact('grn'));
    }
}
