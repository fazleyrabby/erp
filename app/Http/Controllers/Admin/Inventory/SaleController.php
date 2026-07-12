<?php

namespace App\Http\Controllers\Admin\Inventory;

use App\Http\Controllers\Controller;
use App\Http\Requests\CheckOutCartRequest;
use App\Models\Accounts\ChartOfAccounts;
use App\Models\Accounts\Voucher;
use App\Models\Accounts\VoucherDetails;
use App\Models\inventory\Brand;
use App\Models\inventory\Category;
use App\Models\inventory\Currentstock;
use App\Models\inventory\Emi_sale;
use App\Models\inventory\Party;
use App\Models\inventory\PaymentVoucher;
use App\Models\inventory\Product;
use App\Models\inventory\Sale;
use App\Models\inventory\SaleOrder;
use App\Models\inventory\SaleProduct;
use App\Models\inventory\SaleReturn;
use App\Models\inventory\SaleSerializeProduct;
use App\Models\inventory\SerializeProduct;
use App\Models\inventory\Warehouse;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use PDF;

class SaleController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:sale.view', ['only' => ['viewSales', 'getSale']]);
        $this->middleware('permission:sale.add', ['only' => ['add', 'checkOutCart']]);
        $this->middleware('permission:sale.delete', ['only' => ['delete']]);
    }

    public function viewSales($type)
    {
        return $this->getSale($type, request());
    }

    public function add($type)
    {
        $categories = Category::where('deleted', 'No')->where('status', 'Active')->get();
        $brands = Brand::where('deleted', 'No')->where('status', 'Active')->get();
        $products = Product::where('deleted', 'No')->where('status', 'Active')->where('type', '!=', 'service')->get();
        $warehouses = Warehouse::where('deleted', 'No')->where('status', 'Active')->get();
        $customers = Party::where('deleted', 'No')->where('status', 'Active')->whereIn('party_type', ['Customer', 'Both'])->get();
        $coas = ChartOfAccounts::where('deleted', 'No')->where('status', 'Active')->where('parent_id', '=', '31')->get();

        return view('admin.inventory.sale.add-sale', compact('categories', 'brands', 'products', 'warehouses', 'customers', 'type', 'coas'));
    }

    public function supplierDue(Request $request)
    {
        if ($request->id > 0) {
            $customerInfo = Party::where('id', $request->id)->whereIn('party_type', [$request->customer_type, 'Both'])->first();
        } else {
            $customerInfo = Party::where('contact', '=', $request->partyPhoneNumber)->whereIn('party_type', [$request->customer_type, 'Both'])->where('deleted', '=', 'No')->first();
        }

        return $customerInfo;
    }

    public function getSale($type, Request $request)
    {
        if ($type == 'ts') {
            $query = DB::table('tbl_temporary_sale')
                ->join('parties', 'tbl_temporary_sale.tbl_customerId', '=', 'parties.id')
                ->leftjoin('users', 'tbl_temporary_sale.tbl_userId', '=', 'users.id')
                ->select(
                    'tbl_temporary_sale.tsNo',
                    'tbl_temporary_sale.tSalesDate as date',
                    'tbl_temporary_sale.id',
                    'parties.name',
                    'parties.code',
                    'parties.address',
                    'parties.contact',
                    'parties.alternate_contact',
                    'users.name as user_name'
                )
                ->where('tbl_temporary_sale.deleted', 'No');

            if ($search = $request->q) {
                $query->where(function ($q) use ($search) {
                    $q->where('tbl_temporary_sale.tsNo', 'like', "%{$search}%")
                        ->orWhere('parties.name', 'like', "%{$search}%");
                });
            }

            $sortBy = $request->sort_by ?? 'tbl_temporary_sale.id';
            $sortDir = $request->sort_direction ?? 'DESC';
            $limit = $request->limit ?? 10;

            $data['sales'] = $query->orderBy($sortBy, $sortDir)->paginate($limit)->appends($request->all());
            $data['saleType'] = $type;

            return view('admin.inventory.sale.view-sale', $data);
        } elseif ($type == 'walkin_sale') {
            $query = DB::table('sales')
                ->join('parties', 'sales.customer_id', '=', 'parties.id')
                ->leftjoin('users', 'users.id', '=', 'sales.created_by')
                ->select(
                    'sales.sale_no',
                    'sales.created_date',
                    'sales.sales_type as type',
                    'sales.total_amount',
                    'sales.current_payment',
                    'sales.discount',
                    'sales.carrying_cost',
                    'sales.vat',
                    'sales.ait',
                    'sales.id',
                    'sales.status as saleStatus',
                    'sales.grand_total',
                    'parties.name',
                    'parties.code',
                    'parties.address',
                    'parties.contact',
                    'parties.alternate_contact',
                    'users.name as userName'
                )
                ->where('sales.sales_type', 'walkin_sale')
                ->where('sales.deleted', 'No');

            if ($search = $request->q) {
                $query->where(function ($q) use ($search) {
                    $q->where('sales.sale_no', 'like', "%{$search}%")
                        ->orWhere('parties.name', 'like', "%{$search}%");
                });
            }

            $sortBy = $request->sort_by ?? 'sales.id';
            $sortDir = $request->sort_direction ?? 'DESC';
            $limit = $request->limit ?? 10;

            $data['sales'] = $query->orderBy($sortBy, $sortDir)->paginate($limit)->appends($request->all());
            $data['saleType'] = $type;

            return view('admin.inventory.sale.view-sale', $data);
        } elseif ($type == 'service') {
            $query = DB::table('sales')
                ->join('parties', 'sales.customer_id', '=', 'parties.id')
                ->leftjoin('users', 'users.id', '=', 'sales.created_by')
                ->select(
                    'sales.sale_no',
                    'sales.created_date',
                    'sales.sales_type as type',
                    'sales.total_amount',
                    'sales.current_payment',
                    'sales.discount',
                    'sales.carrying_cost',
                    'sales.vat',
                    'sales.ait',
                    'sales.id',
                    'sales.status as saleStatus',
                    'sales.grand_total',
                    'parties.name',
                    'parties.code',
                    'parties.address',
                    'parties.contact',
                    'parties.alternate_contact',
                    'users.name as userName'
                )
                ->where('sales.sales_type', 'walkin_sale')
                ->where('sales.deleted', 'No');

            if ($search = $request->q) {
                $query->where(function ($q) use ($search) {
                    $q->where('sales.sale_no', 'like', "%{$search}%")
                        ->orWhere('parties.name', 'like', "%{$search}%");
                });
            }

            $sortBy = $request->sort_by ?? 'sales.id';
            $sortDir = $request->sort_direction ?? 'DESC';
            $limit = $request->limit ?? 10;

            $data['sales'] = $query->orderBy($sortBy, $sortDir)->paginate($limit)->appends($request->all());
            $data['saleType'] = $type;

            return view('admin.inventory.sale.view-sale', $data);
        }
    }

    public function addToCart(Request $request)
    {
        $data = '';
        $product_type = '';
        if (Session::get('sale_cart_array') != null) {
            $is_available = 0;
            foreach (Session::get('sale_cart_array') as $keys => $values) {
                if ((Session::get('sale_cart_array')[$keys]['product_id'] == $request->id && Session::get('sale_cart_array')[$keys]['warehouse_id'] == $request->warehouseId) || (Session::get('sale_cart_array')[$keys]['barcode_no'] == $request->barcode && $request->barcode != '')) {
                    $is_available++;
                    session()->put('sale_cart_array.'.$keys.'.product_quantity', Session::get('sale_cart_array')[$keys]['product_quantity'] + $request->quantity);
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
                if ($available_quantity > 0 || $isServiceProduct == true) {
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
                    Session::push('sale_cart_array', $item_array);
                    $data = 'Success';
                } else {
                    $data = 'This product is out of stock';
                }
            }
        } else {
            $productInfo = [];
            if (isset($request->barcode)) {
                $productInfo = Product::where('deleted', 'No')->where('type', '!=', 'service')->where('status', 'Active')->where('barcode_no', $request->barcode)->first();
            } elseif (isset($request->id)) {
                $productInfo = Product::where('deleted', 'No')->where('type', '!=', 'service')->where('status', 'Active')->where('id', $request->id)->first();
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
            if ($available_quantity > 0 || $isServiceProduct == true) {
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
                    'serializeSaleQtyArray' => [0],
                ];
                Session::push('sale_cart_array', $item_array);
                $data = 'Success';
            } else {
                $data = 'This product is out of stock';
            }
        }

        return response()->json(['data' => $data, 'productId' => $request->id, 'warehouseId' => $request->warehouseId, 'productType' => $product_type]);
    }

    public function fetchCart()
    {
        $grandTotal = 0;
        $cart = '';
        if (Session::get('sale_cart_array') != null) {
            $i = 1;
            foreach (Session::get('sale_cart_array') as $keys => $values) {
                $unitPrice = Session::get('sale_cart_array')[$keys]['product_price'];
                $discount = Session::get('sale_cart_array')[$keys]['product_discount'];
                $totalPrice = Session::get('sale_cart_array')[$keys]['product_quantity'] * ($unitPrice - $discount);
                $productId = Session::get('sale_cart_array')[$keys]['product_id'];
                $warehouseId = Session::get('sale_cart_array')[$keys]['warehouse_id'];
                $productType = Session::get('sale_cart_array')[$keys]['product_type'];
                $checkSerialize = '';
                if ($productType == 'serialize') {
                    $btn = ' <a href="#" onclick="showSerializTable('.Session::get('sale_cart_array')[$keys]['product_id'].', '.Session::get('sale_cart_array')[$keys]['warehouse_id'].', '.Session::get('sale_cart_array')[$keys]['product_quantity'].')"> <i class="fa fa-edit"> </i> </a>';
                    $checkSerialize = '<input type="hidden" name="checkSerialize" value="'.$productId.','.$warehouseId.'" />';
                } else {
                    $btn = '';
                }
                $cart .= '<tr><td>'.$i++.'
				            '.$checkSerialize.'
				            <input type="hidden" name="ids[]" id="id_'.$productId.'_'.$warehouseId.'" value="'.$productId.'" />
				            <input type="hidden" name="warehouseIds[]" id="warehouse_id_'.$productId.'_'.$warehouseId.'" value="'.$productId.'" />
				            <input type="hidden" name="productTypes[]" id="product_type_'.$productId.'_'.$warehouseId.'" value="'.$productType.'" />
				</td>'.
                    '<td>'.Session::get('sale_cart_array')[$keys]['product_name'].' ['.Session::get('sale_cart_array')[$keys]['warehouse_name'].']</td>'.
                    '<td class="text-center"><span class="text-center" id="available_qty_'.$productId.'_'.$warehouseId.'">'.Session::get('sale_cart_array')[$keys]['available_qty'].'</span></td>'.
                    '<td class=""><input type="number" style="width: 80%;"  min="1" id="quantity_'.$productId.'_'.$warehouseId.'" name="quantity[]" class="text-center" onblur="loadCartandUpdate('.$productId.','.$warehouseId.')" value="'.Session::get('sale_cart_array')[$keys]['product_quantity'].'" />'.$btn.'</td>'.
                    '<td><input type="number" style="width: 100%;" min="1" id="unitPrice_'.$productId.'_'.$warehouseId.'"  name="unitPrice[]" class="text-center"  onblur="loadCartandUpdate('.$productId.','.$warehouseId.')" value="'.$unitPrice.'"/></td>'.
                    '<td><input type="number" style="width: 100%;" min="0" id="discountPrice_'.$productId.'_'.$warehouseId.'"  name="discountPrice[]" class="text-center"  onblur="loadCartandUpdate('.$productId.','.$warehouseId.')" value="'.$discount.'" /></td>'.
                    '<td class="text-right"><span id="totalPrice_'.$productId.'_'.$warehouseId.'">'.$totalPrice.'</span></td>'.
                    '<td class="text-center"><a href="#" onclick="removeCartProduct('.Session::get('sale_cart_array')[$keys]['product_id'].','.Session::get('sale_cart_array')[$keys]['warehouse_id'].')" style="color:red;"><i class="fa fa-trash"> </i></a></td></tr>';
                $grandTotal += $totalPrice;
            }
        }

        $cart .= '<tr><td colspan="6" class="text-right" > Total Tk : </td><td class="text-right " id="grandTotal"> '.number_format($grandTotal, 2).'</td><td></td</tr>';
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
        $cartData = Session::get('sale_cart_array');
        foreach (Session::get('sale_cart_array') as $keys => $values) {
            if (Session::get('sale_cart_array')[$keys]['product_id'] == $id && Session::get('sale_cart_array')[$keys]['warehouse_id'] == $warehouse_id) {
                unset($cartData[$keys]);
                Session::put('sale_cart_array', $cartData);
                $data = 'Success';
                break;
            }
        }
        $data = 'Success';

        return response()->json(['data' => $data]);
    }

    public function updateCart(Request $request)
    {
        if (Session::get('sale_cart_array') != null) {
            foreach (Session::get('sale_cart_array') as $keys => $values) {
                if (Session::get('sale_cart_array')[$keys]['product_id'] == $request->id && Session::get('sale_cart_array')[$keys]['warehouse_id'] == $request->warehouse_id) {
                    session()->put('sale_cart_array.'.$keys.'.product_quantity', $request->quantity);
                    session()->put('sale_cart_array.'.$keys.'.product_price', $request->unitPrice);
                    session()->put('sale_cart_array.'.$keys.'.product_discount', $request->discount);
                    // Serialize Product
                    if (Session::get('sale_cart_array')[$keys]['product_type'] == 'serialize') {
                        if ($request->has('product_type') && $request->product_type == true) {
                            $serializeId = $request->serializeProductsId;
                            $serializeSaleQty = $request->serializeSaleQuantity;
                            $serializeIdExist = true;
                            foreach (Session::get('sale_cart_array')[$keys]['serializeIdArray'] as $key => $value) {
                                if ($value == $serializeId) {
                                    session()->put('sale_cart_array.'.$keys.'.serializeSaleQtyArray.'.$key, $serializeSaleQty);
                                    $serializeIdExist = false;
                                }
                            }
                            if ($serializeIdExist) {
                                Session::push('sale_cart_array.'.$keys.'.serializeIdArray', $serializeId);
                                Session::push('sale_cart_array.'.$keys.'.serializeSaleQtyArray', $serializeSaleQty);
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
        Session::forget('sale_cart_array');
        $data = 'Success';

        return $data;
    }

    // =========== Start Serialize Product ===========//
    public function showSerializTable(Request $request)
    {
        $rows = '';
        $product_id = $request->id;
        $warehouse_id = $request->warehouseId;
        $totalQuantityForSale = 0;

        $matchQuantity = $request->matchQuantity;
        $totalMatchQuantity = 0;
        if ($matchQuantity == 'CheckQuantity') {
            $countLen = count(Session::get('sale_cart_array'));
            for ($i = 0; $i < $countLen; $i++) {
                $totalMatchQuantity += array_sum(Session::get('sale_cart_array')[$i]['serializeSaleQtyArray']);
            }

            return response()->json(['displayTable' => $rows, 'totalQuantityForSale' => $totalQuantityForSale, 'totalMatchQuantity' => $totalMatchQuantity]);
        }
        $serializeProducts = DB::table('tbl_serialize_products')
            ->select(
                'tbl_serialize_products.id',
                'tbl_serialize_products.tbl_productsId',
                'tbl_serialize_products.purchase_id',
                'tbl_serialize_products.serial_no',
                'tbl_serialize_products.quantity',
                'tbl_serialize_products.used_quantity'
            )
            ->where('tbl_serialize_products.tbl_productsId', $product_id)
            ->where('tbl_serialize_products.warehouse_id', $warehouse_id)
            ->where('tbl_serialize_products.deleted', 'No')
            ->where('tbl_serialize_products.status', 'Active')
            ->where('tbl_serialize_products.is_sold', 'ON')
            ->orderBy('tbl_serialize_products.id', 'ASC')
            ->get();
        if (count($serializeProducts)) {
            foreach ($serializeProducts as $key => $product) {
                $remainingQty = ($product->quantity - $product->used_quantity);
                $tblSerializeProductsId = $product->id;
                $saleQuantity = $this->findStoreQuantity($product_id, $warehouse_id, $tblSerializeProductsId); // Function Calling
                $totalQuantityForSale += intval($saleQuantity);
                $rows .= '<tr><td>'.($key + 1).'</td>'.
                    '<td>'.$product->serial_no.'</td><td id="serializeRemainingQty_'.$tblSerializeProductsId.'">'.$remainingQty.'</td><td><input class="form-control only-number input-sm stockQuantity'.$key.
                    '" id="stockQuantity_'.$tblSerializeProductsId.'" type="text" name="stockQuantity" placeholder=" ... " required oninput="calculateTotalQuantity(this.value,'.$product_id.','.$warehouse_id.','.$tblSerializeProductsId.')" onblur="loadCartandUpdate('.$product_id.','.$warehouse_id.','.true.')" value="'.$saleQuantity.'"></td></tr>';
            }
        } else {
            $rows .= '<tr class="bg-warning"><td colspan="4">Stock Not Avaialable For Sale...</td></tr>';
        }

        return response()->json(['displayTable' => $rows, 'totalQuantityForSale' => $totalQuantityForSale, 'totalMatchQuantity' => $totalMatchQuantity]);
    }

    public function findStoreQuantity($product_id, $warehouse_id, $serializeId)
    {
        foreach (Session::get('sale_cart_array') as $keys => $values) {
            if (Session::get('sale_cart_array')[$keys]['product_id'] == $product_id && Session::get('sale_cart_array')[$keys]['warehouse_id'] == $warehouse_id) {
                $serializeIdArray = Session::get('sale_cart_array')[$keys]['serializeIdArray'];
                $item = array_search($serializeId, $serializeIdArray);
                if ($item > 0) {
                    return Session::get('sale_cart_array')[$keys]['serializeSaleQtyArray'][$item];
                } else {
                    return '';
                }
            }
        }
    }

    // =========== End Serialize Product ===========//
    public function checkOutCart(CheckOutCartRequest $request)
    {

        // Start Temporary Sale
        $saleType = $request->saleType;
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
            $saleNo = Sale::where('sales_type', $saleType)->where('deleted', 'No')->max('sale_no');
            $saleNo++;
            $saleNo = str_pad($saleNo, 6, '0', STR_PAD_LEFT);
            $sale = new Sale;
            $sale->customer_id = $customerId;
            $sale->sale_no = $saleNo;
            $sale->coa_id = $request->category;
            $sale->date = $request->date;
            $sale->total_amount = floatval($request->total_amount);
            $sale->discount = floatval($request->discount);
            $sale->carrying_cost = floatval($request->carrying_cost);
            $sale->vat = floatval($request->vat);
            $sale->type = 'walkin';
            $sale->ait = floatval($request->ait);
            $sale->grand_total = floatval($request->grand_total);
            $sale->previous_due = floatval($request->previous_due);
            $sale->current_balance = floatval($request->current_balance);
            $sale->total_price = floatval($request->totalPrice);
            $sale->total_with_due = floatval($request->total_with_due);
            $sale->current_payment = floatval($request->current_payment);
            $sale->dues_amount = $request->totalDue;
            $sale->no_of_tenure = $request->noOfTenure;
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
            foreach (Session::get('sale_cart_array') as $keys => $values) {
                $product_id = Session::get('sale_cart_array')[$keys]['product_id'];
                $warehouse_id = Session::get('sale_cart_array')[$keys]['warehouse_id'];
                $product = Product::find($product_id);
                $unit_id = $product->unit_id;
                $unit_price = floatval(Session::get('sale_cart_array')[$keys]['product_price']);
                $discount_amount = floatval(Session::get('sale_cart_array')[$keys]['product_discount']);
                $quantity = Session::get('sale_cart_array')[$keys]['product_quantity'];
                $product->increment('sale_quantity', $quantity);
                $product->decrement('current_stock', $quantity);
                $salePrice = floatval($unit_price - $discount_amount);
                $subtotal = floatval($salePrice * $quantity);
                $product->increment('total_sale_price', $subtotal);
                $lot_no = SaleProduct::where('product_id', $product_id)->where('deleted', 'No')->max('lot_no');
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
                        if (empty($serializeId) || empty($serializeSaleQtyArray[$key])) {
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
            if (floatval($request->grand_total) > 0) {
                $party = Party::find($customerId);
                $party->current_due = $request->current_balance;
                $party->save();
                $maxCode = PaymentVoucher::where('deleted', 'No')->max(DB::raw('cast(voucherNo AS decimal(6))'));
                $maxCode++;
                $maxCode = str_pad($maxCode, 6, '000000', STR_PAD_LEFT);
                $paymentVoucher = new PaymentVoucher;
                $paymentVoucher->party_id = $customerId;
                $paymentVoucher->voucherNo = $maxCode;
                $paymentVoucher->sales_id = $sale_id;
                $paymentVoucher->amount = floatval($request->grand_total);
                $paymentVoucher->payment_method = 'Cash';
                $paymentVoucher->paymentDate = $request->date;
                $paymentVoucher->type = 'Party Payable';
                $paymentVoucher->voucherType = 'PartySale';
                $paymentVoucher->remarks = 'Party Payable for Sale code: '.$saleNo.' payment: '.$request->grand_total;
                $paymentVoucher->entryBy = auth()->user()->id;
                $paymentVoucher->save();
                if (floatval($request->current_payment) > 0) {
                    $maxCode = PaymentVoucher::where('deleted', 'No')->max(DB::raw('cast(voucherNo AS decimal(6))'));
                    $maxCode++;
                    $maxCode = str_pad($maxCode, 6, '0', STR_PAD_LEFT);
                    $paymentVoucher = new PaymentVoucher;
                    $paymentVoucher->party_id = $customerId;
                    $paymentVoucher->voucherNo = $maxCode;
                    $paymentVoucher->sales_id = $sale_id;
                    $paymentVoucher->amount = floatval($request->current_payment);
                    $paymentVoucher->payment_method = $request->payment_method;
                    $paymentVoucher->paymentDate = $request->date;
                    $paymentVoucher->type = 'Payment Received';
                    $paymentVoucher->voucherType = 'PartySale';
                    $paymentVoucher->remarks = 'Party payment for purchase code: '.$saleNo.' payment: '.$request->grand_total;
                    $paymentVoucher->entryBy = auth()->user()->id;
                    $paymentVoucher->save();
                }
            }
            // if EMI number greater than 0
            $number = intval($request->noOfTenure);
            if ($number > 0) {
                $emiPaymentDateArray = explode(',', $request->emiPaymentDateArray);
                for ($i = 0; $i < $number; $i++) {
                    $emiSale = new Emi_sale;
                    $emiSale->sale_id = $sale_id;
                    $emiSale->total_price = $request->totalPrice;
                    $emiSale->serial = ($i + 1);
                    $emiSale->per_tenur_amount = $request->perTenurAmount;
                    $emiSale->tenure_payment_date = $emiPaymentDateArray[$i];
                    $emiSale->status = 'Active';
                    $emiSale->created_by = auth()->user()->id;
                    $emiSale->save();
                }
            }

            /* accounts part start */
            $voucher = new Voucher;
            $voucher->vendor_id = $customerId;
            $voucher->transaction_date = $request->date;
            $voucher->sales_id = $sale_id;
            $voucher->amount = floatval($request->grand_total);
            $voucher->payment_method = $request->payment_method;
            $voucher->deleted = 'No';
            $voucher->status = 'Active';
            $voucher->created_by = Auth::user()->id;
            $voucher->created_date = date('Y-m-d h:s');
            $voucher->save();
            $voucherId = $voucher->id;

            $voucherDetails = new VoucherDetails;
            $voucherDetails->tbl_acc_voucher_id = $voucherId;
            $voucherDetails->tbl_acc_coa_id = $request->category;
            $voucherDetails->credit = floatval($request->grand_total);
            $voucherDetails->voucher_title = 'Sale created with Sale Code '.$saleNo;
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
                $voucherDetails->voucher_title = 'Sale amount paid with Sale Code '.$saleNo;
                $voucherDetails->deleted = 'No';
                $voucherDetails->status = 'Active';
                $voucherDetails->created_by = Auth::user()->id;
                $voucherDetails->created_date = date('Y-m-d h:s');
                $voucherDetails->save();
            }

            $cashId = ChartOfAccounts::where('slug', '=', 'cash')->first();
            $cash = ChartOfAccounts::find($cashId->id);
            $cash->increment('amount', $request->current_payment);
            /* accounts part end */

            Session::forget('sale_cart_array');
            DB::commit();

            return response()->json(['success' => 'Sale saved successfully.', 'saleId' => $sale_id]);
        } catch (Exception $e) {
            DB::rollBack();

            return response()->json(['error' => $e->getMessage()]);
        }
    }

    public function delete(Request $request)
    {
        DB::beginTransaction();
        try {
            $sale = Sale::find($request->id);
            $isEmi = $sale->emi_status;
            $saleReturn = SaleReturn::where('sale_id', $request->id)->where('deleted', 'No')->first();
            if (empty($saleReturn)) {
                // Check EMI Available or Not
                if ($isEmi == 'Yes') {
                    $paidAmouont = DB::table('emi_sales')->where([['sale_id', '=', $sale->id], ['is_paid', '=', 'Yes'], ['deleted', '=', 'No']])->sum('per_tenur_amount');
                    $due = $sale->grand_total - ($sale->current_payment + $paidAmouont);
                    $sale->deleted = 'Yes';
                    $sale->deleted_date = date('Y-m-d H:i:s');
                    $sale->deleted_by = auth()->user()->id;
                    $sale->save();
                    $party = Party::find($sale->customer_id);
                    $party->current_due = ($party->current_due - $due);
                    $party->save();
                } else {
                    $sale->deleted = 'Yes';
                    $sale->deleted_date = date('Y-m-d H:i:s');
                    $sale->deleted_by = auth()->user()->id;
                    $sale->save();

                    $party = Party::find($sale->customer_id);
                    $party->current_due = ($party->current_due + $sale->current_payment - $sale->grand_total);
                    $party->save();
                }

                $sale_products = SaleProduct::where('sale_id', $request->id)->get();
                foreach ($sale_products as $sale_product) {
                    $sale_product = SaleProduct::find($sale_product->id);
                    $sale_product->deleted = 'Yes';
                    $sale_product->deleted_date = date('Y-m-d H:i:s');
                    $sale_product->save();

                    $product = Product::find($sale_product->product_id);
                    $quantity = intval($sale_product->quantity);
                    $unit_price = floatval($product->sale_price);
                    $product->decrement('sale_quantity', $quantity);
                    $product->increment('current_stock', $quantity);
                    $subtotal = $unit_price * $quantity;
                    $product->decrement('total_sale_price', $subtotal);

                    $Currentstock = Currentstock::where('tbl_productsId', $sale_product->product_id)
                        ->where('tbl_wareHouseId', $sale_product->warehouse_id)
                        ->where('deleted', 'No');
                    if ($Currentstock->first()) {
                        $Currentstock->increment('currentStock', $quantity);
                        $Currentstock->increment('salesDelete', $quantity);
                    } else {
                        $Currentstock_insert = new Currentstock;
                        $Currentstock_insert->tbl_productsId = $sale_product->product_id;
                        $Currentstock_insert->tbl_wareHouseId = $sale_product->warehouse_id;
                        $Currentstock_insert->currentStock = $quantity;
                        $Currentstock_insert->salesDelete = $quantity;
                        $Currentstock_insert->entryBy = auth()->user()->id;
                        $Currentstock_insert->entryDate = date('Y-m-d H:i:s');
                        $Currentstock_insert->save();
                    }
                }
                // Serialize Product
                $saleId = $request->id;
                $saleSerializeProducts = Sale::find($saleId)->saleSerializeProducts()
                    ->select('id', 'product_id', 'warehouse_id', 'sale_quantity', 'tbl_serialize_products_id')
                    ->where('deleted', 'No')
                    ->get();
                if (count($saleSerializeProducts) > 0) {
                    foreach ($saleSerializeProducts as $saleSerializeProduct) {
                        $serializeProductId = $saleSerializeProduct->tbl_serialize_products_id;

                        $serializeProduct = SerializeProduct::find($serializeProductId);
                        $serializeProduct->used_quantity = ($serializeProduct->used_quantity - $saleSerializeProduct->sale_quantity);
                        $serializeProduct->is_sold = 'ON';
                        $serializeProduct->save();
                        // Delete Sale Serialize Products
                        $saleSerializeProduct->deleted = 'Yes';
                        $saleSerializeProduct->deleted_by = auth()->user()->id;
                        $saleSerializeProduct->deleted_date = date('Y-m-d H:i:s');
                        $saleSerializeProduct->save();
                    }
                }

                PaymentVoucher::where('sales_id', '=', $request->id)->update(['deleted' => 'Yes', 'deleted_by' => auth()->user()->id, 'deleted_date' => date('Y-m-d H:i:s')]);
                // Voucher::where('sales_id', '=', $request->id)->update(['deleted' => 'Yes', 'status' =>  'Inactive', 'deleted_by' => auth()->user()->id, 'deleted_date' => date('Y-m-d H:i:s')]);
                //
                $voucher = Voucher::where('sales_id', '=', $request->id)->first();
                $voucherId = $voucher->id;
                $voucher->update(['deleted' => 'Yes', 'status' => 'Inactive', 'deleted_by' => auth()->user()->id, 'deleted_date' => date('Y-m-d H:i:s')]);
                VoucherDetails::where('tbl_acc_voucher_id', $voucherId)->update(['deleted' => 'Yes', 'status' => 'Inactive', 'deleted_by' => auth()->user()->id, 'deleted_date' => date('Y-m-d H:i:s')]);

                DB::commit();

                return response()->json(['Success' => 'Sale deleted!']);
            } else {
                DB::rollBack();

                return response()->json(['error' => 'This sale have sale return. First delete return then delete sales.']);
            }
        } catch (Exception $e) {
            DB::rollBack();

            return response()->json(['error' => 'Sales deleted rollBack!']);
        }
    }

    public function EMI(Request $request)
    {
        $sortBy = $request->sort_by ?? 'sales.id';
        $sortDir = $request->sort_direction ?? 'DESC';
        $limit = $request->limit ?? 10;

        $query = DB::table('sales')
            ->join('parties', 'sales.customer_id', '=', 'parties.id')
            ->join('emi_sales', 'sales.id', '=', 'emi_sales.sale_id')
            ->select(
                'sales.sale_no',
                'sales.date',
                'sales.total_amount',
                'sales.discount',
                'sales.carrying_cost',
                'sales.vat',
                'sales.ait',
                'sales.id',
                'sales.no_of_tenure',
                'sales.grand_total',
                'parties.name',
                'parties.code',
                'parties.address',
                'parties.contact',
                'parties.alternate_contact'
            )
            ->where('sales.deleted', 'No')
            ->where('sales.no_of_tenure', '>', 0)
            ->where('sales.emi_status', 'Yes')
            ->distinct();

        if ($search = $request->q) {
            $query->where(function ($q) use ($search) {
                $q->where('sales.sale_no', 'like', "%{$search}%")
                    ->orWhere('parties.name', 'like', "%{$search}%");
            });
        }

        $emiSales = $query->orderBy($sortBy, $sortDir)->paginate($limit)->appends($request->all());

        return view('admin.inventory.sale.view-emi-sale', compact('emiSales'));
    }

    public function createPDF($id)
    {
        $invoice = DB::table('sale_products')
            ->join('sales', 'sale_products.sale_id', '=', 'sales.id')
            ->join('parties', 'sales.customer_id', '=', 'parties.id')
            ->join('products', 'sale_products.product_id', '=', 'products.id')
            ->join('users', 'sales.created_by', '=', 'users.id')
            ->where([['sales.id', '=', $id], ['sales.deleted', '=', 'No']])
            ->selectRaw(
                'SUM(sale_products.quantity) as quantity,sale_products.unit_price,sale_products.unit_discount,SUM(sale_products.subtotal) as subtotal,users.name as entryBy,sales.date,sales.discount,sales.total_amount,sales.dues_amount,
				sales.total_with_due,sales.current_dues,sales.previous_due,sales.carrying_cost,sales.vat,sales.ait,sales.grand_total,sales.id,sales.current_payment,sales.sale_no,parties.contact,parties.name as customerName,
				parties.code,parties.address,products.name,products.code as productCode,products.image,sale_products.product_id,products.type'
            )
            ->groupby(
                'sale_products.product_id',
                'sale_products.unit_price',
                'sale_products.unit_discount',
                'users.name',
                'sales.id',
                'sales.date',
                'sales.discount',
                'sales.total_amount',
                'sales.dues_amount',
                'sales.total_with_due',
                'sales.current_dues',
                'sales.previous_due',
                'sales.carrying_cost',
                'sales.vat',
                'sales.ait',
                'sales.grand_total',
                'sales.current_payment',
                'sales.sale_no',
                'parties.contact',
                'parties.name',
                'parties.code',
                'parties.address',
                'products.name',
                'products.code',
                'products.type',
                'products.image'
            )
            ->get();

        $saleId = $id;
        $sale = DB::table('sales')
            ->where('id', $saleId)
            ->first();
        $serviceCenter = SaleOrder::where('sale_id', '=', $id)->first();

        $pdf = PDF::loadView('admin.inventory.sale.sale-report', ['invoice' => $invoice, 'sale' => $sale, 'serviceCenter' => $serviceCenter]);

        return $pdf->stream('sale-report-pdf.pdf', ['Attachment' => false]);
    }

    public function createChallanPDF($id)
    {
        $invoice = DB::table('sale_products')
            ->join('sales', 'sale_products.sale_id', '=', 'sales.id')
            ->join('parties', 'sales.customer_id', '=', 'parties.id')
            ->join('products', 'sale_products.product_id', '=', 'products.id')
            ->join('units', 'units.id', '=', 'products.unit_id')
            ->join('brands', 'products.brand_id', '=', 'brands.id')
            ->join('users', 'sales.created_by', '=', 'users.id')
            ->where([['sales.id', '=', $id], ['sales.deleted', '=', 'No']])
            ->selectRaw(
                'SUM(sale_products.quantity) as quantity,sale_products.unit_price,sale_products.unit_discount,SUM(sale_products.subtotal) as subtotal,users.name as entryBy,sales.date,sales.discount,sales.total_amount,sales.dues_amount,
				sales.total_with_due,sales.current_dues,sales.previous_due,sales.carrying_cost,sales.vat,sales.ait,sales.grand_total,sales.current_payment,sales.sale_no,parties.contact,parties.name as customerName,sale_products.product_id, products.model_no,
				parties.code,parties.address,products.name,products.code as productCode,products.image,units.name as unitName,brands.name as brandName'
            )
            ->groupby(
                'sale_products.product_id',
                'sale_products.unit_price',
                'sale_products.unit_discount',
                'users.name',
                'sales.date',
                'sales.discount',
                'sales.total_amount',
                'sales.dues_amount',
                'sales.total_with_due',
                'sales.current_dues',
                'sales.previous_due',
                'sales.carrying_cost',
                'sales.vat',
                'sales.ait',
                'sales.grand_total',
                'sales.current_payment',
                'sales.sale_no',
                'parties.contact',
                'parties.name',
                'parties.code',
                'parties.address',
                'products.name',
                'products.code',
                'products.image',
                'products.model_no',
                'units.name',
                'brands.name'
            )
            ->get();

        $saleId = $id;
        $sale = DB::table('sales')
            ->where('id', $saleId)
            ->first();

        $userId = auth()->user()->id;
        $userName = User::where('id', $userId)->pluck('name')->first();
        session(['userName' => $userName]);

        foreach ($invoice as $inv) {
            $productId = $inv->product_id;
            $specs = DB::table('tbl_productspecification')->where('deleted', 'No')->where('tbl_productsId', $productId)->get();
            $productSpecs = '<b>Model No: </b>'.$inv->model_no.' <b>Brand: </b>'.$inv->brandName;
            foreach ($specs as $spec) {
                $productSpecs .= ' <b>'.$spec->specificationName.' : </b> '.$spec->specificationValue.' ';
            }
            $inv->specs = $productSpecs;
        }
        $pdf = PDF::loadView('admin.inventory.sale.sale-report-challan', ['invoice' => $invoice, 'sale' => $sale]);

        return $pdf->stream('sale-report-challan-pdf.pdf', ['Attachment' => false]);
    }

    public function tsCreatePDF($id)
    {
        $invoice = DB::table('tbl_tsalesproducts')
            ->join('tbl_temporary_sale', 'tbl_tsalesproducts.tbl_tSalesId', '=', 'tbl_temporary_sale.id')
            ->join('parties', 'tbl_temporary_sale.tbl_customerId', '=', 'parties.id')
            ->join('products', 'tbl_tsalesproducts.tbl_productsId', '=', 'products.id')
            ->join('users', 'tbl_temporary_sale.createdBy', '=', 'users.id')
            ->where([['tbl_temporary_sale.id', '=', $id], ['tbl_temporary_sale.deleted', '=', 'No']])
            ->selectRaw(
                'SUM(tbl_tsalesproducts.quantity) as quantity,users.name as entryBy,tbl_temporary_sale.tSalesDate as date,tbl_temporary_sale.tsNo as sale_no,parties.contact,parties.name as customerName,
				parties.code,parties.address,products.name,products.code as productCode,products.image'
            )
            ->groupby(
                'users.name',
                'tbl_temporary_sale.tSalesDate',
                'tbl_temporary_sale.tsNo',
                'parties.contact',
                'parties.name',
                'parties.code',
                'parties.address',
                'products.name',
                'products.code',
                'products.image'
            )
            ->get();
        $tbl_temporary_sale = $id;

        $sale = DB::table('tbl_temporary_sale')
            ->where('id', $tbl_temporary_sale)
            ->first();

        $userId = auth()->user()->id;
        $pdf = PDF::loadView('admin.inventory.sale.sale-ts-report', ['invoice' => $invoice, 'sale' => $sale]);

        return $pdf->stream('sale-ts-report-pdf.pdf', ['Attachment' => false]);
    }
}
