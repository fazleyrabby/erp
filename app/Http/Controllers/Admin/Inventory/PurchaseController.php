<?php

namespace App\Http\Controllers\Admin\Inventory;

use App\Http\Controllers\Controller;
use App\Models\Accounts\ChartOfAccounts;
use App\Models\Accounts\Voucher;
use App\Models\Accounts\VoucherDetails;
use App\Models\inventory\Brand;
use App\Models\inventory\Category;
use App\Models\inventory\Currentstock;
use App\Models\inventory\Party;
use App\Models\inventory\PaymentVoucher;
use App\Models\inventory\Product;
use App\Models\inventory\Purchase;
use App\Models\inventory\PurchaseProduct;
use App\Models\inventory\SerializeProduct;
use App\Models\inventory\Warehouse;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use PDF;

class PurchaseController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:purchase.view', ['only' => ['index', 'getProducts']]);
        $this->middleware('permission:purchase.add', ['only' => ['add', 'checkOutCart']]);
        $this->middleware('permission:purchase.delete', ['only' => ['delete']]);
    }

    public function index(Request $request)
    {
        $query = Purchase::with(['supplier', 'creator'])
            ->where('deleted', 'No');

        if ($search = $request->q) {
            $query->where(function ($q) use ($search) {
                $q->where('purchase_no', 'like', "%{$search}%")
                    ->orWhereHas('supplier', function ($q2) use ($search) {
                        $q2->where('name', 'like', "%{$search}%")
                           ->orWhere('code', 'like', "%{$search}%")
                           ->orWhere('contact', 'like', "%{$search}%");
                    });
            });
        }

        $sortBy = $request->sort_by ?? 'id';
        $sortBy = str_replace('purchases.', '', $sortBy);
        $sortDir = $request->sort_direction ?? 'DESC';
        $limit = $request->limit ?? 10;

        $data['purchases'] = $query->orderBy($sortBy, $sortDir)->paginate($limit)->appends($request->all());

        return view('admin.inventory.purchase.view-purchase', $data);
    }

    public function add()
    {
        $data['categories'] = Category::where('deleted', 'No')->where('status', 'Active')->toBase()->get();
        $data['brands'] = Brand::where('deleted', 'No')->where('status', 'Active')->toBase()->get();
        $data['products'] = Product::with('brand')->where('deleted', 'No')->where('status', 'Active')->where('type', '!=', 'service')->get();
        $data['suppliers'] = Party::where('deleted', 'No')->where('status', 'Active')->whereIn('party_type', ['Supplier', 'Both'])->toBase()->get();
        $data['warehouses'] = Warehouse::where('deleted', 'No')->where('status', 'Active')->toBase()->get();
        $data['coas'] = ChartOfAccounts::where('deleted', 'No')->where('status', 'Active')->where('parent_id', '=', '30')->toBase()->get();

        return view('admin.inventory.purchase.add-purchase', $data);
    }

    public function supplierDue(Request $request)
    {
        $supplierDue = Party::find($request->id);

        return $supplierDue->current_due;
    }

    public function addToCart(Request $request)
    {
        $data = '';
        $available_quantity = 0;
        $checkQuantity = 0;
        $productId = null;
        $product_type = '';
        if (Session::get('purchase_cart_array') != null) {
            $is_available = 0;
            foreach (Session::get('purchase_cart_array') as $keys => $values) {
                if ((Session::get('purchase_cart_array')[$keys]['product_id'] == $request->id && Session::get('purchase_cart_array')[$keys]['warehouse_id'] == $request->warehouseId) || (Session::get('purchase_cart_array')[$keys]['barcode_no'] == $request->barcode && $request->barcode != '')) {
                    $is_available++;
                    session()->put('purchase_cart_array.'.$keys.'.product_quantity', Session::get('purchase_cart_array')[$keys]['product_quantity'] + $request->quantity);
                    $checkQuantity = Session::get('purchase_cart_array')[$keys]['product_quantity'];
                    $productId = $request->id;
                    $product_type = Session::get('purchase_cart_array')[$keys]['product_type'];
                }
            }
            if ($is_available == 0) {
                if (isset($request->barcode)) {
                    $productInfo = Product::where('deleted', 'No')->where('type', '!=', 'service')->where('status', 'Active')->where('barcode_no', $request->barcode)->first();
                } elseif (isset($request->id)) {
                    $productInfo = Product::where('deleted', 'No')->where('type', '!=', 'service')->where('status', 'Active')->where('id', $request->id)->first();
                    $currentStockInfo = Currentstock::where('deleted', 'No')->where('tbl_productsId', $productInfo->id)->where('tbl_wareHouseId', $request->warehouseId)->first();
                    if ($currentStockInfo) {
                        $available_quantity = $currentStockInfo->currentStock;
                    } else {
                        $available_quantity = 0;
                    }
                    $productId = $productInfo->id;
                }
                $temQuantity = [];
                $maxNumber = [];
                if ($productInfo->type == 'serialize') {
                    $maxNumber = SerializeProduct::where('tbl_productsId', $productId)->max('serial_no');
                    $temQuantity = $request->quantity;
                }
                $checkQuantity = $request->quantity;
                $product_type = $productInfo->type;
                $item_array = [
                    'product_id' => $productInfo->id,
                    'product_name' => $productInfo->name.' - '.$productInfo->code,
                    'product_image' => $productInfo->image,
                    'available_qty' => $available_quantity,
                    'product_price' => $productInfo->purchase_price,
                    'product_quantity' => $request->quantity,
                    'product_discount' => $productInfo->discount,
                    'barcode_no' => $productInfo->barcode_no,
                    'warehouse_id' => $request->warehouseId,
                    'warehouse_name' => $request->warehouseName,
                    'product_type' => $productInfo->type,
                    'items_in_box' => $productInfo->items_in_box,
                    'serialNumbers' => [$maxNumber],
                    'stockQuantities' => [$temQuantity],
                ];
                Session::push('purchase_cart_array', $item_array);
            }
        } else {
            if (isset($request->barcode)) {
                $productInfo = Product::where('deleted', 'No')->where('type', '!=', 'service')->where('status', 'Active')->where('barcode_no', $request->barcode)->first();
            } elseif (isset($request->id)) {
                $productInfo = Product::where('deleted', 'No')->where('type', '!=', 'service')->where('status', 'Active')->where('id', $request->id)->first();
                $currentStockInfo = Currentstock::where('deleted', 'No')->where('tbl_productsId', $productInfo->id)->where('tbl_wareHouseId', $request->warehouseId)->first();
                if ($currentStockInfo) {
                    $available_quantity = $currentStockInfo->currentStock;
                } else {
                    $available_quantity = 0;
                }
                $productId = $productInfo->id;
                $checkQuantity = $request->quantity;
                $product_type = $productInfo->type;
            }
            $temQuantity = [];
            $maxNumber = [];
            if ($productInfo->type == 'serialize') {
                $serializeProduct = DB::table('tbl_serialize_products')->orderBy('id', 'desc')
                    ->where('tbl_productsId', $productInfo->id)
                    ->first();
                if ($serializeProduct) {
                    $maxNumber = $serializeProduct->serial_no + 1;
                }
                $temQuantity = $request->quantity;
            }

            $item_array = [
                'product_id' => $productInfo->id,
                'product_name' => $productInfo->name.' - '.$productInfo->code,
                'product_image' => $productInfo->image,
                'available_qty' => $available_quantity,
                'product_price' => $productInfo->purchase_price,
                'product_quantity' => $request->quantity,
                'product_discount' => $productInfo->discount,
                'barcode_no' => $productInfo->barcode_no,
                'warehouse_id' => $request->warehouseId,
                'warehouse_name' => $request->warehouseName,
                'product_type' => $productInfo->type,
                'items_in_box' => $productInfo->items_in_box,
                'serialNumbers' => [$maxNumber],
                'stockQuantities' => [$temQuantity],
            ];
            Session::push('purchase_cart_array', $item_array);
        }
        $data .= 'Success';

        return response()->json(['data' => $data, 'productId' => $productId, 'quantity' => $checkQuantity, 'warehouseId' => $request->warehouseId, 'product_type' => $product_type]);
    }

    public function fetchCart()
    {
        $grandTotal = 0;
        $cart = '';
        if (Session::get('purchase_cart_array') != null) {
            $i = 1;
            foreach (Session::get('purchase_cart_array') as $keys => $values) {
                if (Session::get('purchase_cart_array')[$keys]['product_discount'] > 0) {
                    $unitPrice = Session::get('purchase_cart_array')[$keys]['product_price'];
                    $totalPrice = Session::get('purchase_cart_array')[$keys]['product_quantity'] * $unitPrice;
                } else {
                    $unitPrice = Session::get('purchase_cart_array')[$keys]['product_price'];
                    $totalPrice = Session::get('purchase_cart_array')[$keys]['product_quantity'] * $unitPrice;
                }
                $productId = Session::get('purchase_cart_array')[$keys]['product_id'];
                $warehouseId = Session::get('purchase_cart_array')[$keys]['warehouse_id'];
                $productType = Session::get('purchase_cart_array')[$keys]['product_type'];
                if ($productType == 'serialize') {
                    $btn = ' <a href="#" onclick="showSerializTable('.Session::get('purchase_cart_array')[$keys]['product_id'].', '.Session::get('purchase_cart_array')[$keys]['warehouse_id'].', '.Session::get('purchase_cart_array')[$keys]['product_quantity'].')"> <i class="fa fa-edit"> </i> </a>';
                    $updateQty = "'".'updateQty'."'";
                } else {
                    $btn = '';
                    $updateQty = '';
                }
                $cart .= '<tr><td style="text-align: center;">'.$i++.'<input type="hidden" name="ids[]" id="id_'.$productId.'_'.$warehouseId.'" value="'.$productId.'" /><input type="hidden" name="warehouseIds[]" id="warehouse_id_'.$productId.'_'.$warehouseId.'" value="'.$productId.'" /></td>'.
                    '<td>'.Session::get('purchase_cart_array')[$keys]['product_name'].' ['.Session::get('purchase_cart_array')[$keys]['warehouse_name'].']</td>'.
                    '<td style="text-align: center;"><span id="available_qty_'.$productId.'_'.$warehouseId.'">'.Session::get('purchase_cart_array')[$keys]['available_qty'].'</span></td>'.
                    '<td><input type="text" class="quantityUpdate only-number" style="text-align: center;width: 80%;" id="quantity_'.$productId.'_'.$warehouseId.'" name="quantity[]" onblur="loadCartandUpdate('.$productId.','.$warehouseId.','.$updateQty.')" value="'.Session::get('purchase_cart_array')[$keys]['product_quantity'].'" />'.$btn.'</td>'.
                    '<td><input type="text" style="text-align: center;width: 100%;" id="unitPrice_'.$productId.'_'.$warehouseId.'"  name="unitPrice[]" onblur="loadCartandUpdate('.$productId.','.$warehouseId.')" value="'.$unitPrice.'" /></td>'.
                    '<td style="text-align: right;"><span id="totalPrice_'.$productId.'_'.$warehouseId.'">'.numberFormat($totalPrice).'</span></td>'.
                    '<td style="text-align: center;"><a href="#" onclick="removeCartProduct('.Session::get('purchase_cart_array')[$keys]['product_id'].', '.Session::get('purchase_cart_array')[$keys]['warehouse_id'].')" style="color:red;"><i class="fa fa-trash"> </i> </a></td></tr>';
                $grandTotal += $totalPrice;
            }
        }
        $cart .= '<tr><td colspan="5" class="text-right" > Total Tk : </td><td id="totalAmount" class="text-right"> '.numberFormat($grandTotal).'</td><td></td></tr>';
        $data = [
            'cart' => $cart,
            'totalAmount' => $grandTotal,
        ];

        return response()->json(['data' => $data]);
    }

    public function removeProduct(Request $request)
    {
        $id = $request->id;
        $warehouseId = $request->warehouse_id;
        $data = '';
        $cartData = Session::get('purchase_cart_array');
        foreach (Session::get('purchase_cart_array') as $keys => $values) {
            if (Session::get('purchase_cart_array')[$keys]['product_id'] == $id && Session::get('purchase_cart_array')[$keys]['warehouse_id'] == $warehouseId) {
                unset($cartData[$keys]);
                Session::put('purchase_cart_array', $cartData);
                $data = 'Success';
                break;
            }
        }
        $data = 'Success';

        return response()->json(['data' => $data]);
    }

    public function updateCart(Request $request)
    {
        if (Session::get('purchase_cart_array') != null) {
            foreach (Session::get('purchase_cart_array') as $keys => $values) {
                if (Session::get('purchase_cart_array')[$keys]['product_id'] == $request->id && Session::get('purchase_cart_array')[$keys]['warehouse_id'] == $request->warehouseId) {
                    session()->put('purchase_cart_array.'.$keys.'.product_quantity', $request->quantity);
                    session()->put('purchase_cart_array.'.$keys.'.product_price', $request->unitPrice);
                    // Serialize Product
                    if (Session::get('purchase_cart_array')[$keys]['product_type'] == 'serialize') {
                        if ($request->has('product_type') && $request->product_type == true) {
                            $serialNumbers = (explode(',', $request->serialNumbers));
                            $stockQuantities = (explode(',', $request->stockQuantities));
                            session()->put('purchase_cart_array.'.$keys.'.serialNumbers', $serialNumbers);
                            session()->put('purchase_cart_array.'.$keys.'.stockQuantities', $stockQuantities);
                        } else {
                            $tempSerialNum = [false];
                            $tempQuantity = [$request->quantity];
                            session()->put('purchase_cart_array.'.$keys.'.serialNumbers', $tempSerialNum);
                            session()->put('purchase_cart_array.'.$keys.'.stockQuantities', $tempQuantity);
                        }
                    }
                    // End Serialize Product
                    $data = 'Success';
                }
            }
        } else {
            $data = '';
        }

        return response()->json(['data' => $data]);
    }

    public function clearCart(Request $request)
    {
        Session::forget('purchase_cart_array');
        $data = 'Success';

        return response()->json(['data' => $data]);
    }

    // =========== Start Serialize Product ===========//
    public function showSerializTable(Request $request)
    {
        $trId = 0;
        $rows = '';
        foreach (Session::get('purchase_cart_array') as $keys => $values) {
            if (Session::get('purchase_cart_array')[$keys]['product_id'] == $request->id && Session::get('purchase_cart_array')[$keys]['warehouse_id'] == $request->warehouseId) {
                $product_id = Session::get('purchase_cart_array')[$keys]['product_id'];
                $warehouse_id = Session::get('purchase_cart_array')[$keys]['warehouse_id'];
                $items_in_box = Session::get('purchase_cart_array')[$keys]['items_in_box'];
                $function = '';
                if (Session::get('purchase_cart_array')[$keys]['stockQuantities'] && Session::get('purchase_cart_array')[$keys]['serialNumbers'][0] != false) {
                    foreach (Session::get('purchase_cart_array')[$keys]['stockQuantities'] as $key => $stockQty) {
                        $serialNum = Session::get('purchase_cart_array')[$keys]['serialNumbers'][$key];
                        if ($key == 0) {
                            $function = 'onchange="generateSerialNo(this.value)"';
                        }
                        $rows .= '<tr id="row'.$key.'">'.
                            '<td>'.($key + 1).'</td>'.
                            '<td><input class="form-control input-sm stockQuantity'.$key.
                            '" id="stockQuantity" type="text" name="stockQuantity" placeholder=" Quantity... " required oninput="calculateTotalQuantity()" onblur="loadCartandUpdate('.$product_id.','.$warehouse_id.','.true.')" value="'.$stockQty.'"  ></td>';
                        $rows .=
                            '<td><input class="form-control input-sm serialNo'.$key.
                            '" id="serialNo" type="text" name="serialNo" placeholder=" Serial Number... " required value="'.$serialNum.'" '.$function.'><td><a href="#" onclick="removeRow('.$key.')" style="color:red;"><i class="fa fa-trash"> </i> </a></td></td></tr>';
                    }
                } else {
                    $tempSerialNums = [];
                    $tempQuantities = [];
                    $serializeProduct = DB::table('tbl_serialize_products')->orderBy('id', 'desc')
                        ->where('tbl_productsId', $product_id)
                        ->first();
                    if ($serializeProduct) {
                        $maxNumber = $serializeProduct->serial_no;
                    }
                    $totalQuantity = count(Session::get('purchase_cart_array')[$keys]['stockQuantities']) == 1 ? Session::get('purchase_cart_array')[$keys]['stockQuantities'][0] : 0;
                    if ($trId == 0) {
                        $function = 'onchange="generateSerialNo(this.value)"';
                    }
                    $avarageQty = ceil($totalQuantity / $items_in_box);
                    for ($i = 0; $i < $avarageQty; $i++) {
                        if ($totalQuantity < $items_in_box) {
                            $items_in_box = $totalQuantity;
                        }
                        $rows .= '<tr id="row'.$i.'">'.
                            '<td>'.($i + 1).'</td>'.
                            '<td><input class="form-control input-sm stockQuantity'.$i.
                            '" id="stockQuantity" type="text" name="stockQuantity" placeholder=" Quantity... " required oninput="calculateTotalQuantity()" onblur="loadCartandUpdate('.$product_id.','.$warehouse_id.','.true.')" value="'.$items_in_box.'"  ></td>';
                        $rows .=
                            '<td><input class="form-control input-sm serialNo'.$i.
                            '" id="serialNo" type="text" name="serialNo" placeholder=" Serial Number... " required value="'.++$maxNumber.'" '.$function.'><td><a href="#" onclick="removeRow('.$i.')" style="color:red;"><i class="fa fa-trash"> </i> </a></td></td></tr>';
                        $tempSerialNums[$i] = $maxNumber;
                        $tempQuantities[$i] = $items_in_box;
                        $totalQuantity -= $items_in_box;
                    }
                    session()->put('purchase_cart_array.'.$keys.'.serialNumbers', $tempSerialNums);
                    session()->put('purchase_cart_array.'.$keys.'.stockQuantities', $tempQuantities);
                }
            }
        }

        return response()->json(['displayTable' => $rows]);
    }
    // =========== End Serialize Product ===========//

    public function checkOutCart(Request $request)
    {
        $request->validate([
            'supplier' => 'required',
            'category' => 'required',
        ]);

        DB::beginTransaction();
        try {
            $purchaseNo = Purchase::where('deleted', 'No')->max('purchase_no');
            $purchaseNo++;
            $purchaseNo = str_pad($purchaseNo, 6, '0', STR_PAD_LEFT);
            $purchase = new Purchase;
            $purchase->supplier_id = $request->supplier_id;
            $purchase->purchase_no = $purchaseNo;
            $purchase->coa_id = $request->category;
            $purchase->date = $request->date;
            $purchase->total_amount = floatval($request->total_amount);
            $purchase->discount = floatval($request->discount);
            $purchase->carrying_cost = floatval($request->carrying_cost);
            $grand_total = floatval($request->grand_total);
            $purchase->grand_total = $grand_total;
            $purchase->previous_due = floatval($request->previous_due);
            $purchase->current_balance = floatval($request->current_balance);
            $purchase->total_with_due = floatval($request->total_with_due);
            $current_payment = floatval($request->current_payment);
            $purchase->current_payment = $current_payment;
            $purchase->created_by = auth()->user()->id;
            $purchase->created_date = date('Y-m-d H:i:s');
            $purchase->save();
            $purchase_id = $purchase->id;
            foreach (Session::get('purchase_cart_array') as $keys => $values) {
                $product_id = Session::get('purchase_cart_array')[$keys]['product_id'];
                $warehouse_id = Session::get('purchase_cart_array')[$keys]['warehouse_id'];
                $product = Product::find($product_id);
                $unit_id = $product->unit_id;
                $unit_price = Session::get('purchase_cart_array')[$keys]['product_price'];
                $quantity = Session::get('purchase_cart_array')[$keys]['product_quantity'];
                $product->increment('purchase_quantity', $quantity);
                $product->increment('current_stock', $quantity);
                $subtotal = $unit_price * $quantity;
                $product->increment('total_purchase_price', $subtotal);
                $lot_no = PurchaseProduct::where('product_id', $product_id)->max('lot_no');
                $lot_no++;
                $purchase_products = new PurchaseProduct;
                $purchase_products->purchase_id = $purchase_id;
                $purchase_products->product_id = $product_id;
                $purchase_products->warehouse_id = $warehouse_id;
                $purchase_products->unit_id = $unit_id;
                $purchase_products->unit_price = floatval($unit_price);
                $purchase_products->quantity = $quantity;
                $purchase_products->lot_no = $lot_no;
                $purchase_products->subtotal = floatval($subtotal);
                $purchase_products->created_by = auth()->user()->id;
                $purchase_products->created_date = date('Y-m-d H:i:s');
                $purchase_products->save();
                // Start Serialize Product
                if (Session::get('purchase_cart_array')[$keys]['product_type'] == 'serialize') {
                    $quantity = 0;
                    if (Session::get('purchase_cart_array')[$keys]['stockQuantities']) {
                        foreach (Session::get('purchase_cart_array')[$keys]['stockQuantities'] as $key => $stockQty) {
                            $serialNum = Session::get('purchase_cart_array')[$keys]['serialNumbers'][$key];
                            $serializeProduct = new SerializeProduct;
                            $serializeProduct->tbl_productsId = $product_id;
                            $serializeProduct->serial_no = $serialNum;
                            $serializeProduct->warehouse_id = $warehouse_id;
                            $serializeProduct->purchase_id = $purchase_id;
                            $serializeProduct->quantity = $stockQty;
                            $serializeProduct->created_by = auth()->user()->id;
                            $serializeProduct->created_date = date('Y-m-d H:i:s');
                            $serializeProduct->save();
                            $quantity += $stockQty;
                        }
                    }
                }
                // End Serialize Product
                if (Session::get('purchase_cart_array')[$keys]['product_type'] == 'service') {
                    continue;
                }
                $Currentstock = Currentstock::where('tbl_productsId', $product_id)
                    ->where('tbl_wareHouseId', $warehouse_id)
                    ->where('deleted', 'No');
                if ($Currentstock->first()) {
                    $Currentstock->increment('currentStock', $quantity);
                    $Currentstock->increment('purchaseStock', $quantity);
                } else {
                    $Currentstock_insert = new Currentstock;
                    $Currentstock_insert->tbl_productsId = $product_id;
                    $Currentstock_insert->tbl_wareHouseId = $warehouse_id;
                    $Currentstock_insert->currentStock = $quantity;
                    $Currentstock_insert->purchaseStock = $quantity;
                    $Currentstock_insert->entryBy = auth()->user()->id;
                    $Currentstock_insert->entryDate = date('Y-m-d H:i:s');
                    $Currentstock_insert->save();
                }
            }
            $party = Party::find($request->supplier_id);
            $party->decrement('current_due', ($grand_total - $current_payment));

            $maxCode = PaymentVoucher::where('deleted', 'No')->max('voucherNo');
            $maxCode++;
            $maxCode = str_pad($maxCode, 6, '0', STR_PAD_LEFT);
            $paymentVoucher = new PaymentVoucher;
            $paymentVoucher->voucherNo = $maxCode;
            $paymentVoucher->party_id = $request->supplier_id;
            $paymentVoucher->purchase_id = $purchase_id;
            $paymentVoucher->amount = floatval($request->grand_total);
            $paymentVoucher->payment_method = 'Cash';
            $paymentVoucher->paymentDate = $request->date;
            $paymentVoucher->type = 'Payable';
            $paymentVoucher->voucherType = 'Local Purchase';
            $paymentVoucher->remarks = 'payable for purchase code: '.$purchaseNo.' payment: '.$request->grand_total;
            $paymentVoucher->entryBy = auth()->user()->id;
            $paymentVoucher->save();
            if (floatval($request->current_payment) > 0) {
                $maxCode = PaymentVoucher::where('deleted', 'No')->max('voucherNo');
                $maxCode++;
                $maxCode = str_pad($maxCode, 6, '0', STR_PAD_LEFT);
                $paymentVoucher = new PaymentVoucher;
                $paymentVoucher->voucherNo = $maxCode;
                $paymentVoucher->party_id = $request->supplier_id;
                $paymentVoucher->purchase_id = $purchase_id;
                $paymentVoucher->amount = floatval($request->current_payment);
                $paymentVoucher->payment_method = $request->payment_method;
                $paymentVoucher->paymentDate = $request->date;
                $paymentVoucher->type = 'Payment';
                $paymentVoucher->voucherType = 'Local Purchase';
                $paymentVoucher->remarks = 'Payment for purchase code: '.$purchaseNo.' payment: '.$request->grand_total;
                $paymentVoucher->entryBy = auth()->user()->id;
                $paymentVoucher->save();
            }

            /* accounts part start */
            $voucher = new Voucher;
            $voucher->vendor_id = $request->supplier_id;
            $voucher->transaction_date = $request->date;
            $voucher->amount = floatval($request->grand_total);
            $voucher->payment_method = $request->payment_method;
            $voucher->purchase_id = $purchase_id;
            $voucher->deleted = 'No';
            $voucher->status = 'Active';
            $voucher->created_by = Auth::user()->id;
            $voucher->created_date = date('Y-m-d h:s');
            $voucher->save();
            $voucherId = $voucher->id;

            // $configId=ChartOfAccounts::where('name','=','Purchase')->first();
            /* if($request->category == '1'){
                $configId=ChartOfAccounts::where('name','=','Power Tools Expense')->first();
            }elseif(($request->category == '2')){
                $configId=ChartOfAccounts::where('name','=','Welding Machine Expense')->first();
            }elseif(($request->category == '3')){
                $configId=ChartOfAccounts::where('name','=','Purchase')->first();
            }else{

            } */

            $voucherDetails = new VoucherDetails;
            $voucherDetails->tbl_acc_voucher_id = $voucherId;
            $voucherDetails->tbl_acc_coa_id = $request->category;
            $voucherDetails->debit = floatval($request->grand_total);
            $voucherDetails->voucher_title = 'Purchase created with Purchase code '.$purchaseNo;
            $voucherDetails->deleted = 'No';
            $voucherDetails->status = 'Active';
            $voucherDetails->created_by = Auth::user()->id;
            $voucherDetails->created_date = date('Y-m-d H:i:s');
            $voucherDetails->save();

            if ($request->current_payment > 0) {
                $voucherDetails = new VoucherDetails;
                $voucherDetails->tbl_acc_voucher_id = $voucherId;
                $voucherDetails->tbl_acc_coa_id = $request->category;
                $voucherDetails->credit = floatval($request->current_payment);
                $voucherDetails->voucher_title = 'Purchase amount paid with Purchase code '.$purchaseNo;
                $voucherDetails->deleted = 'No';
                $voucherDetails->status = 'Active';
                $voucherDetails->created_by = Auth::user()->id;
                $voucherDetails->created_date = date('Y-m-d h:s');
                $voucherDetails->save();
            }

            $cashId = ChartOfAccounts::where('slug', '=', 'cash')->first();
            $cash = ChartOfAccounts::find($cashId->id);
            $cash->decrement('amount', $request->current_payment);
            /* accounts part end */

            Session::forget('purchase_cart_array');
            DB::commit();

            return response()->json(['Success' => 'Product purchased successfully', 'purchaseId' => $purchase_id]);
        } catch (Exception $e) {
            DB::rollBack();

            return response()->json(['error' => 'Purchase rollBack!']);
        }
    }

    public function delete(Request $request)
    {
        DB::beginTransaction();
        try {
            $purchase = Purchase::find($request->id);
            $purchase->deleted = 'Yes';
            $purchase->deleted_date = date('Y-m-d H:i:s');
            $purchase->save();

            $party = Party::find($purchase->supplier_id);
            $party->current_due = ($party->current_due - $purchase->current_payment + $purchase->grand_total);
            $party->save();

            $purchase_products = PurchaseProduct::where('purchase_id', $request->id)->get();
            foreach ($purchase_products as $purchase_product) {
                $purchaseProduct = PurchaseProduct::find($purchase_product->id);
                $purchaseProduct->deleted = 'Yes';

                $purchaseProduct->deleted_date = date('Y-m-d H:i:s');
                $purchaseProduct->deleted_by = auth()->user()->id;
                $purchaseProduct->save();

                $product = Product::find($purchase_product->product_id);
                $quantity = intval($purchaseProduct->quantity);
                $unit_price = floatval($product->unit_price);
                $product->decrement('purchase_quantity', $quantity);
                $product->decrement('current_stock', $quantity);
                $subtotal = floatval($unit_price * $quantity);
                $product->decrement('total_purchase_price', $subtotal);

                $currentstock = Currentstock::where('tbl_productsId', $purchase_product->product_id)
                    ->where('tbl_wareHouseId', $purchase_product->warehouse_id)
                    ->where('deleted', 'No');
                if ($currentstock->get()) {
                    $currentstock->decrement('currentStock', $quantity);
                    $currentstock->increment('purchaseDelete', $quantity);
                } else {
                    $currentstock_insert = new Currentstock;
                    $currentstock_insert->tbl_productsId = $purchase_product->product_id;
                    $currentstock_insert->tbl_wareHouseId = $purchase_product->warehouse_id;
                    $currentstock_insert->currentStock = -$quantity;
                    $currentstock_insert->purchaseDelete = $quantity;
                    $currentstock_insert->entryBy = auth()->user()->id;
                    $currentstock_insert->entryDate = date('Y-m-d H:i:s');
                    $currentstock_insert->save();
                }
            }

            $purchaseId = $request->id;
            $result = SerializeProduct::where('purchase_id', $purchaseId)->update(['deleted' => 'Yes', 'deleted_by' => auth()->user()->id, 'deleted_date' => date('Y-m-d H:i:s')]);
            PaymentVoucher::where('purchase_id', '=', $request->id)->update(['deleted' => 'Yes', 'deleted_by' => auth()->user()->id, 'deleted_date' => date('Y-m-d H:i:s')]);
            //
            $voucher = Voucher::where('purchase_id', '=', $request->id)->first();
            $voucherId = $voucher->id;
            $voucher->update(['deleted' => 'Yes', 'status' => 'Inactive', 'deleted_by' => auth()->user()->id, 'deleted_date' => date('Y-m-d H:i:s')]);
            VoucherDetails::where('tbl_acc_voucher_id', $voucherId)->update(['deleted' => 'Yes', 'status' => 'Inactive', 'deleted_by' => auth()->user()->id, 'deleted_date' => date('Y-m-d H:i:s')]);

            DB::commit();

            return response()->json(['Success' => 'Purchase deleted!']);
        } catch (Exception $e) {
            DB::rollBack();

            return response()->json(['error' => 'Purchase delete rollBack!']);
        }
    }

    public function createPDF($id)
    {
        $invoice = DB::table('purchase_products')
            ->join('purchases', 'purchase_products.purchase_id', '=', 'purchases.id')
            ->join('parties', 'purchases.supplier_id', '=', 'parties.id')
            ->join('products', 'purchase_products.product_id', '=', 'products.id')
            ->leftjoin('users', 'purchases.created_by', '=', 'users.id')
            ->where([['purchases.id', '=', $id], ['purchases.deleted', '=', 'No']])
            ->select(
                'purchase_products.*',
                'users.name as entryBy',
                'purchases.date',
                'purchases.total_amount',
                'purchases.grand_total',
                'purchases.current_payment',
                'purchases.purchase_no',
                'parties.contact',
                'parties.name as supplier_name',
                'parties.code',
                'parties.address',
                'products.name',
                'products.id as productId',
                'products.code as productCode',
                'products.image',
                'products.type'
            )->get();

        $purchaseId = $id;
        $purchases = Purchase::where('id', $purchaseId)->get();
        $pdf = PDF::loadView('admin.inventory.purchase.purchase-report', ['invoice' => $invoice, 'purchases' => $purchases]);

        return $pdf->stream('purchase-report-pdf.pdf', ['Attachment' => false]);
    }
}
