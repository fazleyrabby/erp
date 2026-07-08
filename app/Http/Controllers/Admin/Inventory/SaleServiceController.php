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
use App\Models\inventory\Sale;
use App\Models\inventory\SaleOrder;
use App\Models\inventory\SaleOrderFeedback;
use App\Models\inventory\SaleOrderProduct;
use App\Models\inventory\SaleProduct;
use App\Models\inventory\SaleSerializeProduct;
use App\Models\inventory\SerializeProduct;
use App\Models\inventory\Warehouse;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use PDF;

class SaleServiceController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:sale.service.view', ['only' => ['viewSaleOrders']]);
        $this->middleware('permission:sale.service.add', ['only' => ['add', 'addToCart', 'fetchCart', 'checkOutCart']]);
        $this->middleware('permission:sale.service.edit', ['only' => ['editSaleOrder', 'addToOrderEditCart', 'fetchOrderEditCart', 'updateOrderEditCart']]);
        $this->middleware('permission:sale.service.statusComplete', ['only' => ['statusComplete']]);
        $this->middleware('permission:sale.service.createOrderToWalkinSale', ['only' => ['createOrderToWalkinSale', 'completeOrdercheckOutCart']]);
    }

    public function viewSaleOrders(Request $request)
    {
        $saleType = 'walkin_sale';
        session(['type' => $saleType]);

        $searchTerm = $request->q;
        $sortBy = $request->sort_by ?? 'sale_orders.id';
        $sortDirection = $request->sort_direction ?? 'DESC';
        $limit = $request->limit ?? 10;

        $query = DB::table('sale_orders')
            ->join('parties', 'sale_orders.customer_id', '=', 'parties.id')
            ->leftjoin('users', 'users.id', '=', 'sale_orders.created_by')
            ->select(
                'sale_orders.sale_no',
                'sale_orders.date',
                'sale_orders.total_amount',
                'sale_orders.current_payment',
                'sale_orders.discount',
                'sale_orders.carrying_cost',
                'sale_orders.vat',
                'sale_orders.ait',
                'sale_orders.id',
                'sale_orders.sale_id',
                'sale_orders.grand_total',
                'sale_orders.order_status',
                'sale_orders.ready_to_deliver_date',
                'sale_orders.created_date',
                'sale_orders.service_start_date',
                'sale_orders.delivered_date',
                'sale_orders.brand',
                'sale_orders.model',
                'sale_orders.item',
                'sale_orders.project_name',
                'parties.name',
                'parties.code',
                'parties.address',
                'parties.contact',
                'parties.alternate_contact',
                'users.name as userName'
            )
            ->where('sale_orders.sales_type', $saleType)
            ->where('sale_orders.deleted', 'No')
            ->where('parties.deleted', 'No');

        if ($searchTerm) {
            $query->where(function ($q) use ($searchTerm) {
                $q->where('sale_orders.sale_no', 'like', "%{$searchTerm}%")
                  ->orWhere('parties.name', 'like', "%{$searchTerm}%")
                  ->orWhere('sale_orders.brand', 'like', "%{$searchTerm}%")
                  ->orWhere('sale_orders.model', 'like', "%{$searchTerm}%");
            });
        }

        $saleOrders = $query->orderBy($sortBy, $sortDirection)
            ->paginate($limit)
            ->appends($request->all());

        return view('admin.inventory.service.view-sale-orders', compact('saleOrders', 'saleType'));
    }

    public function changeCustomer(Request $request)
    {

        $saleOrder = SaleOrder::find($request->id);
        $saleOrder->customer_id = '52';
        $saleOrder->customer_change_date = Carbon::now();
        $saleOrder->save();

        return response()->json();
    }

    public function getPaymentData(Request $request)
    {
        $saleorders = SaleOrder::find($request->id);

        return $saleorders;
    }

    public function add()
    {
        $categories = Category::where('deleted', 'No')->where('status', 'Active')->get();
        $brands = Brand::where('deleted', 'No')->where('status', 'Active')->get();
        $products = Product::where('deleted', 'No')->where('status', 'Active')->get();
        $warehouses = Warehouse::where('deleted', 'No')->where('status', 'Active')->get();
        $customers = Party::where('deleted', 'No')->where('status', 'Active')->where('party_type', 'Customer')->get();
        $type = 'walkin_sale';
        $coas = ChartOfAccounts::where('deleted', 'No')->where('status', 'Active')->where('parent_id', '=', '31')->get();

        return view('admin.inventory.service.add-service-sale', compact('categories', 'brands', 'products', 'coas', 'warehouses', 'customers', 'type'));
    }

    public function addToCart(Request $request)
    {
        $data = '';
        $product_type = '';
        if (Session::get('order_cart_array') != null) {
            $is_available = 0;
            foreach (Session::get('order_cart_array') as $keys => $values) {
                if ((Session::get('order_cart_array')[$keys]['product_id'] == $request->id && Session::get('order_cart_array')[$keys]['warehouse_id'] == $request->warehouseId) || (Session::get('order_cart_array')[$keys]['barcode_no'] == $request->barcode && $request->barcode != '')) {
                    $is_available++;
                    session()->put('order_cart_array.'.$keys.'.product_quantity', Session::get('order_cart_array')[$keys]['product_quantity'] + $request->quantity);
                    $data = 'Success';
                }
            }
            if ($is_available == 0) {
                if (isset($request->barcode)) {
                    $productInfo = Product::where('deleted', 'No')->where('status', 'Active')->where('barcode_no', $request->barcode)->first();
                } elseif (isset($request->id)) {
                    $productInfo = Product::where('deleted', 'No')->where('status', 'Active')->where('id', $request->id)->first();
                }
                $isServiceProduct = false;
                if ($productInfo->type == 'service') {
                    $isServiceProduct = true;
                    $available_quantity = 0;
                } else {
                    $currentStockInfo = Currentstock::where('deleted', 'No')->where('tbl_productsId', $productInfo->id)->where('tbl_wareHouseId', $request->warehouseId)->first();
                    if ($currentStockInfo) {
                        $available_quantity = $currentStockInfo->currentStock;
                    } else {
                        $available_quantity = 0;
                    }
                }
                // if ($available_quantity > 0 || $isServiceProduct == TRUE) {
                if ($request->saleType == 'walkin_sale') {
                    $salePrice = $productInfo->sale_price; // Sale price is Max price
                } else {
                    $salePrice = $productInfo->purchase_price; // Sale price is Min price
                }
                if ($productInfo->discount == '') {
                    $productDiscount = 0;
                }
                $product_type = $productInfo->type;
                $serializeIdArray = [];
                $serializeSaleQtyArray = [];
                $item_array = [
                    'product_id' => $productInfo->id,
                    'product_name' => $productInfo->name.' - '.$productInfo->code,
                    'product_image' => $productInfo->image,
                    'available_qty' => $available_quantity,
                    'product_price' => $salePrice,
                    'product_quantity' => $request->quantity,
                    'product_discount' => $productDiscount,
                    'barcode_no' => $productInfo->barcode_no,
                    'warehouse_id' => $request->warehouseId,
                    'warehouse_name' => $request->warehouseName,
                    'product_type' => $productInfo->type,
                    'items_in_box' => $productInfo->items_in_box,
                    'serializeIdArray' => [$serializeIdArray],
                    'serializeSaleQtyArray' => [$serializeSaleQtyArray],
                ];
                Session::push('order_cart_array', $item_array);
                $data = 'Success';
                /*  } else {
                    $data = "This product is out of stock";
                } */
            }
        } else {
            $productInfo = [];
            if (isset($request->barcode)) {
                $productInfo = Product::where('deleted', 'No')->where('status', 'Active')->where('barcode_no', $request->barcode)->first();
            } elseif (isset($request->id)) {
                $productInfo = Product::where('deleted', 'No')->where('status', 'Active')->where('id', $request->id)->first();
            }
            $isServiceProduct = false;
            if ($productInfo->type == 'service') {
                $isServiceProduct = true;
                $available_quantity = 0;
            } else {
                $currentStockInfo = Currentstock::where('deleted', 'No')->where('tbl_productsId', $productInfo->id)->where('tbl_wareHouseId', $request->warehouseId)->first();
                if ($currentStockInfo) {
                    $available_quantity = $currentStockInfo->currentStock;
                } else {
                    $available_quantity = 0;
                }
            }
            // if ($available_quantity > 0 || $isServiceProduct == TRUE) {
            if ($request->saleType == 'walkin_sale') {
                $salePrice = $productInfo->sale_price; // Sale price is Max price
            } else {
                $salePrice = $productInfo->purchase_price; // Sale price is Min price
            }
            if ($productInfo->discount == '') {
                $productDiscount = 0;
            }
            $product_type = $productInfo->type;
            $serializeIdArray = [];
            $serializeSaleQtyArray = [];
            $item_array = [
                'product_id' => $productInfo->id,
                'product_name' => $productInfo->name.' - '.$productInfo->code,
                'product_image' => $productInfo->image,
                'available_qty' => $available_quantity,
                'product_price' => $salePrice,
                'product_quantity' => $request->quantity,
                'product_discount' => $productDiscount,
                'barcode_no' => $productInfo->barcode_no,
                'warehouse_id' => $request->warehouseId,
                'warehouse_name' => $request->warehouseName,
                'product_type' => $productInfo->type,
                'items_in_box' => $productInfo->items_in_box,
                'serializeIdArray' => [$serializeIdArray],
                'serializeSaleQtyArray' => [$request->quantity],
            ];
            Session::push('order_cart_array', $item_array);
            $data = 'Success';
            /* } else {
                $data = "This product is out of stock";
            } */
        }

        return response()->json(['data' => $data, 'productId' => $request->id, 'warehouseId' => $request->warehouseId, 'productType' => $product_type]);
    }

    public function fetchCart()
    {
        $grandTotal = 0;
        $cart = '';
        if (Session::get('order_cart_array') != null) {
            $i = 1;
            foreach (Session::get('order_cart_array') as $keys => $values) {
                $unitPrice = Session::get('order_cart_array')[$keys]['product_price'];
                $discount = Session::get('order_cart_array')[$keys]['product_discount'];
                $totalPrice = Session::get('order_cart_array')[$keys]['product_quantity'] * ($unitPrice - $discount);
                $productId = Session::get('order_cart_array')[$keys]['product_id'];
                $warehouseId = Session::get('order_cart_array')[$keys]['warehouse_id'];
                $productType = Session::get('order_cart_array')[$keys]['product_type'];
                if ($productType == 'serialize') {
                    $btn = '';
                } else {
                    $btn = '';
                }
                $cart .= '<tr><td>'.$i++.'
				            <input type="hidden" name="ids[]" id="id_'.$productId.'_'.$warehouseId.'" value="'.$productId.'" />
				            <input type="hidden" name="warehouseIds[]" id="warehouse_id_'.$productId.'_'.$warehouseId.'" value="'.$productId.'" />
				            <input type="hidden" name="productTypes[]" id="product_type_'.$productId.'_'.$warehouseId.'" value="'.$productType.'" />
				</td>'.
                    '<td>'.Session::get('order_cart_array')[$keys]['product_name'].'-'.$productType.' ['.Session::get('order_cart_array')[$keys]['warehouse_name'].']</td>'.
                    '<td class="text-center"><span class="text-center" id="available_qty_'.$productId.'_'.$warehouseId.'">'.Session::get('order_cart_array')[$keys]['available_qty'].'</span></td>'.
                    '<td class=""><input type="number" style="width: 80%;"  min="1" id="quantity_'.$productId.'_'.$warehouseId.'" name="quantity[]" class="text-center" onblur="loadCartandUpdate('.$productId.','.$warehouseId.')" value="'.Session::get('order_cart_array')[$keys]['product_quantity'].'" />'.$btn.'</td>'.
                    '<td><input type="number" style="width: 100%;" min="1" id="unitPrice_'.$productId.'_'.$warehouseId.'"  name="unitPrice[]" class="text-center"  onblur="loadCartandUpdate('.$productId.','.$warehouseId.')" value="'.$unitPrice.'"/></td>'.
                    '<td><input type="number" style="width: 100%;" min="0" id="discountPrice_'.$productId.'_'.$warehouseId.'"  name="discountPrice[]" class="text-center"  onblur="loadCartandUpdate('.$productId.','.$warehouseId.')" value="'.$discount.'" /></td>'.
                    '<td class="text-right"><span id="totalPrice_'.$productId.'_'.$warehouseId.'">'.$totalPrice.'</span></td>'.
                    '<td class="text-center"><a href="#" onclick="removeCartProduct('.Session::get('order_cart_array')[$keys]['product_id'].','.Session::get('order_cart_array')[$keys]['warehouse_id'].')" style="color:red;"><i class="fa fa-trash"> </i></a></td></tr>';
                $grandTotal += $totalPrice;
            }
        }

        $cart .= '<tr><td colspan="6" class="text-right" > Total Tk : </td><td class="text-right " id="grandTotal"> '.$grandTotal.'</td><td></td</tr>';
        $data = [
            'cart' => $cart,
            'totalAmount' => $grandTotal,
        ];

        return response()->json(['data' => $data]);
    }

    public function removeProduct(Request $request)
    {
        $id = $request->id;
        $warehouse_id = $request->warehouse_id;
        $data = '';
        $cartData = Session::get('order_cart_array');
        foreach (Session::get('order_cart_array') as $keys => $values) {
            if (Session::get('order_cart_array')[$keys]['product_id'] == $id && Session::get('order_cart_array')[$keys]['warehouse_id'] == $warehouse_id) {
                unset($cartData[$keys]);
                Session::put('order_cart_array', $cartData);
                $data = 'Success';
                break;
            }
        }
        $data = 'Success';

        return response()->json(['data' => $data]);
    }

    public function updateCart(Request $request)
    {

        if (Session::get('order_cart_array') != null) {
            foreach (Session::get('order_cart_array') as $keys => $values) {
                $product = Product::find($request->id);

                if (Session::get('order_cart_array')[$keys]['product_id'] == $request->id && Session::get('order_cart_array')[$keys]['warehouse_id'] == $request->warehouse_id) {
                    session()->put('order_cart_array.'.$keys.'.product_quantity', $request->quantity);
                    if ($product->purchase_price <= $request->unitPrice) {
                        session()->put('order_cart_array.'.$keys.'.product_price', $request->unitPrice);
                    } else {
                        return response()->json(['exceed' => 'exceeded']);
                    }
                    session()->put('order_cart_array.'.$keys.'.product_discount', $request->discount);
                    // Serialize Product
                    if (Session::get('order_cart_array')[$keys]['product_type'] == 'serialize') {
                        if ($request->has('product_type') && $request->product_type == true) {
                            $serializeId = $request->serializeProductsId;
                            $serializeSaleQty = $request->serializeSaleQuantity;
                            $serializeIdExist = true;
                            foreach (Session::get('order_cart_array')[$keys]['serializeIdArray'] as $key => $value) {
                                if ($value == $serializeId) {
                                    session()->put('order_cart_array.'.$keys.'.serializeSaleQtyArray.'.$key, $serializeSaleQty);
                                    $serializeIdExist = false;
                                }
                            }
                            if ($serializeIdExist) {
                                Session::push('order_cart_array.'.$keys.'.serializeIdArray', $serializeId);
                                Session::push('order_cart_array.'.$keys.'.serializeSaleQtyArray', $serializeSaleQty);
                            }
                        }
                    }
                    // End Serialize Product
                    $data = 'Success';
                    break;
                }
            }
        } else {
            $data = '';
        }

        return response()->json(['data' => $data]);
    }

    public function clearCart(Request $request)
    {
        Session::forget('order_cart_array');
        $data = 'Success';

        return $data;
    }

    public function checkOutCart(Request $request)
    {

        $request->validate([
            'partyPhoneNumber' => 'required',
            'customerName' => 'required',
            'quantity' => 'required',
            'brand' => 'required',
            'model' => 'required',
            'item' => 'required',
            'vat' => 'required',
            'ait' => 'required',
            'category' => 'required',
        ]);

        // Start Temporary Sale
        $saleType = $request->saleType;
        // End Temporary Sale
        DB::beginTransaction();
        try {
            $customerId = $request->customer_id;
            // If Customer Not Exist, Create New Customer
            if ($customerId == 0) {
                $partyType = 'Walkin_Customer';
                $maxCode = Party::where('party_type', $partyType)->where('deleted', 'No')->max('code');
                $maxCode++;
                $maxCode = str_pad($maxCode, 6, '0', STR_PAD_LEFT);
                $party = new Party;
                $party->name = $request->customerName;
                $party->code = $maxCode;
                $party->address = $request->customerAddress;
                $party->contact = $request->partyPhoneNumber;
                $party->alternate_contact = $request->partyPhoneNumber;
                $party->credit_limit = 0;
                $party->party_type = $partyType;
                $party->created_by = auth()->user()->id;
                $party->created_date = Carbon::now();
                $party->deleted = 'No';
                $party->save();
                $customerId = $party->id;
            }

            // Start Service Centre Product Sale
            $saleNo = SaleOrder::where('deleted', 'No')->max('sale_no');
            $saleNo++;
            $saleNo = str_pad($saleNo, 6, '0', STR_PAD_LEFT);
            $saleOrder = new SaleOrder;

            if ($request->status == '0') {

                $saleOrder->order_status = 'Pending';
            } else {
                $saleOrder->order_status = $request->status;
            }

            if ($request->status == 'Servicing') {
                $saleOrder->service_start_date = Carbon::now();
            }
            $saleOrder->service_note = $request->service_note;
            $saleOrder->customer_id = $customerId;
            $saleOrder->sale_no = $saleNo;
            $saleOrder->date = $request->date;
            $saleOrder->total_amount = floatval($request->totalAmount);
            $saleOrder->discount = floatval($request->discount);
            $saleOrder->carrying_cost = floatval($request->carrying_cost);
            $saleOrder->vat = floatval($request->vat);
            $saleOrder->ait = floatval($request->ait);
            $saleOrder->grand_total = floatval($request->grand_total);
            $saleOrder->advance_payment = floatval($request->current_payment);
            $saleOrder->previous_due = floatval($request->previous_due);
            $saleOrder->current_balance = floatval($request->current_balance);
            $saleOrder->total_price = floatval($request->totalPrice);
            $saleOrder->total_with_due = floatval($request->total_with_due);
            $saleOrder->current_payment = floatval($request->current_payment);
            $saleOrder->dues_amount = $request->totalDue;
            $saleOrder->created_by = auth()->user()->id;
            $saleOrder->created_date = Carbon::now();
            $saleOrder->sales_type = $saleType;
            $saleOrder->current_dues = $request->current_balance;
            $saleOrder->description = $request->defectReported;
            $saleOrder->work_approval_date = $request->workApprovalDate;
            $saleOrder->expected_delivery_date = $request->expectedDeliveryDate;
            $saleOrder->manufacturing_si_no = $request->manufacturingSiNo;
            $saleOrder->quantity = $request->quantity;
            $saleOrder->brand = $request->brand;
            $saleOrder->model = $request->model;
            $saleOrder->category = $request->category;
            $saleOrder->item = $request->item;
            $saleOrder->accessories_recieved = $request->accessoriesRecieved;
            $saleOrder->other_accessories = $request->otherAccessories;
            $saleOrder->save();
            $saleOrderId = $saleOrder->id;
            if (Session::has('order_cart_array')) {
                foreach (Session::get('order_cart_array') as $keys => $values) {
                    $product_id = Session::get('order_cart_array')[$keys]['product_id'];
                    $warehouse_id = Session::get('order_cart_array')[$keys]['warehouse_id'];
                    $product = Product::find($product_id);
                    $unit_id = $product->unit_id;
                    $unit_price = floatval(Session::get('order_cart_array')[$keys]['product_price']);
                    $discount_amount = floatval(Session::get('order_cart_array')[$keys]['product_discount']);
                    $quantity = Session::get('order_cart_array')[$keys]['product_quantity'];
                    // $product->increment('sale_quantity', $quantity);
                    // $product->decrement('current_stock', $quantity);
                    $salePrice = floatval($unit_price - $discount_amount);
                    $subtotal = floatval($salePrice * $quantity);
                    // $product->increment('total_sale_price', $subtotal);
                    $lot_no = SaleOrderProduct::where('deleted', 'No')->where('product_id', $product_id)->max('lot_no');
                    $lot_no++;
                    $saleOrderProduct = new SaleOrderProduct;
                    $saleOrderProduct->tbl_sale_orders_id = $saleOrderId;
                    $saleOrderProduct->product_id = $product_id;
                    $saleOrderProduct->warehouse_id = $warehouse_id;
                    $saleOrderProduct->unit_id = $unit_id;
                    $saleOrderProduct->unit_price = $unit_price;
                    $saleOrderProduct->unit_discount = $discount_amount;
                    $saleOrderProduct->sale_price = $salePrice;
                    $saleOrderProduct->quantity = $quantity;
                    $saleOrderProduct->lot_no = $lot_no;
                    $saleOrderProduct->subtotal = $subtotal;
                    $saleOrderProduct->created_by = auth()->user()->id;
                    $saleOrderProduct->created_date = Carbon::now();
                    $saleOrderProduct->save();
                }
            }
            if (floatval($request->current_payment) > 0) {

                $maxCode = PaymentVoucher::where('deleted', 'No')->max(DB::raw('cast(voucherNo AS decimal(6))'));
                $maxCode++;
                $maxCode = str_pad($maxCode, 6, '000000', STR_PAD_LEFT);
                $paymentVoucher = new PaymentVoucher;
                $paymentVoucher->party_id = $customerId;
                $paymentVoucher->voucherNo = $maxCode;
                $paymentVoucher->order_sale_id = $saleOrderId;
                $paymentVoucher->amount = floatval($request->current_payment);
                $paymentVoucher->payment_method = 'Cash';
                $paymentVoucher->paymentDate = Carbon::now()->format('Y-m-d');
                $paymentVoucher->type = 'Payment Received';
                $paymentVoucher->voucherType = 'PartySale';
                $paymentVoucher->voucherType = 'PartySale';
                $paymentVoucher->remarks = 'Party payment for saleOrder code: '.$saleOrder->sale_no.' payment: '.$saleOrder->grand_total;
                $paymentVoucher->entryBy = auth()->user()->id;
                $paymentVoucher->save();

                $voucher = new Voucher;
                $voucher->vendor_id = $customerId;
                $voucher->transaction_date = Carbon::now()->format('Y-m-d');
                $voucher->payment_method = 'Cash';
                $voucher->sale_order_id = $saleOrderId;
                $voucher->deleted = 'No';
                $voucher->status = 'Active';
                $voucher->created_by = Auth::user()->id;
                $voucher->created_date = date('Y-m-d h:s');
                $voucher->save();
                $voucherId = $voucher->id;

                $voucherDetails = new VoucherDetails;
                $voucherDetails->tbl_acc_voucher_id = $voucherId;
                $voucherDetails->tbl_acc_coa_id = $request->category;
                $voucherDetails->debit = floatval($request->current_payment);
                $voucherDetails->voucher_title = 'Service advance amount paid with Service Code '.$saleNo;
                $voucherDetails->deleted = 'No';
                $voucherDetails->status = 'Active';
                $voucherDetails->created_by = Auth::user()->id;
                $voucherDetails->created_date = date('Y-m-d h:s');
                $voucherDetails->save();
            }
            // End Service Centre Product Sale
            Session::forget('order_cart_array');
            DB::commit();

            return response()->json(['success' => 'Sale order saved successfully.', 'saleOrderId' => $saleOrderId]);
        } catch (Exception $e) {
            DB::rollBack();

            return response()->json(['error' => 'Sale order rollback!']);
        }
    }

    public function statusComplete(Request $request)
    {
        DB::beginTransaction();
        try {
            $saleOrder = SaleOrder::find($request->id);
            $saleOrder->order_status = 'ReadyToDeliverd';
            $saleOrder->updated_by = auth()->user()->id;
            $saleOrder->updated_date = Carbon::now();
            $saleOrder->ready_to_deliver_date = Carbon::now();
            $saleOrder->save();
            DB::commit();

            return response()->json(['success' => 'Status updated successfully.']);
        } catch (Exception $e) {
            DB::rollBack();

            return response()->json(['error' => 'Status updated rollBack!']);
        }
    }

    // ========== Start Edit Order Service ==========//
    public function editSaleOrder($id)
    {

        if (Session::has('saleOrderId') && Session::get('saleOrderId') != $id) {
            Session::forget('order_cart_array');
        }
        Session::put('saleOrderId', $id);
        $type = 'walkin_sale';
        $saleOrder = SaleOrder::find($id);
        $categories = Category::where('deleted', 'No')->where('status', 'Active')->get();
        $brands = Brand::where('deleted', 'No')->where('status', 'Active')->get();
        $products = Product::where('deleted', 'No')->where('status', 'Active')->get();
        $warehouses = Warehouse::where('deleted', 'No')->where('status', 'Active')->get();
        $customer = Party::select('id', 'name', 'contact', 'address', 'party_type')->where('id', $saleOrder->customer_id)->where('deleted', 'No')->where('status', 'Active')->first();
        $advance = PaymentVoucher::where('order_sale_id', '=', $id)->where('type', '=', 'Payment Received')->first();
        $coas = ChartOfAccounts::where('deleted', 'No')->where('status', 'Active')->where('parent_id', '=', '31')->get();
        if ($saleOrder->customer_id == 52) {
            $jafreeMatched = 'yes';
        } else {
            $jafreeMatched = 'no';
        }
        // dd($jafreeMatched);
        if (! Session::has('order_cart_array')) {
            $saleOrderProducts = DB::table('sale_order_products')
                ->join('sale_orders', 'sale_order_products.tbl_sale_orders_id', '=', 'sale_orders.id')
                ->join('tbl_warehouse', 'sale_order_products.warehouse_id', '=', 'tbl_warehouse.id')
                ->join('products', 'sale_order_products.product_id', '=', 'products.id')
                ->where([['sale_orders.id', '=', $id], ['sale_orders.deleted', '=', 'No'], ['sale_order_products.deleted', '=', 'No']])
                ->selectRaw(
                    'SUM(sale_order_products.quantity) as quantity,sale_order_products.id tbl_sale_order_productsId,sale_order_products.unit_price,sale_order_products.unit_discount,SUM(sale_order_products.subtotal) as subtotal,sale_orders.date,sale_orders.discount,sale_orders.total_amount,sale_orders.dues_amount,
             sale_orders.total_with_due,sale_orders.current_dues,sale_orders.previous_due,sale_orders.carrying_cost,sale_orders.grand_total,sale_orders.id,sale_orders.current_payment,sale_orders.sale_no,
             products.name,products.code as productCode,products.image,sale_order_products.warehouse_id,tbl_warehouse.wareHouseName,sale_order_products.product_id'
                )
                ->groupby(
                    'sale_order_products.id',
                    'sale_order_products.product_id',
                    'sale_order_products.warehouse_id',
                    'tbl_warehouse.wareHouseName',
                    'sale_order_products.unit_price',
                    'sale_order_products.unit_discount',
                    'sale_orders.id',
                    'sale_orders.date',
                    'sale_orders.discount',
                    'sale_orders.total_amount',
                    'sale_orders.dues_amount',
                    'sale_orders.total_with_due',
                    'sale_orders.current_dues',
                    'sale_orders.previous_due',
                    'sale_orders.carrying_cost',
                    'sale_orders.grand_total',
                    'sale_orders.current_payment',
                    'sale_orders.sale_no',
                    'sale_orders.project_name',
                    'products.name',
                    'products.code',
                    'products.image'
                )
                ->get();
            Session::forget('order_cart_array');
            foreach ($saleOrderProducts as $productInfo) {
                $serializeIdArray = [];
                $serializeSaleQtyArray = [];
                $item_array = [
                    'product_id' => $productInfo->product_id,
                    'tbl_sale_order_productsId' => $productInfo->tbl_sale_order_productsId,
                    'product_name' => $productInfo->name.' - '.$productInfo->productCode,
                    'product_image' => $productInfo->image,
                    'available_qty' => 0,
                    'product_price' => $productInfo->unit_price,
                    'product_quantity' => $productInfo->quantity,
                    'product_discount' => $productInfo->unit_discount,
                    'barcode_no' => $productInfo->productCode,
                    'warehouse_id' => $productInfo->warehouse_id,
                    'warehouse_name' => $productInfo->wareHouseName,
                    'product_type' => 'service',
                    'items_in_box' => 0,
                    'serializeIdArray' => [$serializeIdArray],
                    'serializeSaleQtyArray' => [$serializeSaleQtyArray],
                ];
                Session::push('order_cart_array', $item_array);
            }
        }

        return view('admin.inventory.service.edit-service-sale', compact('saleOrder', 'coas', 'jafreeMatched', 'advance', 'categories', 'brands', 'products', 'warehouses', 'customer', 'type'));
    }

    public function addToOrderEditCart(Request $request)
    {
        $data = '';
        $product_type = '';
        if (Session::get('order_cart_array') != null) {
            $is_available = 0;
            foreach (Session::get('order_cart_array') as $keys => $values) {
                if ((Session::get('order_cart_array')[$keys]['product_id'] == $request->id && Session::get('order_cart_array')[$keys]['warehouse_id'] == $request->warehouseId) || (Session::get('order_cart_array')[$keys]['barcode_no'] == $request->barcode && $request->barcode != '')) {
                    $is_available++;
                    session()->put('order_cart_array.'.$keys.'.product_quantity', Session::get('order_cart_array')[$keys]['product_quantity'] + $request->quantity);
                    $data = 'Success';
                }
            }

            if ($is_available == 0) {
                if (isset($request->barcode)) {
                    $productInfo = Product::where('deleted', 'No')->where('status', 'Active')->where('barcode_no', $request->barcode)->first();
                } elseif (isset($request->id)) {
                    $productInfo = Product::where('deleted', 'No')->where('status', 'Active')->where('id', $request->id)->first();
                }
                $isServiceProduct = false;
                if ($productInfo->type == 'service') {
                    $isServiceProduct = true;
                    $available_quantity = 0;
                } else {
                    $currentStockInfo = Currentstock::where('deleted', 'No')->where('tbl_productsId', $productInfo->id)->where('tbl_wareHouseId', $request->warehouseId)->first();
                    if ($currentStockInfo) {
                        $available_quantity = $currentStockInfo->currentStock;
                    } else {
                        $available_quantity = 0;
                    }
                }
                // if ($available_quantity > 0 || $isServiceProduct == TRUE) {
                if ($request->saleType == 'walkin_sale') {
                    $salePrice = $productInfo->sale_price; // Sale price as Max price
                } else {
                    $salePrice = $productInfo->purchase_price; // Sale price as Min price
                }
                if ($productInfo->discount == '') {
                    $productDiscount = 0;
                }
                $product_type = $productInfo->type;
                $serializeIdArray = [];
                $serializeSaleQtyArray = [];
                $item_array = [
                    'product_id' => $productInfo->id,
                    'tbl_sale_order_productsId' => 0,
                    'product_name' => $productInfo->name.' - '.$productInfo->code,
                    'product_image' => $productInfo->image,
                    'available_qty' => $available_quantity,
                    'product_price' => $salePrice,
                    'product_quantity' => $request->quantity,
                    'product_discount' => $productDiscount,
                    'barcode_no' => $productInfo->barcode_no,
                    'warehouse_id' => $request->warehouseId,
                    'warehouse_name' => $request->warehouseName,
                    'product_type' => $productInfo->type,
                    'items_in_box' => $productInfo->items_in_box,
                    'serializeIdArray' => [$serializeIdArray],
                    'serializeSaleQtyArray' => [$serializeSaleQtyArray],
                ];
                Session::push('order_cart_array', $item_array);
                $data = 'Success';
                /*  } else {
                    $data = "This product is out of stock!";
                } */
            }
        } else {
            $productInfo = [];
            if (isset($request->barcode)) {
                $productInfo = Product::where('deleted', 'No')->where('status', 'Active')->where('barcode_no', $request->barcode)->first();
            } elseif (isset($request->id)) {
                $productInfo = Product::where('deleted', 'No')->where('status', 'Active')->where('id', $request->id)->first();
            }
            $isServiceProduct = false;
            if ($productInfo->type == 'service') {
                $isServiceProduct = true;
                $available_quantity = 0;
            } else {
                $currentStockInfo = Currentstock::where('deleted', 'No')->where('tbl_productsId', $productInfo->id)->where('tbl_wareHouseId', $request->warehouseId)->first();
                if ($currentStockInfo) {
                    $available_quantity = $currentStockInfo->currentStock;
                } else {
                    $available_quantity = 0;
                }
            }
            // if ($available_quantity > 0 || $isServiceProduct == TRUE) {
            if ($request->saleType == 'walkin_sale') {
                $salePrice = $productInfo->sale_price; // Sale price is Max price
            } else {
                $salePrice = $productInfo->purchase_price; // Sale price is Min price
            }
            if ($productInfo->discount == '') {
                $productDiscount = 0;
            }
            $product_type = $productInfo->type;
            $serializeIdArray = [];
            $serializeSaleQtyArray = [];
            $item_array = [
                'product_id' => $productInfo->id,
                'tbl_sale_order_productsId' => 0,
                'product_name' => $productInfo->name.' - '.$productInfo->code,
                'product_image' => $productInfo->image,
                'available_qty' => $available_quantity,
                'product_price' => $salePrice,
                'product_quantity' => $request->quantity,
                'product_discount' => $productDiscount,
                'barcode_no' => $productInfo->barcode_no,
                'warehouse_id' => $request->warehouseId,
                'warehouse_name' => $request->warehouseName,
                'product_type' => $productInfo->type,
                'items_in_box' => $productInfo->items_in_box,
                'serializeIdArray' => [$serializeIdArray],
                'serializeSaleQtyArray' => [$request->quantity],
            ];
            Session::push('order_cart_array', $item_array);
            $data = 'Success';
            // }
        }

        return response()->json(['data' => $data, 'productId' => $request->id, 'warehouseId' => $request->warehouseId, 'productType' => $product_type]);
    }

    public function fetchOrderEditCart()
    {
        $grandTotal = 0;
        $cart = '';
        if (Session::get('order_cart_array') != null) {
            $i = 1;
            foreach (Session::get('order_cart_array') as $keys => $values) {
                $unitPrice = Session::get('order_cart_array')[$keys]['product_price'];
                $discount = Session::get('order_cart_array')[$keys]['product_discount'];
                $totalPrice = Session::get('order_cart_array')[$keys]['product_quantity'] * ($unitPrice - $discount);
                $productId = Session::get('order_cart_array')[$keys]['product_id'];
                $warehouseId = Session::get('order_cart_array')[$keys]['warehouse_id'];
                $productType = Session::get('order_cart_array')[$keys]['product_type'];
                $cart .= '<tr><td>'.$i++.'
				            <input type="hidden" name="ids[]" id="id_'.$productId.'_'.$warehouseId.'" value="'.$productId.'" />
				            <input type="hidden" name="warehouseIds[]" id="warehouse_id_'.$productId.'_'.$warehouseId.'" value="'.$productId.'" />
				            <input type="hidden" name="productTypes[]" id="product_type_'.$productId.'_'.$warehouseId.'" value="'.$productType.'" />
				</td>'.
                    '<td>'.Session::get('order_cart_array')[$keys]['product_name'].'-'.$productType.'  ['.Session::get('order_cart_array')[$keys]['warehouse_name'].']</td>'.
                    '<td class="text-center"><span class="text-center" id="available_qty_'.$productId.'_'.$warehouseId.'">'.Session::get('order_cart_array')[$keys]['available_qty'].'</span></td>'.
                    '<td class=""><input type="number" style="width: 80%;"  min="1" id="quantity_'.$productId.'_'.$warehouseId.'" name="quantity[]" class="text-center" onblur="loadCartandUpdate('.$productId.','.$warehouseId.')" value="'.Session::get('order_cart_array')[$keys]['product_quantity'].'" /></td>'.
                    '<td><input type="number" style="width: 100%;" min="1" id="unitPrice_'.$productId.'_'.$warehouseId.'"  name="unitPrice[]" class="text-center"  onblur="loadCartandUpdate('.$productId.','.$warehouseId.')" value="'.$unitPrice.'" /></td>'.
                    '<td><input type="number" style="width: 100%;" min="0" id="discountPrice_'.$productId.'_'.$warehouseId.'"  name="discountPrice[]" class="text-center"  onblur="loadCartandUpdate('.$productId.','.$warehouseId.')" value="'.$discount.'" /></td>'.
                    '<td class="text-right"><span id="totalPrice_'.$productId.'_'.$warehouseId.'">'.$totalPrice.'</span></td>'.
                    '<td class="text-center"><a href="#" onclick="removeCartProduct('.Session::get('order_cart_array')[$keys]['product_id'].','.Session::get('order_cart_array')[$keys]['warehouse_id'].','.Session::get('order_cart_array')[$keys]['tbl_sale_order_productsId'].')" style="color:red;"><i class="fa fa-trash"> </i></a></td></tr>';
                $grandTotal += $totalPrice;
            }
        }

        $cart .= '<tr><td colspan="6" class="text-right" > Total Tk : </td><td class="text-right " id="grandTotal"> '.$grandTotal.'</td><td></td</tr>';
        $data = [
            'cart' => $cart,
            'totalAmount' => $grandTotal,
        ];

        return response()->json(['data' => $data]);
    }

    public function removeOrderEditProduct(Request $request)
    {
        $id = $request->id;
        $warehouse_id = $request->warehouse_id;
        $tbl_sale_order_productsId = $request->tbl_sale_order_productsId;
        $data = '';
        $cartData = Session::get('order_cart_array');
        foreach (Session::get('order_cart_array') as $keys => $values) {
            if (Session::get('order_cart_array')[$keys]['product_id'] == $id && Session::get('order_cart_array')[$keys]['warehouse_id'] == $warehouse_id) {
                if ($tbl_sale_order_productsId > 0) {
                    SaleOrderProduct::where('id', $tbl_sale_order_productsId)
                        ->where('product_id', $id)
                        ->where('warehouse_id', $warehouse_id)
                        ->update(['deleted' => 'Yes', 'deleted_by' => auth()->user()->id, 'deleted_date' => Carbon::now()]);
                }

                unset($cartData[$keys]);
                Session::put('order_cart_array', $cartData);
                $data = 'Success';
                break;
            }
        }
        $data = 'Success';

        return response()->json(['data' => $data]);
    }

    public function updateOrderEditCart(Request $request)
    {
        if (Session::get('order_cart_array') != null) {
            foreach (Session::get('order_cart_array') as $keys => $values) {
                if (Session::get('order_cart_array')[$keys]['product_id'] == $request->id && Session::get('order_cart_array')[$keys]['warehouse_id'] == $request->warehouse_id) {
                    session()->put('order_cart_array.'.$keys.'.product_quantity', $request->quantity);
                    session()->put('order_cart_array.'.$keys.'.product_price', $request->unitPrice);
                    session()->put('order_cart_array.'.$keys.'.product_discount', $request->discount);
                    // Serialize Product
                    if (Session::get('order_cart_array')[$keys]['product_type'] == 'serialize') {
                        if ($request->has('product_type') && $request->product_type == true) {
                            $serializeId = $request->serializeProductsId;
                            $serializeSaleQty = $request->serializeSaleQuantity;
                            $serializeIdExist = true;
                            foreach (Session::get('order_cart_array')[$keys]['serializeIdArray'] as $key => $value) {
                                if ($value == $serializeId) {
                                    session()->put('order_cart_array.'.$keys.'.serializeSaleQtyArray.'.$key, $serializeSaleQty);
                                    $serializeIdExist = false;
                                }
                            }
                            if ($serializeIdExist) {
                                Session::push('order_cart_array.'.$keys.'.serializeIdArray', $serializeId);
                                Session::push('order_cart_array.'.$keys.'.serializeSaleQtyArray', $serializeSaleQty);
                            }
                        }
                    }
                    // End Serialize Product
                    $data = 'Success';
                    break;
                }
            }
        } else {
            $data = '';
        }

        return response()->json(['data' => $data]);
    }

    public function clearOrderEditCart(Request $request)
    {
        Session::forget('order_cart_array');
        $data = 'Success';

        return $data;
    }

    public function checkMinimumPrice(Request $request)
    {
        return $request->id;
    }

    public function updatOrderSale(Request $request)
    {

        if ($request->customer_id == 52) {
            $request->validate([
                'project_name' => 'required',
            ]);
        }
        $request->validate([
            'partyPhoneNumber' => 'required',
            'customerName' => 'required',
            'brand' => 'required',
            'model' => 'required',
            'item' => 'required',
        ]);
        $saleType = $request->saleType;
        DB::beginTransaction();
        try {
            /*   if($request->category == '1'){
                $configId=ChartOfAccounts::where('name','=','Power Tools Income')->first();
            }elseif(($request->category == '2')){
                $configId=ChartOfAccounts::where('name','=','Welding Machine Income')->first();
            }else{
                $configId=ChartOfAccounts::where('name','=','Sales')->first();
            } */

            $customerId = $request->customer_id;
            // Start Service Centre Product Sale
            $saleOrderId = $request->saleOrderId;
            $saleOrder = SaleOrder::find($saleOrderId);

            if ($request->status == '0') {
                $saleOrder->order_status = $saleOrder->order_status;
            } else {
                $saleOrder->order_status = $request->status;
                if ($saleOrder->order_status == 'Servicing') {
                    $saleOrder->service_start_date = Carbon::now();
                } elseif ($request->status == 'Delivered') {
                    $saleOrder->delivered_date = Carbon::now();
                }
            }
            $saleOrder->service_note = $request->service_note;
            $saleOrder->total_amount = floatval($request->total_amount);
            $saleOrder->discount = floatval($request->discount);
            $saleOrder->carrying_cost = floatval($request->carrying_cost);
            $saleOrder->vat = floatval($request->vat);
            $saleOrder->ait = floatval($request->ait);
            $saleOrder->grand_total = floatval($request->grand_total);
            $saleOrder->previous_due = floatval($request->previous_due);
            $saleOrder->current_balance = floatval($request->current_balance);
            $saleOrder->total_price = floatval($request->totalPrice);
            $saleOrder->total_with_due = floatval($request->total_with_due);
            $saleOrder->project_name = $request->project_name;

            if ($saleOrder->current_payment == '0') {
                $saleOrder->current_payment = floatval($request->current_payment);
            } else {
                $saleOrder->increment('current_payment', $request->current_payment);
            }

            if ($request->advance_payment == '0') {
                $saleOrder->advance_payment = floatval($request->current_payment);
            } else {

                $saleOrder->increment('advance_payment', $request->current_payment);
            }

            $saleOrder->dues_amount = $request->totalDue;
            $saleOrder->updated_by = auth()->user()->id;
            $saleOrder->updated_date = Carbon::now();
            $saleOrder->current_dues = $request->current_balance;
            $saleOrder->description = $request->defectReported;
            $saleOrder->work_approval_date = $request->workApprovalDate;
            $saleOrder->expected_delivery_date = $request->expectedDeliveryDate;
            $saleOrder->manufacturing_si_no = $request->manufacturingSiNo;
            $saleOrder->brand = $request->brand;
            $saleOrder->category = $request->category;
            $saleOrder->model = $request->model;
            $saleOrder->item = $request->item;
            $saleOrder->accessories_recieved = $request->accessoriesRecieved;
            $saleOrder->other_accessories = $request->otherAccessories;
            $saleOrder->save();
            if (Session::has('order_cart_array')) {
                foreach (Session::get('order_cart_array') as $keys => $values) {
                    $product_id = Session::get('order_cart_array')[$keys]['product_id'];
                    $warehouse_id = Session::get('order_cart_array')[$keys]['warehouse_id'];
                    $product = Product::find($product_id);

                    $unit_id = $product->unit_id;
                    $unit_price = floatval(Session::get('order_cart_array')[$keys]['product_price']);
                    $discount_amount = floatval(Session::get('order_cart_array')[$keys]['product_discount']);
                    $quantity = Session::get('order_cart_array')[$keys]['product_quantity'];
                    // $product->increment('sale_quantity', $quantity);
                    // $product->decrement('current_stock', $quantity);
                    $salePrice = floatval($unit_price - $discount_amount);
                    $subtotal = floatval($salePrice * $quantity);
                    // $product->increment('total_sale_price', $subtotal);

                    $lot_no = SaleOrderProduct::where('product_id', $product_id)->max('lot_no');
                    $lot_no++;
                    $saleOrderProduct = new SaleOrderProduct;
                    $saleOrderProduct->tbl_sale_orders_id = $saleOrderId;
                    $saleOrderProduct->product_id = $product_id;
                    $saleOrderProduct->warehouse_id = $warehouse_id;
                    $saleOrderProduct->unit_id = $unit_id;
                    $saleOrderProduct->unit_price = $unit_price;
                    $saleOrderProduct->unit_discount = $discount_amount;
                    $saleOrderProduct->sale_price = $salePrice;
                    $saleOrderProduct->quantity = $quantity;
                    $saleOrderProduct->lot_no = $lot_no;
                    $saleOrderProduct->subtotal = $subtotal;
                    $saleOrderProduct->created_by = auth()->user()->id;
                    $created_date = Carbon::now();
                    $saleOrderProduct->created_date = $created_date;
                    $saleOrderProduct->save();

                    $tbl_sale_order_productsId = Session::get('order_cart_array')[$keys]['tbl_sale_order_productsId'];
                    if ($tbl_sale_order_productsId > 0) {
                        SaleOrderProduct::where('id', $tbl_sale_order_productsId)
                            ->where('product_id', $product_id)
                            ->where('warehouse_id', $warehouse_id)
                            ->update(['deleted' => 'Yes', 'deleted_by' => auth()->user()->id, 'deleted_date' => Carbon::now()]);
                    }
                }
            }

            /* */
            if ($request->status == 'Delivered') {

                // return "deliverd";
                if (floatval($request->grand_total) > 0) {

                    $maxCode = PaymentVoucher::max(DB::raw('cast(voucherNo AS decimal(6))'));
                    $maxCode++;
                    $maxCode = str_pad($maxCode, 6, '000000', STR_PAD_LEFT);
                    $paymentVoucher = new PaymentVoucher;
                    $paymentVoucher->party_id = $customerId;
                    $paymentVoucher->voucherNo = $maxCode;
                    $paymentVoucher->order_sale_id = $saleOrder->id;
                    $paymentVoucher->amount = floatval($request->grand_total);
                    $paymentVoucher->payment_method = 'Cash';
                    $paymentVoucher->paymentDate = Carbon::now()->format('Y-m-d');
                    $paymentVoucher->type = 'Party Payable';
                    $paymentVoucher->voucherType = 'PartySale';
                    $paymentVoucher->voucherType = 'PartySale';
                    $paymentVoucher->remarks = 'Party Payable for saleOrder code: '.$saleOrder->sale_no.' payment: '.$saleOrder->grand_total;
                    $paymentVoucher->entryBy = auth()->user()->id;
                    $paymentVoucher->save();
                    if (floatval($request->current_payment) > 0) {
                        $maxCode = PaymentVoucher::max(DB::raw('cast(voucherNo AS decimal(6))'));
                        $maxCode++;
                        $maxCode = str_pad($maxCode, 6, '0', STR_PAD_LEFT);
                        $paymentVoucher = new PaymentVoucher;
                        $paymentVoucher->party_id = $customerId;
                        $paymentVoucher->voucherNo = $maxCode;
                        $paymentVoucher->order_sale_id = $saleOrder->id;
                        $paymentVoucher->amount = floatval($request->current_payment);
                        $paymentVoucher->payment_method = 'Cash';
                        $paymentVoucher->paymentDate = Carbon::now()->format('Y-m-d');
                        $paymentVoucher->type = 'Payment Received';
                        $paymentVoucher->voucherType = 'PartySale';
                        $paymentVoucher->remarks = 'Party payment for saleOrder code: '.$saleOrder->sale_no.' payment: '.$saleOrder->grand_total;
                        $paymentVoucher->entryBy = auth()->user()->id;
                        $paymentVoucher->save();
                    }
                }
                if (floatval($request->grand_total) > 0) {
                    $party = Party::find($customerId);
                    $party->current_due = $request->current_balance;
                    $party->save();
                }
                $voucherIdFind = Voucher::where('sale_order_id', '=', $saleOrderId)->first();

                if ($voucherIdFind == null) {
                    $voucher = new Voucher;
                    $voucher->vendor_id = $customerId;
                    $voucher->transaction_date = Carbon::now()->format('Y-m-d');
                    $voucher->sale_order_id = $saleOrderId;
                    $voucher->amount = floatval($request->grand_total);
                    $voucher->payment_method = 'Cash';
                    $voucher->deleted = 'No';
                    $voucher->status = 'Active';
                    $voucher->created_by = Auth::user()->id;
                    $voucher->created_date = date('Y-m-d h:s');
                    $voucher->save();
                    $voucherId = $voucher->id;
                } else {
                    $voucher = Voucher::find($voucherIdFind->id);
                    $voucher->amount = floatval($request->grand_total);
                    $voucher->sale_order_id = $saleOrderId;
                    $voucher->deleted = 'No';
                    $voucher->status = 'Active';
                    $voucher->last_updated_by = Auth::user()->id;
                    $voucher->updated_date = date('Y-m-d h:s');
                    $voucher->save();
                    $voucherId = $voucher->id;
                }

                $voucherDetails = new VoucherDetails;
                $voucherDetails->tbl_acc_voucher_id = $voucherId;
                $voucherDetails->tbl_acc_coa_id = $request->category;
                $voucherDetails->credit = floatval($request->grand_total);
                $voucherDetails->voucher_title = 'Service created with Service code '.$saleOrder->sale_no;
                $voucherDetails->deleted = 'No';
                $voucherDetails->status = 'Active';
                $voucherDetails->created_by = Auth::user()->id;
                $voucherDetails->created_date = date('Y-m-d H:i:s');
                $voucherDetails->save();

                if ($request->current_payment > 0) {
                    $voucherDetails = new VoucherDetails;
                    $voucherDetails->tbl_acc_voucher_id = $voucherId;
                    $voucherDetails->tbl_acc_coa_id = $request->category;
                    $voucherDetails->debit = floatval($request->current_payment);
                    $voucherDetails->voucher_title = 'Service Sale final amount paid with Service code '.$saleOrder->sale_no.' after delivery';
                    $voucherDetails->deleted = 'No';
                    $voucherDetails->status = 'Active';
                    $voucherDetails->created_by = Auth::user()->id;
                    $voucherDetails->created_date = date('Y-m-d h:s');
                    $voucherDetails->save();
                }
            } else {
                if (floatval($request->current_payment) > 0) {
                    // return "not deliverd";
                    $maxCode = PaymentVoucher::max(DB::raw('cast(voucherNo AS decimal(6))'));
                    $maxCode++;
                    $maxCode = str_pad($maxCode, 6, '000000', STR_PAD_LEFT);
                    $paymentVoucher = new PaymentVoucher;
                    $paymentVoucher->party_id = $customerId;
                    $paymentVoucher->voucherNo = $maxCode;
                    $paymentVoucher->order_sale_id = $saleOrderId;
                    $paymentVoucher->amount = floatval($request->current_payment);
                    $paymentVoucher->payment_method = 'Cash';
                    $paymentVoucher->paymentDate = Carbon::now()->format('Y-m-d');
                    $paymentVoucher->type = 'Payment Received';
                    $paymentVoucher->voucherType = 'PartySale';
                    $paymentVoucher->voucherType = 'PartySale';
                    $paymentVoucher->remarks = 'Party payment for saleOrder code: '.$saleOrder->sale_no.' payment: '.$saleOrder->grand_total;
                    $paymentVoucher->entryBy = auth()->user()->id;
                    $paymentVoucher->save();

                    $voucherIdfind = Voucher::where('sale_order_id', '=', $saleOrderId)->first();
                    if ($voucherIdfind == null) {
                        $voucher = new Voucher;
                        $voucher->vendor_id = $customerId;
                        $voucher->transaction_date = Carbon::now()->format('Y-m-d');
                        $voucher->payment_method = 'Cash';
                        $voucher->sale_order_id = $saleOrderId;
                        $voucher->deleted = 'No';
                        $voucher->status = 'Active';
                        $voucher->created_by = Auth::user()->id;
                        $voucher->created_date = date('Y-m-d h:s');
                        $voucher->save();
                        $voucherId = $voucher->id;
                    } else {
                        $voucherId = $voucherIdfind->id;
                    }

                    $voucherDetails = new VoucherDetails;
                    $voucherDetails->tbl_acc_voucher_id = $voucherId;
                    $voucherDetails->tbl_acc_coa_id = $request->category;
                    $voucherDetails->debit = floatval($request->current_payment);
                    $voucherDetails->voucher_title = 'Service advance amount paid with Service code '.$saleOrder->sale_no;
                    $voucherDetails->deleted = 'No';
                    $voucherDetails->status = 'Active';
                    $voucherDetails->created_by = Auth::user()->id;
                    $voucherDetails->created_date = date('Y-m-d h:s');
                    $voucherDetails->save();
                }
            }

            // End Service Centre Product Sale
            Session::forget('order_cart_array');
            Session::forget('saleOrderId');
            DB::commit();

            return response()->json(['success' => 'sale order updated successfully.', 'saleOrderId' => $saleOrderId]);
        } catch (Exception $e) {
            DB::rollBack();

            return response()->json(['error' => 'sale order updated rollback!']);
        }
    }

    // === Start Order Feedbacks ===//
    public function getOrderFeedbacks(Request $request)
    {
        $saleOrderId = $request->id;
        $saleOrderFeedbacks = SaleOrder::find($saleOrderId)->saleOrderFeedbacks()
            ->select('id', 'date_of_contact', 'customer_response')
            ->where('deleted', 'No')
            ->get();
        $rows = '';
        $i = 1;
        foreach ($saleOrderFeedbacks as $saleOrderFeedback) {
            $rows .= '<tr><td>'.$i++.'</td><td>'.$saleOrderFeedback->date_of_contact.'</td>
                <td>'.$saleOrderFeedback->customer_response.'</td>
                    <td><a href="#" onclick="removeOrderFeedback('.$saleOrderFeedback->id.')" style="color:red;"><i class="fa fa-trash"></i></a></td>
                </td>
            </tr>';
        }

        return response()->json(['orderFeedbackTable' => $rows]);
    }

    public function addOrderFeedback(Request $request)
    {
        $request->validate([
            'saleOrderId' => 'required',
            'dateOfContact' => 'required',
            'customerResponse' => 'required',
        ]);
        $saleOrderFeedback = new SaleOrderFeedback;
        $saleOrderFeedback->tbl_sale_orders_id = $request->saleOrderId;
        $saleOrderFeedback->date_of_contact = $request->dateOfContact;
        $saleOrderFeedback->customer_response = $request->customerResponse;
        $saleOrderFeedback->created_by = auth()->user()->id;
        $saleOrderFeedback->created_date = Carbon::now();
        $saleOrderFeedback->save();

        return response()->json(['data' => 'Success']);
    }

    public function removeOrderFeedback(Request $request)
    {
        $saleOrderId = $request->id;
        $saleOrderFeedback = SaleOrderFeedback::find($saleOrderId);
        $saleOrderFeedback->deleted = 'Yes';
        $saleOrderFeedback->deleted_by = auth()->user()->id;
        $saleOrderFeedback->deleted_date = Carbon::now();
        $saleOrderFeedback->save();

        return response()->json(['data' => 'Success']);
    }

    public function createOrderToWalkinSale($id)
    {
        $saleOrderProducts = DB::table('sale_order_products')
            ->join('sale_orders', 'sale_order_products.tbl_sale_orders_id', '=', 'sale_orders.id')
            ->join('tbl_warehouse', 'sale_order_products.warehouse_id', '=', 'tbl_warehouse.id')
            ->join('products', 'sale_order_products.product_id', '=', 'products.id')
            ->where([['sale_orders.id', '=', $id], ['sale_orders.deleted', '=', 'No'], ['sale_order_products.deleted', '=', 'No'], ['sale_orders.order_status', '=', 'Delivered']])
            ->selectRaw(
                'SUM(sale_order_products.quantity) as quantity,sale_order_products.id tbl_sale_order_productsId,sale_order_products.unit_price,sale_order_products.unit_discount,SUM(sale_order_products.subtotal) as subtotal,sale_orders.date,sale_orders.discount,sale_orders.total_amount,sale_orders.dues_amount,
             sale_orders.total_with_due,sale_orders.current_dues,sale_orders.previous_due,sale_orders.carrying_cost,sale_orders.grand_total,sale_orders.id,sale_orders.current_payment,sale_orders.sale_no,
             products.name,products.purchase_price,products.type,products.code as productCode,products.image,sale_order_products.warehouse_id,tbl_warehouse.wareHouseName,sale_order_products.product_id'
            )
            ->groupby(
                'sale_order_products.id',
                'sale_order_products.product_id',
                'sale_order_products.warehouse_id',
                'tbl_warehouse.wareHouseName',
                'sale_order_products.unit_price',
                'sale_order_products.unit_discount',
                'sale_orders.id',
                'sale_orders.date',
                'sale_orders.discount',
                'sale_orders.total_amount',
                'sale_orders.dues_amount',
                'sale_orders.total_with_due',
                'sale_orders.current_dues',
                'sale_orders.previous_due',
                'sale_orders.carrying_cost',
                'sale_orders.grand_total',
                'sale_orders.current_payment',
                'sale_orders.sale_no',
                'products.name',
                'products.type',
                'products.code',
                'products.image',
                'products.purchase_price'
            )
            ->get();

        if (Session::has('sale_cart_array') && count(Session::get('sale_cart_array')) > 0) {
            Session::forget('sale_cart_array');
        }
        foreach ($saleOrderProducts as $productInfo) {
            $serializeIdArray = [];
            $serializeSaleQtyArray = [];
            $currentStockInfo = Currentstock::where('deleted', 'No')->where('tbl_productsId', $productInfo->product_id)->where('tbl_wareHouseId', $productInfo->warehouse_id)->first();
            if ($currentStockInfo) {
                $available_quantity = $currentStockInfo->currentStock;
            } else {
                $available_quantity = 0;
            }
            $item_array = [
                'product_id' => $productInfo->product_id,
                'tbl_sale_order_productsId' => $productInfo->tbl_sale_order_productsId,
                'product_name' => $productInfo->name.' - '.$productInfo->productCode,
                'product_image' => $productInfo->image,
                'available_qty' => $available_quantity,
                'product_price' => $productInfo->purchase_price,
                'product_quantity' => $productInfo->quantity,
                'product_discount' => $productInfo->unit_discount,
                'barcode_no' => $productInfo->productCode,
                'warehouse_id' => $productInfo->warehouse_id,
                'warehouse_name' => $productInfo->wareHouseName,
                'product_type' => $productInfo->type,
                'items_in_box' => 0,
                'serializeIdArray' => [$serializeIdArray],
                'serializeSaleQtyArray' => [$serializeSaleQtyArray],
            ];
            Session::push('sale_cart_array', $item_array);
        }
        $categories = Category::where('deleted', 'No')->where('status', 'Active')->get();
        $brands = Brand::where('deleted', 'No')->where('status', 'Active')->get();
        $products = Product::where('deleted', 'No')->where('status', 'Active')->get();
        $warehouses = Warehouse::where('deleted', 'No')->where('status', 'Active')->get();
        $type = 'walkin_sale';
        $saleOrder = SaleOrder::find($id);
        // dd($saleOrder);
        if ($saleOrder->order_status == 'Delivered') {
            return view('admin.inventory.service.createOrderToWalkinSale', compact('categories', 'brands', 'products', 'warehouses', 'type', 'saleOrder'));
        } else {
            return redirect()->route('sale.service.SaleOrders');
        }
    }

    public function completeOrderCheckOutCart(Request $request)
    {
        $request->validate([
            'partyPhoneNumber' => 'required',
            'customerName' => 'required',
        ]);

        $saleType = $request->saleType;
        DB::beginTransaction();
        try {
            $customerId = $request->customer_id;
            $saleNo = Sale::where('sales_type', $saleType)->max('sale_no');
            $saleNo++;
            $saleNo = str_pad($saleNo, 6, '0', STR_PAD_LEFT);
            $sale = new Sale;
            $sale->customer_id = $customerId;
            $sale->sale_no = $saleNo;
            $sale->tbl_sale_order_id = $request->saleOrderId;
            $sale->date = $request->date;
            $sale->total_amount = floatval($request->total_amount);
            $sale->discount = floatval($request->discount);
            $sale->carrying_cost = floatval($request->carrying_cost);
            $sale->vat = floatval($request->vat);
            $sale->ait = floatval($request->ait);
            $sale->grand_total = floatval($request->grand_total);
            $sale->previous_due = floatval($request->previous_due);
            $sale->current_balance = floatval($request->current_balance);
            $sale->total_price = floatval($request->totalPrice);
            $sale->total_with_due = floatval($request->total_with_due);
            $sale->current_payment = floatval($request->current_payment);
            $sale->dues_amount = $request->totalDue;
            $sale->no_of_tenure = $request->noOfTenure;
            $sale->type = 'service';
            $sale->start_date = $request->startDate;
            $sale->created_by = auth()->user()->id;
            $sale->created_date = Carbon::now();
            $sale->sales_type = $saleType;
            $sale->current_dues = $request->current_balance;
            if (intval($request->noOfTenure) > 0) {
                $sale->emi_status = 'Yes';
            }
            $sale->save();
            $sale_id = $sale->id;

            if (Session::has('sale_cart_array') && count(Session::get('sale_cart_array')) > 0) {
                foreach (Session::get('sale_cart_array') as $keys => $values) {
                    $product_id = Session::get('sale_cart_array')[$keys]['product_id'];
                    $warehouse_id = Session::get('sale_cart_array')[$keys]['warehouse_id'];
                    $quantity = Session::get('sale_cart_array')[$keys]['product_quantity'];

                    $checkCurrentstock = Currentstock::where('tbl_productsId', $product_id)->first();
                    if ($checkCurrentstock != '') {
                        if ($quantity > $checkCurrentstock->currentStock) {
                            DB::rollBack();

                            return response()->json(['mismatch' => 'NotAvailable']);
                        }
                    }

                    $product = Product::find($product_id);
                    $unit_id = $product->unit_id;
                    $unit_price = floatval(Session::get('sale_cart_array')[$keys]['product_price']);
                    $discount_amount = floatval(Session::get('sale_cart_array')[$keys]['product_discount']);
                    $product->increment('sale_quantity', $quantity);
                    if (Session::get('sale_cart_array')[$keys]['product_type'] != 'service') {
                        $product->decrement('current_stock', $quantity);
                    }
                    $salePrice = floatval($unit_price - $discount_amount);
                    $subtotal = floatval($salePrice * $quantity);
                    $product->increment('total_sale_price', $subtotal);
                    $lot_no = SaleProduct::where('product_id', $product_id)->max('lot_no');
                    $lot_no++;
                    $sale_products = new SaleProduct;
                    $sale_products->sale_id = $sale_id;
                    $sale_products->product_id = $product_id;
                    $sale_products->warehouse_id = $warehouse_id;
                    $sale_products->unit_id = $unit_id;
                    $sale_products->unit_price = $unit_price;
                    $sale_products->unit_discount = $discount_amount;
                    $sale_products->sale_price = $salePrice;
                    $sale_products->quantity = $quantity;
                    $sale_products->lot_no = $lot_no;
                    $sale_products->subtotal = $subtotal;
                    $sale_products->created_by = auth()->user()->id;
                    $sale_products->created_date = Carbon::now();
                    $sale_products->save();

                    // Serialize Product
                    if (Session::get('sale_cart_array')[$keys]['product_type'] == 'serialize') {
                        $quantity = 0;
                        foreach (Session::get('sale_cart_array')[$keys]['serializeIdArray'] as $key => $serializeId) {
                            $serializeSaleQtyArray = Session::get('sale_cart_array')[$keys]['serializeSaleQtyArray'];
                            if (empty($serializeId)) {
                                continue;
                            }
                            $serializeProduct = SerializeProduct::find($serializeId);
                            if ($serializeProduct) {
                                $totalSerializeQuantity = ($serializeProduct->used_quantity + $serializeSaleQtyArray[$key]);
                                $serializeProduct->used_quantity = $totalSerializeQuantity;
                                if ($serializeProduct->quantity == $totalSerializeQuantity) {
                                    $serializeProduct->is_sold = 'OFF';
                                }
                                $serializeProduct->save();
                                $quantity += $serializeSaleQtyArray[$key];

                                $saleSerializeProduct = new SaleSerializeProduct;
                                $saleSerializeProduct->sale_id = $sale_id;
                                $saleSerializeProduct->product_id = $product_id;
                                $saleSerializeProduct->warehouse_id = $warehouse_id;
                                $saleSerializeProduct->sale_quantity = $serializeSaleQtyArray[$key];
                                $saleSerializeProduct->tbl_serialize_products_id = $serializeId;
                                $saleSerializeProduct->created_by = auth()->user()->id;
                                $saleSerializeProduct->created_date = Carbon::now();
                                $saleSerializeProduct->save();
                            }
                        }
                    } // End Serialize Product
                    if (Session::get('sale_cart_array')[$keys]['product_type'] == 'service') {
                        continue;
                    }
                    $Currentstock = Currentstock::where('tbl_productsId', $product_id)
                        ->where('tbl_wareHouseId', $warehouse_id)
                        ->where('deleted', 'No');
                    if ($Currentstock->first()) {
                        $Currentstock->decrement('currentStock', $quantity);
                        $Currentstock->increment('salesStock', $quantity);
                    } else {
                        $Currentstock_insert = new Currentstock;
                        $Currentstock_insert->tbl_productsId = $product_id;
                        $Currentstock_insert->tbl_wareHouseId = $warehouse_id;
                        $Currentstock_insert->currentStock = -$quantity;
                        $Currentstock_insert->salesStock = $quantity;
                        $Currentstock_insert->entryBy = auth()->user()->id;
                        $Currentstock_insert->entryDate = date('Y-m-d H:i:s');
                        $Currentstock_insert->save();
                    }
                }
            }
            // Update Order Sale
            $saleOrder = SaleOrder::find($request->saleOrderId);
            $saleOrder->order_status = 'Completed';
            $saleOrder->sale_status = 'Completed';
            $saleOrder->final_sale_amount = $request->grand_total;
            $saleOrder->sale_id = $sale_id;
            $saleOrder->completed_date = date('Y-m-d');
            $saleOrder->updated_by = auth()->user()->id;
            $saleOrder->updated_date = Carbon::now();
            $saleOrder->save();

            /*    if (floatval($request->grand_total) > 0) {
                $party = Party::find( $customerId);
                $party->current_due = $request->current_balance;
                $party->save();
            } */
            /* */

            Session::forget('sale_cart_array');
            DB::commit();

            return response()->json(['success' => 'Sale saved successfully.', 'saleId' => $sale_id]);
        } catch (Exception $e) {
            DB::rollBack();

            return response()->json(['error' => 'sale  rollback!']);
        }
    }

    public function orderInvoice($id)
    {
        $invoice = DB::table('sale_orders')
            ->leftJoin('sale_order_products', 'sale_orders.id', '=', 'sale_order_products.tbl_sale_orders_id')
            ->join('parties', 'sale_orders.customer_id', '=', 'parties.id')
            ->leftJoin('products', 'sale_order_products.product_id', '=', 'products.id')
            ->join('users', 'sale_orders.created_by', '=', 'users.id')
            ->where([['sale_orders.id', '=', $id], ['sale_orders.deleted', '=', 'No']])
            ->selectRaw(
                'SUM(sale_order_products.quantity) as quantity,sale_order_products.unit_price,sale_order_products.unit_discount,SUM(sale_order_products.subtotal) as subtotal,users.name as entryBy,sale_orders.date,sale_orders.discount,sale_orders.total_amount,sale_orders.dues_amount,
				sale_orders.total_with_due,sale_orders.current_dues,sale_orders.previous_due,sale_orders.carrying_cost,sale_orders.vat,sale_orders.ait,sale_orders.grand_total,sale_orders.id,sale_orders.current_payment,sale_orders.sale_no,
                sale_orders.description,
                sale_orders.work_approval_date,
                sale_orders.expected_delivery_date,
                sale_orders.quantity as saleOrderQty,
                sale_orders.brand,
                sale_orders.category,
                sale_orders.model,
                sale_orders.item,
                parties.contact,parties.name as customerName,parties.code,parties.address,
                products.name,products.code as productCode,products.image,sale_order_products.product_id'
            )
            ->groupby(
                'sale_order_products.product_id',
                'sale_order_products.unit_price',
                'sale_order_products.unit_discount',
                'users.name',
                'sale_orders.id',
                'sale_orders.date',
                'sale_orders.discount',
                'sale_orders.total_amount',
                'sale_orders.dues_amount',
                'sale_orders.total_with_due',
                'sale_orders.current_dues',
                'sale_orders.previous_due',
                'sale_orders.carrying_cost',
                'sale_orders.vat',
                'sale_orders.ait',
                'sale_orders.grand_total',
                'sale_orders.current_payment',
                'sale_orders.sale_no',
                'sale_orders.description',
                'sale_orders.work_approval_date',
                'sale_orders.expected_delivery_date',
                'sale_orders.quantity',
                'sale_orders.brand',
                'sale_orders.category',
                'sale_orders.model',
                'sale_orders.item',
                'parties.contact',
                'parties.name',
                'parties.code',
                'parties.address',
                'products.name',
                'products.code',
                'products.image'
            )
            ->get();

        $saleOrderFeedbacks = SaleOrder::find($id)->saleOrderFeedbacks()
            ->select('date_of_contact', 'customer_response')
            ->where('deleted', 'No')
            ->get();
        $saleOrders = DB::table('sale_orders')
            ->where('id', $id)
            ->first();
        $pdf = PDF::loadView('admin.inventory.service.sale-service-order-invoice', ['invoice' => $invoice, 'saleOrders' => $saleOrders, 'saleOrderFeedbacks' => $saleOrderFeedbacks]);

        return $pdf->stream('sale-report-pdf.pdf', ['Attachment' => false]);
    }

    public function completeInvoice($id)
    {
        $invoice = DB::table('sale_orders')
            ->leftJoin('sale_order_products', 'sale_orders.id', '=', 'sale_order_products.tbl_sale_orders_id')
            ->join('parties', 'sale_orders.customer_id', '=', 'parties.id')
            ->leftJoin('products', 'sale_order_products.product_id', '=', 'products.id')
            ->join('users', 'sale_orders.created_by', '=', 'users.id')
            ->where([['sale_orders.id', '=', $id], ['sale_orders.deleted', '=', 'No'], ['sale_order_products.deleted', '=', 'No']])
            ->selectRaw(
                'SUM(sale_order_products.quantity) as quantity,sale_order_products.unit_price,sale_order_products.unit_discount,SUM(sale_order_products.subtotal) as subtotal,users.name as entryBy,sale_orders.date,sale_orders.discount,sale_orders.total_amount,sale_orders.dues_amount,
				sale_orders.total_with_due,sale_orders.current_dues,sale_orders.previous_due,sale_orders.carrying_cost,sale_orders.vat,sale_orders.ait,sale_orders.grand_total,sale_orders.id,sale_orders.current_payment,sale_orders.sale_no,
                sale_orders.description,
                sale_orders.work_approval_date,
                sale_orders.expected_delivery_date,
                sale_orders.quantity as saleOrderQty,
                sale_orders.brand,
                sale_orders.category,
                sale_orders.model,
                sale_orders.item,
                parties.contact,parties.name as customerName,parties.code,parties.address,
                products.name,products.code as productCode,products.image,sale_order_products.product_id'
            )
            ->groupby(
                'sale_order_products.product_id',
                'sale_order_products.unit_price',
                'sale_order_products.unit_discount',
                'users.name',
                'sale_orders.id',
                'sale_orders.date',
                'sale_orders.discount',
                'sale_orders.total_amount',
                'sale_orders.dues_amount',
                'sale_orders.total_with_due',
                'sale_orders.current_dues',
                'sale_orders.previous_due',
                'sale_orders.carrying_cost',
                'sale_orders.vat',
                'sale_orders.ait',
                'sale_orders.grand_total',
                'sale_orders.current_payment',
                'sale_orders.sale_no',
                'sale_orders.description',
                'sale_orders.work_approval_date',
                'sale_orders.expected_delivery_date',
                'sale_orders.quantity',
                'sale_orders.brand',
                'sale_orders.category',
                'sale_orders.model',
                'sale_orders.item',
                'sale_orders.project_name',
                'parties.contact',
                'parties.name',
                'parties.code',
                'parties.address',
                'products.name',
                'products.code',
                'products.image'
            )->get();

        $saleOrderFeedbacks = SaleOrderFeedback::select('date_of_contact', 'customer_response')
            ->where('tbl_sale_orders_id', '=', $id)
            ->where('deleted', 'No')
            ->get();

        $saleOrders = DB::table('sale_orders')
            ->join('parties', 'sale_orders.customer_id', '=', 'parties.id')
            ->join('users', 'sale_orders.created_by', '=', 'users.id')
            ->where('sale_orders.id', $id)
            ->select(
                'sale_orders.*',
                'parties.name',
                'parties.code',
                'parties.address',
                'parties.contact',
                'users.name as entry_by'
            )->first();

        $payments = PaymentVoucher::where('deleted', '=', 'No')
            ->where('status', '=', 'Active')
            ->where('order_sale_id', '=', $id)
            ->where('type', '=', 'Payment Received')
            ->get();

        $pdf = PDF::loadView('admin.inventory.service.sale-service-completeInvoice', ['invoice' => $invoice, 'saleOrders' => $saleOrders, 'saleOrderFeedbacks' => $saleOrderFeedbacks, 'payments' => $payments]);

        return $pdf->stream('Service-center-report-pdf.pdf', ['Attachment' => false]);
    }
}
