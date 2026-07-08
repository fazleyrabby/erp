<?php

namespace App\Http\Controllers\Admin\Inventory;

use App\Http\Controllers\Controller;
use App\Models\Accounts\ChartOfAccounts;
use App\Models\inventory\Category;
use App\Models\inventory\Currentstock;
use App\Models\inventory\Party;
use App\Models\inventory\PaymentVoucher;
use App\Models\inventory\Product;
use App\Models\inventory\Purchase;
use App\Models\inventory\Purchase_Product_Return;
use App\Models\inventory\Purchase_Return;
use App\Models\inventory\PurchaseProduct;
use App\Models\inventory\Warehouse;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use PDF;

class PurchaseReturnController extends Controller
{
    public function purchaseReturn($id)
    {
        $purchase = Purchase::find($id);

        $supplierName = Party::select('name')->where('deleted', 'No')->where('status', 'Active')->where('id', $purchase->supplier_id)->first();
        $purchase['supplier_name'] = $supplierName->name;

        $purchaseProducts = DB::table('purchase_products')
            ->join('products', 'products.id', '=', 'purchase_products.product_id')
            ->join('tbl_warehouse', 'purchase_products.warehouse_id', 'tbl_warehouse.id')
            ->select('purchase_products.*', 'products.name', 'tbl_warehouse.wareHouseName')
            ->where('purchase_products.purchase_id', $id)
            ->where('purchase_products.deleted', 'No')
            ->where('purchase_products.status', 'Active')
            ->get();

        $returnedQtyArray = [];
        $i = 0;
        foreach ($purchaseProducts as $purchaseProduct) {
            $returnedQtyArray[$i] = DB::table('purchase_product_returns')
                ->where('purchase_product_id', $purchaseProduct->id)
                ->where('deleted', 'No')
                ->sum('return_qty');

            $i++;
        }
        $warehouses = Warehouse::where('deleted', 'No')->where('status', 'Active')->get();

        return view('admin.inventory.purchase.purchase-return', ['purchase' => $purchase, 'purchaseProducts' => $purchaseProducts, 'returnedQtyArray' => $returnedQtyArray, 'warehouses' => $warehouses]);
    }

    public function storePurchaseReturn(Request $request)
    {
        DB::beginTransaction();
        try {

            $request->validate([
                'warehouse' => 'required',
            ]);

            $purchaseReturnNo = Purchase_Return::where('deleted', 'No')->max('purchase_return_no');
            $purchaseReturnNo++;
            $purchaseReturnNo = str_pad($purchaseReturnNo, 6, '0', STR_PAD_LEFT);

            $purchaseReturn_id = 0;
            $purchaseId = $request->purchaseId;
            $purchaseProductIds = $request->purchaseProductIds;
            $purchaseProductIdsArray = explode(',', $purchaseProductIds);
            $itemCodesArray = explode(',', $request->itemCodes); // product id
            $QuantitiesArray = explode(',', $request->Quantities);
            $returnedQuantitiesArray = explode(',', $request->returnedQuantities);
            $returnQuantitiesArray = explode(',', $request->returnQuantities);
            $remainQuantitiesArray = explode(',', $request->remainQuantities);
            $unitPricesArray = explode(',', $request->unitPrices);
            $totalsArray = explode(',', $request->totals);
            $warehouse = $request->warehouse;
            $purchase_no = Purchase::where('id', $purchaseId)->pluck('purchase_no');
            // store  in purchase_returns //
            $purchaseReturn = new Purchase_Return;
            $purchaseReturn->purchase_return_no = $purchaseReturnNo;
            $purchaseReturn->purchase_no = $purchase_no[0];
            // $purchaseReturn->coa_id = $request->category;
            $purchaseReturn->coa_id = 43; // tbl_acc_coas [purchase-return]
            $purchaseReturn->purchase_return_date = Carbon::now();
            $purchaseReturn->purchase_date = $request->purchaseDate;
            $purchaseReturn->purchase_id = $purchaseId;
            $purchaseReturn->supplier_id = $request->supplierId;
            $purchaseReturn->grand_total = $request->grandTotal;
            $purchaseReturn->created_by = Auth::user()->id;
            $purchaseReturn->save();
            $purchaseReturnId = $purchaseReturn->id;

            for ($i = 0; $i < count($purchaseProductIdsArray); $i++) {

                if ($returnQuantitiesArray[$i] <= 0) {
                    continue;
                }

                $purchaseProduct = PurchaseProduct::find($purchaseProductIdsArray[$i]);
                // $purchaseProduct_id = $purchaseProduct->product_id;

                // store  in purchase_product_returns table //
                $purchaseReturnProduct = new Purchase_Product_Return;
                $purchaseReturnProduct->product_id = intval($itemCodesArray[$i]);
                $purchaseReturnProduct->purchase_product_id = intval($purchaseProductIdsArray[$i]);
                $purchaseReturnProduct->purchase_id = $purchaseId;
                $purchaseReturnProduct->warehouse_id = $warehouse;
                $purchaseReturnProduct->purchase_return_id = $purchaseReturnId;
                $purchaseReturnProduct->return_qty = $returnQuantitiesArray[$i];
                $purchaseReturnProduct->remaining_qty = $remainQuantitiesArray[$i];
                $purchaseReturnProduct->unit_price = $unitPricesArray[$i];
                $purchaseReturnProduct->total_price = $totalsArray[$i];
                $purchaseReturnProduct->created_by = Auth::user()->id;
                $purchaseReturnProduct->created_date = Carbon::now();
                $purchaseReturnProduct->deleted = 'No';
                $purchaseReturnProduct->save();

                // update (current_stock)  in products table //
                $productId = intval($itemCodesArray[$i]);
                $quantity = intval($returnQuantitiesArray[$i]);
                $product = Product::find($productId);
                $product->decrement('current_stock', $quantity);

                $Currentstock = Currentstock::where('tbl_productsId', $productId)
                    ->where('tbl_wareHouseId', $warehouse)
                    ->where('deleted', 'No');
                if ($Currentstock->get()) {
                    $Currentstock->decrement('currentStock', $quantity);
                    $Currentstock->increment('purchaseReturnStock', $quantity);
                } else {
                    $Currentstock_insert = new Currentstock;
                    $Currentstock_insert->tbl_productsId = $productId;
                    $Currentstock_insert->tbl_wareHouseId = $warehouse;
                    $Currentstock_insert->currentStock = -$quantity;
                    $Currentstock_insert->purchaseReturnStock = $quantity;
                    $Currentstock_insert->entryBy = auth()->user()->id;
                    $Currentstock_insert->entryDate = date('Y-m-d H:i:s');
                    $Currentstock_insert->save();
                }
            }

            $party = Party::find($request->supplierId);
            $current_due = $party->current_due + $purchaseReturn->grand_total;
            $party->current_due = $current_due;
            $party->save();
            // accounts part Start
            $cashId = ChartOfAccounts::where('slug', 'purchase-return')->first();
            $cash = ChartOfAccounts::find($cashId->id);
            $cash->increment('amount', $request->grandTotal);
            // accounts part End

            /* commemted- 22-05-2022
            $PaymentVoucherNo = PaymentVoucher::max('voucherNo');
            $PaymentVoucherNo++;
            $PaymentVoucherNo = str_pad($PaymentVoucherNo, 6, '0', STR_PAD_LEFT);

            $partyId = $party->id;
            $paymentVoucher = new PaymentVoucher();
            $paymentVoucher->party_id  = $partyId;
            $paymentVoucher->amount = $purchaseReturn->grand_total;
            $paymentVoucher->entryBy = Auth::user()->id;
            $paymentVoucher->paymentDate = Carbon::now();
            $paymentVoucher->status = "Active";
            $paymentVoucher->payment_method = "Cash";
            $paymentVoucher->type = "Party Payable";
            $paymentVoucher->customerType = "Party";
            $paymentVoucher->voucherType = "Local Purchase";
            $paymentVoucher->purchase_return_id = $purchaseReturnId;
            $paymentVoucher->voucherNo  = $PaymentVoucherNo;
            $paymentVoucher->save();
            */

            DB::commit();

            return response()->json(['success' => 'Purchase returned successfully']);
        } catch (Exception $e) {
            DB::rollBack();

            return response()->json(['error' => 'Purchase delete rollBack ']);
        }
    }

    public function purchaseReturnList(Request $request)
    {
        $query = DB::table('purchase_returns')
            ->join('parties', 'purchase_returns.supplier_id', '=', 'parties.id')
            ->join('users', 'purchase_returns.created_by', '=', 'users.id')
            ->select(
                'purchase_returns.purchase_return_no',
                'purchase_returns.purchase_no',
                'purchase_returns.purchase_return_date',
                'purchase_returns.grand_total',
                'purchase_returns.discount',
                'purchase_returns.id',
                'purchase_returns.purchase_date',
                'parties.name',
                'parties.code',
                'parties.address',
                'parties.contact',
                'parties.alternate_contact',
                'users.name as userName'
            )
            ->where('purchase_returns.deleted', 'No');

        if ($search = $request->q) {
            $query->where(function ($q) use ($search) {
                $q->where('purchase_returns.purchase_return_no', 'like', "%{$search}%")
                  ->orWhere('purchase_returns.purchase_no', 'like', "%{$search}%")
                  ->orWhere('parties.name', 'like', "%{$search}%");
            });
        }

        $sortBy = $request->sort_by ?? 'purchase_returns.id';
        $sortDir = $request->sort_direction ?? 'DESC';
        $limit = $request->limit ?? 10;

        $data['purchaseReturns'] = $query->orderBy($sortBy, $sortDir)->paginate($limit)->appends($request->all());

        return view('admin.inventory.purchase.purchase-returnList', $data);
    }

    public function deletePurchaseReturn(Request $request)
    {
        DB::beginTransaction();
        try {
            $purchase = Purchase_Return::find($request->id);
            $purchase->deleted = 'Yes';
            $purchase->deleted_date = Carbon::now();
            $purchase->created_by = Auth::user()->id;
            $purchase->save();

            $party = Party::find($purchase->supplier_id);
            $party->current_due = ($party->current_due + $purchase->current_payment - $purchase->grand_total);
            $party->save();

            $purchase_products = Purchase_Product_Return::where('purchase_return_id', $request->id)->get();
            foreach ($purchase_products as $purchase_product) {
                $purchaseProduct = Purchase_Product_Return::find($purchase_product->id);
                $purchaseProduct->deleted = 'Yes';
                $purchaseProduct->deleted_date = Carbon::now();
                $purchaseProduct->deleted_by = Auth::user()->id;
                $purchaseProduct->save();

                $product = Product::find($purchase_product->product_id);
                $quantity = intval($purchaseProduct->return_qty);
                $unit_price = floatval($purchaseProduct->unit_price);
                $product->increment('purchase_quantity', $quantity);
                $product->increment('current_stock', $quantity);
                $subtotal = floatval($unit_price * $quantity);
                $product->increment('total_purchase_price', $subtotal);

                $Currentstock = Currentstock::where('tbl_productsId', $product->id)
                    ->where('tbl_wareHouseId', $purchaseProduct->warehouse_id)
                    ->where('deleted', 'No');
                if ($Currentstock->get()) {
                    $Currentstock->increment('currentStock', $quantity);
                    $Currentstock->increment('purchaseReturnDelete', $quantity);
                } else {
                    $Currentstock_insert = new Currentstock;
                    $Currentstock_insert->tbl_productsId = $product->id;
                    $Currentstock_insert->tbl_wareHouseId = $purchaseProduct->warehouse_id;
                    $Currentstock_insert->currentStock = $quantity;
                    $Currentstock_insert->purchaseReturnDelete = $quantity;
                    $Currentstock_insert->entryBy = auth()->user()->id;
                    $Currentstock_insert->entryDate = date('Y-m-d H:i:s');
                    $Currentstock_insert->save();
                }
            }
            DB::commit();

            return response()->json(['Success' => 'Purchase Return deleted!'.$product]);
        } catch (Exception $e) {
            DB::rollBack();

            return response()->json(['error' => 'Purchase Return delete rollBack '.$e]);
        }
    }

    public function createPDF($id)
    {

        $invoice = DB::table('purchase_returns')
            ->join('purchase_product_returns', 'purchase_returns.id', '=', 'purchase_product_returns.purchase_return_id')
            ->join('products', 'purchase_product_returns.product_id', '=', 'products.id')
            ->join('parties', 'purchase_returns.supplier_id', '=', 'parties.id')
            ->join('users', 'purchase_returns.created_by', '=', 'users.id')
            ->where([['purchase_returns.id', '=', $id], ['purchase_returns.deleted', '=', 'No']])
            ->select(
                'purchase_returns.*',
                'purchase_product_returns.return_qty',
                'products.name',
                'products.code as productCode',
                'parties.name as supplier_name',
                'parties.code',
                'parties.contact',
                'parties.address',
                'purchase_product_returns.remaining_qty',
                'purchase_returns.purchase_return_date',
                'purchase_returns.purchase_return_no',
                'purchase_product_returns.unit_price',
                'purchase_product_returns.total_price',
                'users.name as entryBy'
            )
            ->get();

        $userId = auth()->user()->id;
        $userName = User::where('id', $userId)->pluck('name')->first();
        session(['userName' => $userName]);

        // return view('admin.inventory.purchase.purchase-return-report', compact('invoice'));

        $pdf = PDF::loadView('admin.inventory.purchase.purchase-return-report', compact('invoice'));

        return $pdf->stream('purchase-return-report-pdf_file.pdf', ['Attachment' => false]);
    }
}
