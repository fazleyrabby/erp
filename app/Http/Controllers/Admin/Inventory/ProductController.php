<?php

namespace App\Http\Controllers\Admin\Inventory;

use App\Http\Controllers\Controller;
use App\Models\inventory\Brand;
use App\Models\inventory\Category;
use App\Models\inventory\Currentstock;
use App\Models\inventory\DamageProduct;
use App\Models\inventory\Product;
use App\Models\inventory\Productspecification;
use App\Models\inventory\SerializeProduct;
use App\Models\inventory\Unit;
use App\Models\inventory\Warehouse;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Image;
use PDF;

class ProductController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:products.view', ['only' => ['index', 'getProducts']]);
        $this->middleware('permission:products.store', ['only' => ['store']]);
        $this->middleware('permission:products.edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:products.delete', ['only' => ['delete']]);
        // Damage
        $this->middleware('permission:damage.view', ['only' => ['damageIndex']]);
        $this->middleware('permission:damage.store', ['only' => ['damageStore']]);
        $this->middleware('permission:damage.delete', ['only' => ['damageDelete']]);
    }

    public function index(Request $request)
    {
        $data['categories'] = Category::where('deleted', 'No')->where('status', '=', 'Active')->where('name', '!=', 'Service')->get();
        $data['brands'] = Brand::where('deleted', 'No')->where('status', '=', 'Active')->where('name', '!=', 'Service')->get();
        $data['units'] = Unit::where('deleted', 'No')->where('status', '=', 'Active')->get();
        $data['warehouses'] = Warehouse::where('deleted', 'No')->where('status', '=', 'Active')->get();

        $query = DB::table('products')
            ->join('brands', 'products.brand_id', '=', 'brands.id')
            ->join('units', 'products.unit_id', '=', 'units.id')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->select('products.id', 'products.status', 'products.type', 'products.image', 'products.name', 'products.model_no', 'products.code', 'products.barcode_no', 'products.opening_stock', 'products.current_stock', 'products.remainder_quantity', 'products.purchase_price', 'products.sale_price', 'products.discount', 'categories.name as categoryName', 'brands.name as brandName', 'units.name as unitName')
            ->where('products.deleted', 'No');

        if ($search = $request->q) {
            $query->where(function ($q) use ($search) {
                $q->where('products.name', 'like', "%{$search}%")
                    ->orWhere('products.model_no', 'like', "%{$search}%")
                    ->orWhere('products.code', 'like', "%{$search}%")
                    ->orWhere('products.barcode_no', 'like', "%{$search}%");
            });
        }

        $sortBy = $request->sort_by ?? 'products.id';
        $sortDir = $request->sort_direction ?? 'DESC';
        $limit = $request->limit ?? 10;

        $data['products'] = $query->orderBy($sortBy, $sortDir)->paginate($limit)->appends($request->all());

        return view('admin.inventory.products.view-products', $data);
    }

    public function getAdvanceSearchProducts(Request $request)
    {

        $products = DB::table('products')
            ->join('brands', 'products.brand_id', '=', 'brands.id')
            ->join('units', 'products.unit_id', '=', 'units.id')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->select('products.id', 'products.status', 'products.image', 'products.name', 'products.code', 'products.barcode_no', 'products.opening_stock', 'products.current_stock', 'products.remainder_quantity', 'products.purchase_price', 'products.sale_price', 'products.discount', 'categories.name as categoryName', 'brands.name as brandName', 'units.name as unitName')
            ->where('products.deleted', 'No')
            ->orderBy('products.id', 'DESC')
            ->get();
        $output = ['data' => []];
        $i = 1;
        foreach ($products as $product) {
            $productId = $product->id;
            $specs = DB::table('tbl_productspecification')->where('deleted', 'No')->where('tbl_productsId', $productId)->get();
            $productSpecs = '';
            foreach ($specs as $spec) {
                $productSpecs .= '<tr><td><b>'.$spec->specificationName.' : </b></td><td>'.$spec->specificationValue.'</td></tr><br>';
            }
            if ($request->page == 'Purchase') {
                $button = '<td>
                            <div >
                                <button type="button" class="btn btn-cyan" onclick="warehouseWiseStock('.$product->id.');"> <i class="fa fa-eye"> </i> </button>
                                <button type="button" class="btn btn-cyan" onclick="selectProductWOWarehouse('.$product->id.');"> <i class="fa fa-plus"> </i> </button>
                            </div>
                            </td>';
            } else {
                $button = '<td>
                            <div >
                                <button type="button" class="btn btn-cyan" onclick="warehouseWiseStock('.$product->id.');"> <i class="fa fa-eye"> </i> </button>
                            </div>
                            </td>';
            }

            $output['data'][] = [
                $i++.'<input type="hidden" name="id" id="id" value="'.$product->id.'" />',
                $product->name.' <br><b>Code: </b>'.$product->code.'<br><b>Barcode: </b>'.$product->barcode_no,
                '<b>Category: </b>'.$product->categoryName.' <br><b>Brand: </b>'.$product->brandName.'<br><b>Unit: </b>'.$product->unitName,
                $productSpecs,
                '<b>PP: </b>'.Session::get('companySettings')[0]['currency'].' '.$product->purchase_price.'<br><b>SP: </b>'.Session::get('companySettings')[0]['currency'].' '.$product->sale_price.'<br><b>Dis: </b>'.Session::get('companySettings')[0]['currency'].' '.$product->discount,
                '<h6 class="text-cyan">Stock : '.$product->current_stock.'</h6><div id="'.$product->id.'"></div>',
                $button,
            ];
        }

        return $output;
    }

    public function warehouseWiseStock(Request $request)
    {
        $productId = $request->id;
        $currentstocks = DB::table('tbl_currentstock')
            ->join('tbl_warehouse', 'tbl_currentstock.tbl_wareHouseId', '=', 'tbl_warehouse.id')
            ->select('tbl_currentstock.id', 'tbl_currentstock.tbl_wareHouseId', 'tbl_currentstock.tbl_wareHouseId', 'tbl_currentstock.currentStock', 'tbl_warehouse.wareHouseName')
            ->where('tbl_currentstock.tbl_productsId', $productId)
            ->where('tbl_currentstock.deleted', 'No')
            ->where('tbl_warehouse.deleted', 'No')
            ->orderBy('tbl_currentstock.id', 'ASC')
            ->distinct('tbl_warehouse.id')
            ->get();

        $warehouseWiseStock = '';
        foreach ($currentstocks as $stock) {
            $warehouseWiseStock .= '<table style="border: 1px solid #ececec;"><tr id="warehouseWise"><td><b id="wrhs_name'.$stock->tbl_wareHouseId.'">'.$stock->wareHouseName.'</b>:</td><td width="25%" >'.$stock->currentStock.'</td><td width="5%"><a href="#" class="btn btn-sm btn-success rounded" onclick="selectProducts('.$productId.','.$stock->tbl_wareHouseId.')"><i class="fa fa-plus"></i></a></td></tr></table>';
        }

        return $warehouseWiseStock;
    }

    public function brandAndCategoryWise(Request $request)
    {
        $categoryId = $request->categoryId;
        $brandId = $request->brandId;
        $warehouseId = $request->warehouseId;
        // Added By Hamid (line: 150 to 161)
        if ($categoryId != '' && $brandId != '' && $warehouseId != '') {
            // WarehouseWise Product(s)
            $product = DB::table('products')
                ->join('tbl_currentstock', 'products.id', '=', 'tbl_currentstock.tbl_productsId')
                ->select('products.*', 'tbl_currentstock.currentStock')
                ->where('products.deleted', 'No')
                ->where('products.category_id', $categoryId)
                ->where('products.brand_id', $brandId)
                ->where('tbl_currentstock.tbl_wareHouseId', $warehouseId)
                ->where('products.deleted', 'No')
                ->get();
            // End Added By Hamid
        } elseif ($categoryId == '' && $brandId == '') {
            if ($request->type == 'purchase') {
                $product = Product::where('deleted', 'No')
                    ->where('status', 'Active')
                    ->get();
            } else {
                $product = Product::where('deleted', 'No')
                    ->where('current_stock', '>', 0)
                    ->where('status', 'Active')
                    ->get();
            }
        } elseif ($categoryId == '') {
            if ($request->type == 'purchase') {
                $product = DB::table('products')
                    ->where('deleted', 'No')
                    ->where('brand_id', $brandId)
                    ->where('status', 'Active')
                    ->get();
            } else {
                $product = DB::table('products')
                    ->where('deleted', 'No')
                    ->where('brand_id', $brandId)
                    ->where('status', 'Active')
                    ->where('current_stock', '>', 0)
                    ->get();
            }
        } elseif ($brandId == '') {
            if ($request->type == 'purchase') {
                $product = DB::table('products')
                    ->where('deleted', 'No')
                    ->where('category_id', $categoryId)
                    ->where('status', 'Active')
                    ->get();
            } else {
                $product = DB::table('products')
                    ->where('deleted', 'No')
                    ->where('category_id', $categoryId)
                    ->where('status', 'Active')
                    ->where('current_stock', '>', 0)
                    ->get();
            }
        } else {
            if ($request->type == 'purchase') {
                $product = DB::table('products')
                    ->where('deleted', 'No')
                    ->where('category_id', $categoryId)
                    ->where('brand_id', $brandId)
                    ->where('status', 'Active')
                    ->get();
            } else {
                $product = DB::table('products')
                    ->where('deleted', 'No')
                    ->where('category_id', $categoryId)
                    ->where('brand_id', $brandId)
                    ->where('status', 'Active')
                    ->where('current_stock', '>', 0)
                    ->get();
            }
        }

        return $product;
    }

    public function store(Request $request)
    {
        $discount = $request->discount;
        $lastChar = substr($discount, -1); // Get Last Character
        $isNumber = false;
        // ---If Discount In Percentage(%)---//
        if ($lastChar == '%') {
            $discountInNumbmer = substr($discount, 0, -1); // Remove Last Character
            $isNumber = is_numeric($discountInNumbmer);
            $lastChar = substr($discount, -1);
            $request['discount'] = $discountInNumbmer;
        }

        $request->validate([
            'name' => 'required|max:255|regex:/^([a-zA-Z0-9_ "\.\-\s\,\;\:\/\&\$\%\(\)]+\s)*[a-zA-Z0-9_ "\.\-\s\,\;\:\/\&\$\%\(\)]+$/u',
            'opening_stock' => 'required|max:7|regex:/^\d+(\.\d{1,2})?$/',
            'remainder_quantity' => 'required|max:7|regex:/^\d+(\.\d{1,2})?$/',
            'category_id' => 'required',
            'brand_id' => 'required',
            'stock_warehouse' => 'required|max:7|regex:/^\d+(\.\d{1,2})?$/',
            'unit_id' => 'required',
            'model_no' => 'required',
            'purchase_price' => 'required|max:7|regex:/^\d+(\.\d{1,2})?$/',
            'sale_price' => 'required|max:7|regex:/^\d+(\.\d{1,2})?$/',
            'discount' => 'numeric|nullable',
            'type' => 'required',
            'stockCheck' => 'required',
            'specNames' => 'nullable|regex:/^([a-zA-Z0-9_ "\.\-\s\,\;\:\/\&\$\%\(\)]+\s)*[a-zA-Z0-9_ "\.\-\s\,\;\:\/\&\$\%\(\)]+$/u',
            'specValues' => 'nullable|regex:/^([a-zA-Z0-9_ "\.\-\s\,\;\:\/\&\$\%\(\)]+\s)*[a-zA-Z0-9_ "\.\-\s\,\;\:\/\&\$\%\(\)]+$/u',
            'notes' => 'nullable|regex:/^([a-zA-Z0-9_ "\.\-\s\,\;\:\/\&\$\%\(\)]+\s)*[a-zA-Z0-9_ "\.\-\s\,\;\:\/\&\$\%\(\)]+$/u',
        ]);
        // --- If Dicount In Percentage(%)---//
        if ($lastChar == '%') {
            $discount = $discountInNumbmer;
            $salePrice = $request->sale_price;
            $amount = ($salePrice / 100);
            $discountAmount = $amount * $discount;
            $request['discount'] = $discountAmount;
        }

        if ($request->type == 'serialize') {
            $request->validate([
                'itemsInBox' => 'required',
                'serialNumbers' => 'required',
                'stockQuantities' => 'required',
            ]);
        }
        if ($request->hasFile('image')) {
            $request->validate([
                'image' => 'image|max:2048',
            ]);

            $productImage = $request->file('image');
            $name = $productImage->getClientOriginalName();
            $uploadPath = 'upload/product_images/thumbs/';
            $uploadResizePath = 'upload/product_images/resizes/';
            $uploadPathOriginal = 'upload/product_images/';
            $imageName = time().$name;
            $imageUrl = $uploadPath.$imageName;
            $resizeUrl = $uploadResizePath.$imageName;
            // --resize image upload in public--//
            Image::make($productImage)->resize(360, 360)->save($resizeUrl);
            Image::make($productImage)->resize(100, 100)->save($imageUrl);
            // --original image upload in public--//
            $request->image->move(public_path($uploadPathOriginal), $imageName);

            // End Image Resize
        } else {
            $imageName = 'no_image.png';
        }

        DB::beginTransaction();
        try {
            $productCode = Product::where('deleted', '=', 'No')->where('status', '=', 'Active')->max('code');
            $productCode++;
            $productCode = str_pad($productCode, 6, '0', STR_PAD_LEFT);

            $product = new Product;
            $product->name = $request->name;
            $product->image = $imageName;
            $product->code = $productCode;

            if ($request->barcode_no == '') {
                $barcode_no = $productCode;
            } else {
                $barcode_no = '';
            }

            $product->barcode_no = $barcode_no;
            $product->category_id = $request->category_id;
            $product->brand_id = $request->brand_id;
            $product->unit_id = $request->unit_id;
            $product->opening_stock = $request->opening_stock;
            $product->current_stock = $request->opening_stock;
            $product->remainder_quantity = $request->remainder_quantity;
            $product->purchase_price = $request->purchase_price;
            $product->sale_price = $request->sale_price;
            $product->discount = $request->discount;
            $product->notes = $request->notes;
            $product->model_no = $request->model_no;
            $product->created_by = auth()->user()->id;
            $product->created_date = date('Y-m-d H:i:s');
            $product->type = $request->type;
            $product->stock_check = $request->stockCheck;
            $product->items_in_box = $request->itemsInBox;
            $product->save();
            $productId = $product->id;
            // Serialize Product
            if ($request->type == 'serialize') {
                $serialNumbers = explode(',', $request->serialNumbers);
                $stockQuantities = explode(',', $request->stockQuantities);
                $k = 0;
                foreach ($serialNumbers as $serialNumber) {
                    $serialize = new SerializeProduct;
                    $serialize->tbl_productsId = $productId;
                    $serialize->serial_no = $serialNumber;
                    $serialize->quantity = $stockQuantities[$k];
                    $serialize->warehouse_id = $request->stock_warehouse;
                    $serialize->created_by = auth()->user()->id;
                    $serialize->created_date = date('Y-m-d H:i:s');
                    $serialize->save();
                    $k++;
                }
            }
            $specNames = explode(',', $request->specNames);
            $specValues = explode(',', $request->specValues);
            if ($specNames[0] != -1 || $specValues[0] != -1) {
                $i = 0;
                foreach ($specNames as $specName) {
                    $spec = new Productspecification;
                    $spec->tbl_productsId = $productId;
                    $spec->specificationName = $specName;
                    $spec->specificationValue = $specValues[$i];
                    $spec->lastInsertedBy = auth()->user()->id;
                    $spec->insertDate = date('Y-m-d H:i:s');
                    $spec->save();
                    $i++;
                }
            }
            // Currentstock
            if ($request->type == 'service') {
                DB::commit();

                return response()->json(['success' => 'Product saved successfully']);
            }
            $currentStock = new Currentstock;
            $currentStock->tbl_productsId = $productId;
            $currentStock->tbl_wareHouseId = $request->stock_warehouse;
            $currentStock->currentStock = $request->opening_stock;
            $currentStock->initialStock = $request->opening_stock;
            $currentStock->entryBy = auth()->user()->id;
            $currentStock->entryDate = date('Y-m-d H:i:s');
            $currentStock->save();
            DB::commit();

            return response()->json(['success' => 'Product saved successfully']);
        } catch (Exception $e) {
            DB::rollBack();

            return response()->json(['error' => 'rollBack! Please try again']);
        }
    }

    public function servicestore(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255|regex:/^([a-zA-Z0-9_ "\.\-\s\,\;\:\/\&\$\%\(\)]+\s)*[a-zA-Z0-9_ "\.\-\s\,\;\:\/\&\$\%\(\)]+$/u',
            'purchase_price' => 'max:7|regex:/^\d+(\.\d{1,2})?$/',
            'sale_price' => 'max:7|regex:/^\d+(\.\d{1,2})?$/',
            'notes' => 'nullable|regex:/^([a-zA-Z0-9_ "\.\-\s\,\;\:\/\&\$\%\(\)]+\s)*[a-zA-Z0-9_ "\.\-\s\,\;\:\/\&\$\%\(\)]+$/u',
            'specNames' => 'nullable|regex:/^([a-zA-Z0-9_ "\.\-\s\,\;\:\/\&\$\%\(\)]+\s)*[a-zA-Z0-9_ "\.\-\s\,\;\:\/\&\$\%\(\)]+$/u',
            'specValues' => 'nullable|regex:/^([a-zA-Z0-9_ "\.\-\s\,\;\:\/\&\$\%\(\)]+\s)*[a-zA-Z0-9_ "\.\-\s\,\;\:\/\&\$\%\(\)]+$/u',
        ]);

        $discount = '0.00%';
        $lastChar = substr($discount, -1); // Get Last Character
        $isNumber = false;
        // ---If Discount In Percentage(%)---//
        if ($lastChar == '%') {
            $discountInNumbmer = substr($discount, 0, -1); // Remove Last Character
            $isNumber = is_numeric($discountInNumbmer);
            $lastChar = substr($discount, -1);
            $request['discount'] = $discountInNumbmer;
        }

        // --- If Dicount In Percentage(%)---//
        if ($lastChar == '%') {
            $discount = $discountInNumbmer;
            $salePrice = $request->sale_price;
            $amount = ($salePrice / 100);
            $discountAmount = $amount * $discount;
            $request['discount'] = $discountAmount;
        }

        if ($request->type == 'serialize') {
            $request->validate([
                'itemsInBox' => 'required',
                'serialNumbers' => 'required',
                'stockQuantities' => 'required',
            ]);
        }
        if ($request->hasFile('image')) {
            $request->validate([
                'image' => 'image|max:2048',
            ]);

            $productImage = $request->file('image');
            $name = $productImage->getClientOriginalName();
            $uploadPath = 'upload/product_images/thumbs/';
            $uploadResizePath = 'upload/product_images/resizes/';
            $uploadPathOriginal = 'upload/product_images/';
            $imageName = time().$name;
            $imageUrl = $uploadPath.$imageName;
            $resizeUrl = $uploadResizePath.$imageName;
            // --resize image upload in public--//
            Image::make($productImage)->resize(360, 360)->save($resizeUrl);
            Image::make($productImage)->resize(100, 100)->save($imageUrl);
            // --original image upload in public--//
            $request->image->move(public_path($uploadPathOriginal), $imageName);

            // End Image Resize
        } else {
            $imageName = 'no_image.png';
        }

        DB::beginTransaction();
        try {
            $productCode = Product::where('deleted', '=', 'No')->where('status', '=', 'Active')->max('code');

            return $productCode;
            $productCode++;
            $productCode = str_pad($productCode, 6, '0', STR_PAD_LEFT);
            $product = new Product;
            $product->name = $request->name;
            $product->image = $imageName;
            $product->code = $productCode;
            if ($request->barcode_no == '') {
                $barcode_no = $productCode;
            } else {
                $barcode_no = '';
            }
            $product->barcode_no = $barcode_no;
            $product->category_id = 3;
            $product->brand_id = 3;
            $product->unit_id = 3;
            $product->opening_stock = 0;
            $product->current_stock = 0;
            $product->remainder_quantity = 0;
            $product->purchase_price = $request->purchase_price;
            $product->sale_price = $request->sale_price;

            $product->notes = $request->notes;
            $product->model_no = '0123';
            $product->created_by = auth()->user()->id;
            $product->created_date = date('Y-m-d H:i:s');
            $product->type = 'service';
            $product->stock_check = $request->stockCheck;
            $product->items_in_box = $request->itemsInBox;
            $product->save();
            $productId = $product->id;
            // Serialize Product
            if ($request->type == 'serialize') {
                $serialNumbers = explode(',', $request->serialNumbers);
                $stockQuantities = explode(',', $request->stockQuantities);
                $k = 0;
                foreach ($serialNumbers as $serialNumber) {
                    $serialize = new SerializeProduct;
                    $serialize->tbl_productsId = $productId;
                    $serialize->serial_no = $serialNumber;
                    $serialize->quantity = $stockQuantities[$k];
                    $serialize->warehouse_id = $request->stock_warehouse;
                    $serialize->created_by = auth()->user()->id;
                    $serialize->created_date = date('Y-m-d H:i:s');
                    $serialize->save();
                    $k++;
                }
            }
            $specNames = explode(',', $request->specNames);
            $specValues = explode(',', $request->specValues);
            if ($specNames[0] != -1 || $specValues[0] != -1) {
                $i = 0;
                foreach ($specNames as $specName) {
                    $spec = new Productspecification;
                    $spec->tbl_productsId = $productId;
                    $spec->specificationName = $specName;
                    $spec->specificationValue = $specValues[$i];
                    $spec->lastInsertedBy = auth()->user()->id;
                    $spec->insertDate = date('Y-m-d H:i:s');
                    $spec->save();
                    $i++;
                }
            }
            // Currentstock

            DB::commit();

            return response()->json(['success' => 'Product saved successfully']);

        } catch (Exception $e) {
            DB::rollBack();

            return response()->json(['error' => 'rollBack! Please try again']);
        }
    }

    public function edit(Request $request)
    {
        $product = Product::find($request->id);
        $productSpecs = DB::table('tbl_productspecification')
            ->where('tbl_productsId', $product->id)
            ->where('deleted', 'No')
            ->get();

        return response()->json([$product, $productSpecs]);
    }

    public function editOpenStock(Request $request)
    {
        $product = Product::find($request->id);
        $productSpecs = DB::table('tbl_productspecification')
            ->where('tbl_productsId', $product->id)
            ->where('deleted', 'No')
            ->get();
        $currentStocks = DB::table('tbl_currentstock')
            ->join('tbl_warehouse', 'tbl_currentstock.tbl_wareHouseId', '=', 'tbl_warehouse.id')
            ->join('products', 'tbl_currentstock.tbl_productsId', '=', 'products.id')
            ->select('tbl_warehouse.wareHouseName', 'tbl_currentstock.initialStock', 'tbl_currentstock.currentStock', 'products.name', 'products.code')
            ->where('tbl_currentstock.deleted', 'No')
            ->where('tbl_currentstock.tbl_productsId', $request->id)
            ->where('tbl_currentstock.initialStock', '>', 0)
            ->orderBy('tbl_warehouse.id', 'DESC')
            ->get();
        $initialStockData = '';
        foreach ($currentStocks as $currentStock) {
            $initialStockData .= '<tr>
                                    <td>'.$currentStock->wareHouseName.'</td>
                                    <td>'.$currentStock->initialStock.'</td>
                                    <td>'.$currentStock->currentStock.'</td>
                                </tr>';
        }
        // Start Serialize Products
        $serializeProductRows = '';
        if ($product->type == 'serialize') {
            $serializeProducts = DB::table('tbl_serialize_products')
                ->select(
                    'tbl_serialize_products.id',
                    'tbl_serialize_products.tbl_productsId',
                    'tbl_serialize_products.warehouse_id',
                    'tbl_serialize_products.serial_no',
                    'tbl_serialize_products.quantity',
                    'tbl_serialize_products.used_quantity'
                )
                ->where('tbl_serialize_products.tbl_productsId', $product->id)
                ->whereNull('tbl_serialize_products.purchase_id')
                ->where('tbl_serialize_products.deleted', 'No')
                ->where('tbl_serialize_products.status', 'Active')
                ->where('tbl_serialize_products.is_sold', 'ON')
                ->orderBy('tbl_serialize_products.id', 'ASC')
                ->get();

            $product_id = $request->id;
            if (count($serializeProducts) > 0) {
                foreach ($serializeProducts as $key => $serializeProduct) {
                    $tblSerializeProductsId = $serializeProduct->id;
                    $serializeProductRows .= '<tr id="row'.($key + 1).'"><td>'.($key + 1).'</td>'.
                        '<td><input class="form-control input-sm serialNo'.$key.
                        '" id="editSerialNo" type="text" name="serialNo" placeholder=" Serial... " value="'.$serializeProduct->serial_no.'" required></td><td><input class="form-control only-number input-sm stockQuantity'.$key.
                        '" id="stockQuantity_'.$tblSerializeProductsId.'" type="text" name="stockQuantity" placeholder=" ... " required oninput="updateCalculateTotalQuantity(this.value,'.$product_id.','.$serializeProduct->warehouse_id.','.$tblSerializeProductsId.')" onblur="updateCalculateTotalQuantity('.$product_id.','.$serializeProduct->warehouse_id.','.true.')" value="'.$serializeProduct->quantity.'"></td></tr>';
                }
            } else {
                $serializeProductRows = '<h5 class="text-dark text-bolder text-center">No Serialize Product Available!</h5>';
            }
        }

        // End Serialize Products
        return response()->json(['product' => $product, 'productSpecs' => $productSpecs, 'initialStockData' => $initialStockData, 'serializeProductRows' => $serializeProductRows]);
    }

    public function update(Request $request)
    {
        $discount = $request->discount;
        $lastChar = substr($discount, -1); // get last character
        $isNumber = false;
        // ---if dicount in percentage(%)---//
        if ($lastChar == '%') {
            $discountInNumbmer = substr($discount, 0, -1); // remove last character
            $isNumber = is_numeric($discountInNumbmer);
            $lastChar = substr($discount, -1);
            $request['discount'] = $discountInNumbmer;
        }

        $request->validate([
            'name' => 'required|max:255|regex:/^([a-zA-Z0-9_ "\.\-\s\,\;\:\/\&\$\%\(\)]+\s)*[a-zA-Z0-9_ "\.\-\s\,\;\:\/\&\$\%\(\)]+$/u',
            'opening_stock' => 'required|max:7|regex:/^\d+(\.\d{1,2})?$/',
            'remainder_quantity' => 'required|max:7|regex:/^\d+(\.\d{1,2})?$/',
            'category_id' => 'required',
            'brand_id' => 'required',
            'unit_id' => 'required',
            'status' => 'required',
            'purchase_price' => 'required|max:10|regex:/^\d+(\.\d{1,2})?$/',
            'sale_price' => 'required|max:10|regex:/^\d+(\.\d{1,2})?$/',
            'discount' => 'numeric|nullable',
            'specNames' => 'nullable|regex:/^([a-zA-Z0-9_ "\.\-\s\,\;\:\/\&\$\%\(\)]+\s)*[a-zA-Z0-9_ "\.\-\s\,\;\:\/\&\$\%\(\)]+$/u',
            'specValues' => 'nullable|regex:/^([a-zA-Z0-9_ "\.\-\s\,\;\:\/\&\$\%\(\)]+\s)*[a-zA-Z0-9_ "\.\-\s\,\;\:\/\&\$\%\(\)]+$/u',
            'notes' => 'nullable|regex:/^([a-zA-Z0-9_ "\.\-\s\,\;\:\/\&\$\%\(\)]+\s)*[a-zA-Z0-9_ "\.\-\s\,\;\:\/\&\$\%\(\)]+$/u',
        ]);

        // ---if dicount in percentage(%)---//
        if ($lastChar == '%') {
            $discount = $discountInNumbmer;
            $salePrice = $request->sale_price;
            $amount = ($salePrice / 100);
            $discountAmount = $amount * $discount;
            $request['discount'] = $discountAmount;
        }

        $product = Product::find($request->id);
        $product->name = $request->name;

        if ($request->hasFile('image')) {
            $request->validate([
                'image' => 'image|max:2048',
            ]);
            $productImage = $request->file('image');
            $name = $productImage->getClientOriginalName();
            $uploadPath = 'upload/product_images/thumbs/';
            $uploadResizePath = 'upload/product_images/resizes/';
            $uploadPathOriginal = 'upload/product_images/';
            $imageName = time().$name;
            $imageUrl = $uploadPath.$imageName;
            $resizeUrl = $uploadResizePath.$imageName;
            // --resize image upload in public--//
            Image::make($productImage)->resize(360, 360)->save($resizeUrl);
            Image::make($productImage)->resize(100, 100)->save($imageUrl);
            // --original image upload in public--//
            $request->image->move(public_path($uploadPathOriginal), $imageName);
            $product->image = $imageName;
            /*$productImage = $request->file('image');
            $name = $productImage->getClientOriginalName();
            $uploadPath = 'upload/product_images/';
            $imageUrl = $uploadPath . $name;
            $imageName = time() . $name;
            $productImage->move($uploadPath, $imageName);
            $product->image = $imageName;*/
        }
        // $product->code = $request->code;
        $product->barcode_no = $request->barcode_no;
        $product->category_id = $request->category_id;
        $product->brand_id = $request->brand_id;
        $product->unit_id = $request->unit_id;
        $product->opening_stock = $request->opening_stock;
        $product->remainder_quantity = $request->remainder_quantity;
        $product->purchase_price = $request->purchase_price;
        $product->sale_price = $request->sale_price;
        $product->discount = $request->discount;
        $product->status = $request->status;
        $product->notes = $request->notes;
        $product->model_no = $request->model_no;
        $product->updated_by = auth()->user()->id;
        $product->updated_date = date('Y-m-d H:i:s');
        $product->type = $request->type;
        $product->stock_check = $request->stockCheck;
        $product->save();
        // Specs
        $specIds = (explode(',', $request->specIds));
        $specNames = (explode(',', $request->specNames));
        $specValues = (explode(',', $request->specValues));
        // New Specs
        $newSpecNames = (explode(',', $request->newSpecNames));
        $newSpecValues = (explode(',', $request->newSpecValues));
        if ($specIds[0] != -1) {
            for ($i = 0; $i < count($specIds); $i++) {
                $specId = $specIds[$i];
                $spec = Productspecification::find($specId);
                $spec->tbl_productsId = $request->id;
                $spec->specificationName = $specNames[$i];
                $spec->specificationValue = $specValues[$i];
                $spec->lastUpdatedBy = auth()->user()->id;
                $spec->lastupdatedDate = date('Y-m-d H:i:s');
                $spec->save();
            }
        }
        if ($newSpecNames[0] != -1 || $newSpecValues[0] != -1) {
            for ($i = 0; $i < count($newSpecNames); $i++) {
                $spec = new Productspecification;
                $spec->tbl_productsId = $request->id;
                $spec->specificationName = $newSpecNames[$i];
                $spec->specificationValue = $newSpecValues[$i];
                $spec->lastInsertedBy = auth()->user()->id;
                $spec->insertDate = date('Y-m-d H:i:s');
                $spec->save();
            }
        }

        return response()->json(['success' => 'Product updated Successfully']);
    }

    public function updateProductOpenStock(Request $request)
    {
        $request->validate([
            'warehouseId' => 'required',
        ]);
        DB::beginTransaction();
        try {
            $currentStock = Currentstock::where('deleted', 'No')->where('tbl_productsId', $request->productId)->where('tbl_wareHouseId', $request->warehouseId);
            $product = Product::find($request->productId);
            if ($currentStock->first()) {
                $currentStock = $currentStock->first();
                $currentDiff = $request->openingStock - $currentStock->initialStock;
                $currentStock->initialStock = $request->openingStock;
                $currentStock->save();
                $currentStock->increment('currentStock', $currentDiff);
                $product->increment('current_stock', $currentDiff);
                $product->increment('opening_stock', $currentDiff);
            } else {
                $currentstock_insert = new Currentstock;
                $currentstock_insert->tbl_productsId = $request->productId;
                $currentstock_insert->tbl_wareHouseId = $request->warehouseId;
                $currentstock_insert->currentStock = $request->openingStock;
                $currentstock_insert->initialStock = $request->openingStock;
                $currentstock_insert->entryBy = auth()->user()->id;
                $currentstock_insert->entryDate = date('Y-m-d H:i:s');
                $currentstock_insert->save();
                $product->increment('current_stock', $request->openingStock);
                $product->increment('opening_stock', $request->openingStock);
            }
            // Start Serialize Product
            if ($product->type == 'serialize') {
                $serializeProducts = DB::table('tbl_serialize_products')
                    ->where('tbl_productsId', $product->id)
                    ->where('warehouse_id', $request->warehouseId)
                    ->whereNull('purchase_id')
                    ->where('deleted', 'No')
                    ->orderBy('id', 'ASC')
                    ->pluck('id');

                if (count($serializeProducts) > 0) {
                    $tblSerializeProductIdArray = $serializeProducts->toArray(); // Convert Obejct to Array
                    // Delete Multiple Records
                    SerializeProduct::whereIn('id', $tblSerializeProductIdArray)
                        ->update([
                            'deleted' => 'Yes',
                            'deleted_by' => Auth::id(),
                            'deleted_date' => date('Y-m-d H:i:s'),
                        ]);
                }
                // Serialize Product
                $serialNumbers = explode(',', $request->editSerialNumbers);
                $stockQuantities = explode(',', $request->editStockQuantities);
                $k = 0;
                // Insert New
                foreach ($stockQuantities as $stockQuantity) {
                    $serialize = new SerializeProduct;
                    $serialize->tbl_productsId = $request->productId;
                    $serialize->serial_no = $serialNumbers[$k];
                    $serialize->quantity = $stockQuantity;
                    $serialize->warehouse_id = $request->warehouseId;
                    $serialize->created_by = auth()->user()->id;
                    $serialize->created_date = date('Y-m-d H:i:s');
                    $serialize->save();
                    $k++;
                }
            }
            // End Serialize Product
            DB::commit();

            return response()->json(['success' => 'OpeningStock updated Successfully']);
        } catch (Exception $e) {
            DB::rollBack();

            return response()->json(['success' => $e]);
        }
    }

    public function delete(Request $request)
    {
        $product = Product::find($request->id);
        $product->deleted = 'Yes';
        $product->status = 'Inactive';
        $product->name = $product->name.'-Deleted-'.$request->id;
        $product->code = $product->code.'-Deleted-'.$request->id;
        $product->barcode_no = $product->barcode_no.'-Deleted-'.$request->id;
        $product->deleted_by = auth()->user()->id;
        $product->deleted_date = date('Y-m-d H:i:s');
        $product->save();

        return response()->json(['success' => 'Product deleted successfully']);
    }

    public function deleteSpec(Request $request)
    {
        $spec = Productspecification::find($request->id);
        $spec->deleted = 'Yes';
        $spec->deletedBy = auth()->user()->id;
        $spec->deletedDate = date('Y-m-d H:i:s');
        $spec->save();

        return response()->json(['success' => 'Product Spec deleted']);
    }

    public function damageIndex(Request $request)
    {
        $data['products'] = Product::where('deleted', 'No')->where('type', '!=', 'service')->get();

        $query = DB::table('damage_products')
            ->join('products', 'damage_products.products_id', '=', 'products.id')
            ->join('brands', 'products.brand_id', '=', 'brands.id')
            ->join('units', 'products.unit_id', '=', 'units.id')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->select('damage_products.id', 'damage_products.damage_quantity', 'damage_products.damage_date', 'damage_products.damage_order_no', 'damage_products.remarks', 'products.image', 'products.name', 'products.code', 'categories.name as categoryName', 'brands.name as brandName', 'units.name as unitName')
            ->where('damage_products.deleted', 'No');

        // Search
        if ($searchTerm = $request->q) {
            $query->where(function ($q) use ($searchTerm) {
                $q->where('products.name', 'like', "%{$searchTerm}%")
                    ->orWhere('products.code', 'like', "%{$searchTerm}%")
                    ->orWhere('damage_products.damage_order_no', 'like', "%{$searchTerm}%");
            });
        }

        // Sort
        $sortBy = $request->sort_by ?? 'damage_products.id';
        $sortDir = strtoupper($request->sort_direction ?? 'DESC') === 'ASC' ? 'ASC' : 'DESC';
        $limit = $request->limit ?? 10;
        $query->orderBy($sortBy, $sortDir);

        $data['damages'] = $query->paginate($limit)->appends($request->all());

        return view('admin.inventory.damage.view-damage', $data);
    }

    public function getWarehouseByProductID(Request $request)
    {
        $warehouses = DB::table('tbl_currentstock')
            ->join('tbl_warehouse', 'tbl_currentstock.tbl_wareHouseId', '=', 'tbl_warehouse.id')
            ->select('tbl_warehouse.id', 'tbl_warehouse.wareHouseName')
            ->where('tbl_currentstock.deleted', 'No')
            ->where('tbl_currentstock.tbl_productsId', $request->product_id)
            ->orderBy('tbl_warehouse.id', 'DESC')
            ->get();

        return $warehouses;
    }

    public function getStockByProductWarehouse(Request $request)
    {
        $currentStock = Currentstock::where('deleted', 'No')->where('tbl_productsId', $request->product_id)->where('tbl_wareHouseId', $request->warehouse_id)->pluck('currentStock');

        return $currentStock;
    }

    public function findCurrentStock(Request $request)
    {
        $product = Product::find($request->id);

        return $product->current_stock;
    }

    public function damageStore(Request $request)
    {
        $request->validate([
            'damage_quantity' => 'required|max:7|regex:/^\d+(\.\d{1,2})?$/',
            'remarks' => 'nullable|max:190|regex:/^([a-zA-Z0-9_ "\.\-\s\,\;\:\/\&\$\%\(\)]+\s)*[a-zA-Z0-9_ "\.\-\s\,\;\:\/\&\$\%\(\)]+$/u',
            'products_id' => 'required',
        ]);
        DB::beginTransaction();
        try {
            $damageOrderNo = DamageProduct::max('damage_order_no');
            $damageOrderNo++;
            $damageOrderNo = str_pad($damageOrderNo, 6, '0', STR_PAD_LEFT);
            $DamageProduct = new DamageProduct;
            $DamageProduct->products_id = $request->products_id;
            $DamageProduct->warehouse_id = $request->warehouse_id;
            $DamageProduct->damage_quantity = $request->damage_quantity;
            $DamageProduct->remarks = $request->remarks;
            $DamageProduct->damage_date = $request->damage_date;
            $DamageProduct->damage_order_no = $damageOrderNo;
            $DamageProduct->created_by = auth()->user()->id;
            $DamageProduct->created_date = Carbon::now();
            $DamageProduct->deleted = 'No';
            $DamageProduct->save();
            Product::find($request->products_id)->decrement('current_stock', $request->damage_quantity);
            if ($request->damage_quantity > 0 && $request->warehouse_id > 0) {
                $stockEntry = DB::table('tbl_currentstock')
                    ->where('tbl_productsId', $request->products_id)
                    ->where('tbl_wareHouseId', '=', $request->warehouse_id)
                    ->where('deleted', '=', 'No');
                if ($stockEntry->get()) {
                    $stockEntry->decrement('currentStock', $request->damage_quantity);
                    $stockEntry->increment('damageProducts', $request->damage_quantity);
                } else {
                    $currentStock = new Currentstock;
                    $currentStock->tbl_productsId = $request->products_id;
                    $currentStock->tbl_wareHouseId = $request->warehouse_id;
                    $currentStock->currentStock = -$request->damage_quantity;
                    $currentStock->damageProducts = $request->opening_stock;
                    $currentStock->entryBy = auth()->user()->id;
                    $currentStock->entryDate = date('Y-m-d H:i:s');
                    $currentStock->save();
                }
            }
            DB::commit();

            return response()->json(['success' => 'Product damage saved successfully']);
        } catch (Exception $e) {
            DB::rollBack();

            return response()->json(['error' => 'Purchase rollBack '.$e]);
        }
    }

    public function damageDelete(Request $request)
    {
        DB::beginTransaction();
        try {
            $DamageProduct = DamageProduct::find($request->id);
            $DamageProduct->deleted = 'Yes';
            $DamageProduct->deleted_by = auth()->user()->id;
            $DamageProduct->deleted_date = date('Y-m-d H:i:s');
            $DamageProduct->save();
            Product::find($DamageProduct->products_id)->increment('current_stock', $DamageProduct->damage_quantity);
            $stockEntry = DB::table('tbl_currentstock')
                ->where('tbl_productsId', $DamageProduct->products_id)
                ->where('tbl_wareHouseId', '=', $DamageProduct->warehouse_id)
                ->where('deleted', '=', 'No');
            if ($stockEntry->get()) {
                $stockEntry->increment('currentStock', $DamageProduct->damage_quantity);
                $stockEntry->increment('damageDelete', $DamageProduct->damage_quantity);
            } else {
                $currentStock = new Currentstock;
                $currentStock->tbl_productsId = $DamageProduct->products_id;
                $currentStock->tbl_wareHouseId = $DamageProduct->warehouse_id;
                $currentStock->currentStock = $DamageProduct->damage_quantity;
                $currentStock->damageDelete = $DamageProduct->damage_quantity;
                $currentStock->entryBy = auth()->user()->id;
                $currentStock->entryDate = date('Y-m-d H:i:s');
                $currentStock->save();
            }
            DB::commit();

            return response()->json(['success' => 'Damage Product deleted successfully']);
        } catch (Exception $e) {
            DB::rollBack();

            return response()->json(['error' => 'Damage Product Delete rollBack ']);
        }
    }

    public function createPDF($id)
    {
        $invoice = DB::table('damage_products')
            ->join('products', 'damage_products.products_id', '=', 'products.id')
            ->join('users', 'damage_products.created_by', '=', 'users.id')
            ->where([['damage_products.id', '=', $id], ['damage_products.deleted', '=', 'No']])
            ->select('damage_products.*', 'products.name', 'users.name as createdBy')
            ->where('damage_products.deleted', 'No')
            ->get();
        $pdf = PDF::loadView('admin.inventory.damage.damage-report', compact('invoice'));

        return $pdf->stream('damage-report-pdf.pdf', ['Attachment' => false]);
    }
}
