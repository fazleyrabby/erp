<?php

namespace App\Http\Controllers\Admin\Setup;

use App\Http\Controllers\Controller;
use App\Models\inventory\Category;
use App\Models\inventory\Currentstock;
use App\Models\inventory\Product;
use App\Models\inventory\Warehouse;
use App\Models\inventory\WarehouseTransfer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:categories.view', ['only' => ['index', 'getCategories', 'add']]);
        $this->middleware('permission:categories.store', ['only' => ['store']]);
        $this->middleware('permission:categories.edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:categories.delete', ['only' => ['delete']]);
        // Warehouse
        $this->middleware('permission:warehouse.view', ['only' => ['warehouse', 'getWarehouses']]);
        $this->middleware('permission:warehouse.store', ['only' => ['storeWarehouse']]);
        $this->middleware('permission:warehouse.edit', ['only' => ['editWarehouse', 'updateWarehouse']]);
        $this->middleware('permission:warehouse.delete', ['only' => ['deleteWarehouse']]);
    }

    public function index(Request $request)
    {
        $query = Category::where('deleted', 'No');

        if ($search = $request->q) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            });
        }

        $sortBy = $request->sort_by ?? 'id';
        $sortDir = $request->sort_direction ?? 'DESC';
        $limit = $request->limit ?? 10;

        $categories = $query->orderBy($sortBy, $sortDir)->paginate($limit)->appends($request->all());

        return view('admin.setups.category.view-category', compact('categories'));
    }

    public function add()
    {
        return Category::all();
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255|unique:categories,name|regex:/^([a-zA-Z0-9_ "\.\-\s\,\;\:\/\&\$\%\(\)]+\s)*[a-zA-Z0-9_ "\.\-\s\,\;\:\/\&\$\%\(\)]+$/u',
        ]);
        if ($request->hasFile('image')) {
            $request->validate([
                'image' => 'image|max:2048',
            ]);
            $categoryImage = $request->file('image');
            $name = $categoryImage->getClientOriginalName();
            $uploadPath = 'upload/category_images/';
            $imageUrl = $uploadPath.$name;
            $imageName = time().$name;
            $categoryImage->move($uploadPath, $imageName);
        } else {
            $imageName = 'no_image.png';
        }

        $category = new Category;
        $category->name = $request->name;
        $category->image = $imageName;
        $category->created_by = auth()->user()->id;
        $category->created_date = date('Y-m-d H:i:s');
        $category->deleted = 'No';
        $category->save();

        return response()->json(['success' => 'Category saved successfully']);
    }

    public function edit(Request $request)
    {
        $category = Category::find($request->id);

        return $category;
    }

    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255||regex:/^([a-zA-Z0-9_ "\.\-\s\,\;\:\/\&\$\%\(\)]+\s)*[a-zA-Z0-9_ "\.\-\s\,\;\:\/\&\$\%\(\)]+$/u|unique:categories,name,'.$request->id,
        ]);
        $category = Category::find($request->id);
        $category->name = $request->name;
        if ($request->removeImage == '1') {
            $category->image = 'no_image.png';
        } elseif ($request->hasFile('image')) {
            $request->validate([
                'image' => 'image|max:2048',
            ]);
            $categoryImage = $request->file('image');
            $name = $categoryImage->getClientOriginalName();
            $uploadPath = 'upload/category_images/';
            $imageUrl = $uploadPath.$name;
            $imageName = time().$name;
            $categoryImage->move($uploadPath, $imageName);
            $category->image = $imageName;
        }

        $category->status = $request->Status;
        $category->updated_by = auth()->user()->id;
        $category->updated_date = date('Y-m-d H:i:s');
        $category->save();

        return response()->json(['success' => 'Category updated successfully']);
    }

    public function delete(Request $request)
    {
        $category = Category::find($request->id);
        $category->deleted = 'Yes';
        $category->name = $category->name.'-Deleted-'.$request->id;
        $category->deleted_by = auth()->user()->id;
        $category->deleted_date = date('Y-m-d H:i:s');
        $category->save();

        return response()->json(['success' => 'Category deleted successfully']);
    }

    // Start Warehouse
    public function warehouse(Request $request)
    {
        $query = DB::table('tbl_warehouse')
            ->where('tbl_warehouse.deleted', 'No');

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('wareHouseName', 'like', "%{$s}%")
                  ->orWhere('wareHouseAddress', 'like', "%{$s}%");
            });
        }

        $sortBy = $request->input('sort', 'id');
        $direction = strtoupper($request->input('direction', 'DESC')) === 'ASC' ? 'ASC' : 'DESC';
        $query->orderBy($sortBy, $direction);

        $data['warehouses'] = $query->paginate($request->input('per_page', 20))->withQueryString();

        return view('admin.inventory.warehouse.warehouse', $data);
    }

    public function storeWarehouse(Request $request)
    {
        $request->validate([
            'warehouseName' => 'required|max:255|regex:/^([a-zA-Z0-9_ "\.\-\s\,\;\:\/\&\$\%\(\)]+\s)*[a-zA-Z0-9_ "\.\-\s\,\;\:\/\&\$\%\(\)]+$/u',
            'description' => 'nullable|max:500|regex:/^([a-zA-Z0-9_ "\.\-\s\,\;\:\/\&\$\%\(\)]+\s)*[a-zA-Z0-9_ "\.\-\s\,\;\:\/\&\$\%\(\)]+$/u',
        ]);
        $warehouse = new Warehouse;
        $warehouse->wareHouseName = $request->warehouseName;
        $warehouse->wareHouseAddress = $request->description;
        $warehouse->createdBy = auth()->user()->id;
        $warehouse->createdDate = date('Y-m-d H:i:s');

        // $warehouse->save();
        return response()->json('Warehouse saved successfulluy!');
    }

    public function editWarehouse(Request $request)
    {
        $warehouse = Warehouse::find($request->id);

        return $warehouse;
    }

    public function updateWarehouse(Request $request)
    {
        $request->validate([
            'id' => 'required',
            'warehouseName' => 'required|max:255|regex:/^([a-zA-Z0-9_ "\.\-\s\,\;\:\/\&\$\%\(\)]+\s)*[a-zA-Z0-9_ "\.\-\s\,\;\:\/\&\$\%\(\)]+$/u',
            'description' => 'nullable|max:500|regex:/^([a-zA-Z0-9_ "\.\-\s\,\;\:\/\&\$\%\(\)]+\s)*[a-zA-Z0-9_ "\.\-\s\,\;\:\/\&\$\%\(\)]+$/u',
            'status' => 'required',
        ]);

        $warehouse = Warehouse::find($request->id);
        $warehouse->wareHouseName = $request->warehouseName;
        $warehouse->wareHouseAddress = $request->description;
        $warehouse->status = $request->status;
        $warehouse->lastUpdatedBy = auth()->user()->id;
        $warehouse->lastUpdatedDate = date('Y-m-d H:i:s');
        $warehouse->save();

        return response()->json('Warehouse updated successfulluy!');
    }

    public function deleteWarehouse(Request $request)
    {
        $warehouse = Warehouse::find($request->id);
        $warehouse->deleted = 'Yes';
        $warehouse->deletedBy = auth()->user()->id;
        $warehouse->deletedDate = date('Y-m-d H:i:s');
        $warehouse->save();

        return response()->json('Warehouse Delete successfulluy!');
    }
    // End Warehouse Section

    // Start Warehouse Transfer
    public function warehouseTransferView(Request $request)
    {
        $data['warehouses'] = Warehouse::where('deleted', 'No')->where('status', 'Active')->get();
        $data['products'] = Product::where('deleted', 'No')->where('status', 'Active')->where('current_stock', '>', 0)->get();

        $query = DB::table('tbl_warehouse_transfer')
            ->join('tbl_warehouse', 'tbl_warehouse_transfer.tbl_current_warehouse_id', 'tbl_warehouse.id')
            ->join('tbl_warehouse as warehouse', 'tbl_warehouse_transfer.tbl_transfer_warehouse_id', 'warehouse.id')
            ->join('products', 'tbl_warehouse_transfer.tbl_products_id', 'products.id')
            ->where('tbl_warehouse_transfer.deleted', 'No')
            ->select('tbl_warehouse_transfer.id', 'tbl_warehouse_transfer.transfer_stock', 'tbl_warehouse_transfer.transferDate', 'warehouse.wareHouseName as warehouse_to', 'tbl_warehouse.wareHouseName as warehouse_from', 'products.name');

        if ($request->filled('q')) {
            $s = $request->q;
            $query->where(function ($q) use ($s) {
                $q->where('products.name', 'like', "%{$s}%")
                  ->orWhere('tbl_warehouse.wareHouseName', 'like', "%{$s}%")
                  ->orWhere('warehouse.wareHouseName', 'like', "%{$s}%");
            });
        }

        $sortBy = $request->input('sort_by', 'id');
        $direction = strtoupper($request->input('sort_direction', 'DESC')) === 'ASC' ? 'ASC' : 'DESC';
        $query->orderBy($sortBy, $direction);

        $data['transfers'] = $query->paginate($request->input('limit', 10))->withQueryString();

        return view('admin.inventory.warehouse.warehouseTransfer', $data);
    }

    public function productwiseWarehouse(Request $request)
    {
        $product_id = $request->product_id;
        $warehouses = DB::table('tbl_currentstock')
            ->join('tbl_warehouse', 'tbl_currentstock.tbl_wareHouseId', '=', 'tbl_warehouse.id')
            ->where('tbl_currentstock.tbl_productsId', $product_id)
            ->select('tbl_warehouse.id', 'tbl_warehouse.wareHouseName')
            ->get();
        $warehouse_data = '';
        foreach ($warehouses as $warehouse) {
            $warehouse_data .= '<option value="'.$warehouse->id.'">'.$warehouse->wareHouseName.'</option>';
        }

        return $warehouse_data;
    }

    public function warehousewiseProductStock(Request $request)
    {
        $warehouse_id = $request->warehouse_id;
        $product_id = $request->product_id;
        $currentStock = Currentstock::where('tbl_productsId', $product_id)->where('tbl_wareHouseId', $warehouse_id)->where('deleted', 'No')->first();
        if ($currentStock != null) {
            return $currentStock->currentStock;
        } else {
            return 0;
        }
    }

    public function warehouseTransferStore(Request $request)
    {
        DB::beginTransaction();
        try {
            $WarehouseTransfer = new WarehouseTransfer;
            $WarehouseTransfer->transferDate = $request->transferDate;
            $WarehouseTransfer->tbl_current_warehouse_id = $request->warehouseFrom;
            $WarehouseTransfer->tbl_products_id = $request->product;
            $WarehouseTransfer->current_stock = $request->currentStock;
            $WarehouseTransfer->tbl_transfer_warehouse_id = $request->warehouseTo;
            $WarehouseTransfer->transfer_stock = $request->transferStock;
            $WarehouseTransfer->entryBy = auth()->user()->id;
            $WarehouseTransfer->entryDate = date('Y-m-d H:i:s');
            $WarehouseTransfer->save();

            $product_id = $request->product;
            $quantity = $request->transferStock;
            $warehouse_id = $request->warehouseTo;
            $Currentstock = Currentstock::where('tbl_productsId', $product_id)
                ->where('tbl_wareHouseId', $warehouse_id)
                ->where('deleted', 'No');
            if ($Currentstock->first()) {
                $Currentstock->increment('currentStock', $quantity);
                $Currentstock->increment('transferTo', $quantity);
            } else {
                $Currentstock_insert = new Currentstock;
                $Currentstock_insert->tbl_productsId = $product_id;
                $Currentstock_insert->tbl_wareHouseId = $warehouse_id;
                $Currentstock_insert->currentStock = $quantity;
                $Currentstock_insert->transferTo = $quantity;
                $Currentstock_insert->entryBy = auth()->user()->id;
                $Currentstock_insert->entryDate = date('Y-m-d H:i:s');
                $Currentstock_insert->save();
            }

            $warehouse_id = $request->warehouseFrom;
            $Currentstock = Currentstock::where('tbl_productsId', $product_id)
                ->where('tbl_wareHouseId', $warehouse_id)
                ->where('deleted', 'No');
            if ($Currentstock->first()) {
                $Currentstock->decrement('currentStock', $quantity);
                $Currentstock->increment('transferFrom', $quantity);
            } else {
                $Currentstock_insert = new Currentstock;
                $Currentstock_insert->tbl_productsId = $product_id;
                $Currentstock_insert->tbl_wareHouseId = $warehouse_id;
                $Currentstock_insert->currentStock = -$quantity;
                $Currentstock_insert->transferFrom = $quantity;
                $Currentstock_insert->entryBy = auth()->user()->id;
                $Currentstock_insert->entryDate = date('Y-m-d H:i:s');
                $Currentstock_insert->save();
            }

            DB::commit();

            return response()->json(['success' => 'Warehouse Transfer successfully']);
        } catch (Exception $e) {
            DB::rollBack();

            return response()->json(['error' => 'Warehouse Transfer rollBack '.$e]);
        }
    }


    public function deleteWarehouseTransfer(Request $request)
    {
        $id = $request->id;
        $WarehouseTransfer = WarehouseTransfer::find($id);
        $WarehouseTransfer->deleted = 'Yes';
        $WarehouseTransfer->deletedBy = auth()->user()->id;
        $WarehouseTransfer->deletedDate = date('Y-m-d H:i:s');
        $WarehouseTransfer->save();

        $warehouseFrom = $WarehouseTransfer->tbl_current_warehouse_id;
        $warehouseTo = $WarehouseTransfer->tbl_transfer_warehouse_id;

        $product_id = $WarehouseTransfer->tbl_products_id;
        $quantity = $WarehouseTransfer->transfer_stock;
        $warehouse_id = $warehouseTo;
        $Currentstock = Currentstock::where('tbl_productsId', $product_id)
            ->where('tbl_wareHouseId', $warehouse_id)
            ->where('deleted', 'No');
        if ($Currentstock->first()) {
            $Currentstock->decrement('currentStock', $quantity);
            $Currentstock->increment('transferToDelete', $quantity);
        } else {
            $Currentstock_insert = new Currentstock;
            $Currentstock_insert->tbl_productsId = $product_id;
            $Currentstock_insert->tbl_wareHouseId = $warehouse_id;
            $Currentstock_insert->currentStock = -$quantity;
            $Currentstock_insert->transferToDelete = $quantity;
            $Currentstock_insert->entryBy = auth()->user()->id;
            $Currentstock_insert->entryDate = date('Y-m-d H:i:s');
            $Currentstock_insert->save();
        }

        $warehouse_id = $warehouseFrom;
        $Currentstock = Currentstock::where('tbl_productsId', $product_id)
            ->where('tbl_wareHouseId', $warehouse_id)
            ->where('deleted', 'No');
        if ($Currentstock->first()) {
            $Currentstock->increment('currentStock', $quantity);
            $Currentstock->increment('transferFromDelete', $quantity);
        } else {
            $Currentstock_insert = new Currentstock;
            $Currentstock_insert->tbl_productsId = $product_id;
            $Currentstock_insert->tbl_wareHouseId = $warehouse_id;
            $Currentstock_insert->currentStock = $quantity;
            $Currentstock_insert->transferFromDelete = $quantity;
            $Currentstock_insert->entryBy = auth()->user()->id;
            $Currentstock_insert->entryDate = date('Y-m-d H:i:s');
            $Currentstock_insert->save();
        }
    }
    // End Warehouse Transfer

}
