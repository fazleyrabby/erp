<?php

namespace App\Http\Controllers\Admin\Setup;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\inventory\Warehouse;
use App\Models\inventory\Category;
use App\Models\inventory\Product;
use App\Models\inventory\Currentstock;
use App\Models\inventory\WarehouseTransfer;
use Auth;
use Validator;
use Illuminate\Support\Facades\DB;


class CategoryController extends Controller
{
    function __construct()
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

    public function index()
    {
        return view('admin.setups.category.view-category');
    }

    public function getCategories()
    {
        $data = "";
        $categories = Category::where('deleted', 'No')->orderBy('id', 'DESC')->get();
        $output = array('data' => array());
        $i = 1;
        foreach ($categories as $category) {
            $status = "";
            if ($category->status == 'Active') {
                $status = '<center><i class="fas fa-check-circle" style="color:green; font-size:16px;" title="' . $category->status . '"></i></center>';
            } else {
                $status = '<center><i class="fas fa-times-circle" style="color:red; font-size:16px;" title="' . $category->status . '"></i></center>';
            }
            $imageUrl = url('upload/category_images/' . $category->image);
            /* $button = '<button type="button" onclick="editCategory('.$category->id.')" class="btn btn-xs btn-warning btnEdit" title="Edit Record" ><i class="fa fa-edit"> </i></button>
                        <button type="button" title="Delete" id="delete" class="btn btn-xs btn-danger btnDelete" onclick="confirmDelete('.$category->id.')" title="Delete Record"><i class="fa fa-trash"> </i></button>'; */
            $button = '<td style="width: 12%;">
                        <div class="btn-group">
                            <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
                                <i class="fas fa-cog"></i>  <span class="caret"></span></button>
                                <ul class="dropdown-menu dropdown-menu-right" style="border: 1px solid gray;" role="menu">
                                <li class="action" onclick="editCategory(' . $category->id . ')"  ><a  class="btn" ><i class="fas fa-edit"></i> Edit </a></li>
                                
                                <li class="action"><a   class="btn"  onclick="confirmDelete(' . $category->id . ')" ><i class="fas fa-trash-alt"></i> Delete </a></li>
                              
                                </ul>
                            </div>
                        </td>';

            $output['data'][] = array(
                $i++ . '<input type="hidden" name="id" id="id" value="' . $category->id . '" />',
                $category->name,
                '<img style="width:70px;" src="' . $imageUrl . '" alt="' . $category->name . '" />',
                $status,
                $button
            );
        }

        return $output;
    }
    public function add()
    {
        return Category::all();
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255|unique:categories,name|regex:/^([a-zA-Z0-9_ "\.\-\s\,\;\:\/\&\$\%\(\)]+\s)*[a-zA-Z0-9_ "\.\-\s\,\;\:\/\&\$\%\(\)]+$/u'
        ]);
        if ($request->hasFile('image')) {
            $request->validate([
                'image'   =>  'image|max:2048'
            ]);
            $categoryImage = $request->file('image');
            $name = $categoryImage->getClientOriginalName();
            $uploadPath = 'upload/category_images/';
            $imageUrl = $uploadPath . $name;
            $imageName = time() . $name;
            $categoryImage->move($uploadPath, $imageName);
        } else {
            $imageName = "no_image.png";
        }

        $category = new Category();
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
            'name' => 'required|max:255||regex:/^([a-zA-Z0-9_ "\.\-\s\,\;\:\/\&\$\%\(\)]+\s)*[a-zA-Z0-9_ "\.\-\s\,\;\:\/\&\$\%\(\)]+$/u|unique:categories,name,' . $request->id
        ]);
        $category = Category::find($request->id);
        $category->name = $request->name;
        if ($request->removeImage == "1") {
            $category->image = "no_image.png";
        } else if ($request->hasFile('image')) {
            $request->validate([
                'image'   =>  'image|max:2048'
            ]);
            $categoryImage = $request->file('image');
            $name = $categoryImage->getClientOriginalName();
            $uploadPath = 'upload/category_images/';
            $imageUrl = $uploadPath . $name;
            $imageName = time() . $name;
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
        $category->name = $category->name . '-Deleted-' . $request->id;
        $category->deleted_by = auth()->user()->id;
        $category->deleted_date = date('Y-m-d H:i:s');
        $category->save();
        return response()->json(['success' => 'Category deleted successfully']);
    }


    // Start Warehouse
    public function warehouse()
    {
        return view('admin.inventory.warehouse.warehouse');
    }

    public function getWarehouses()
    {
        $warehouses = DB::table('tbl_warehouse')
            ->where('tbl_warehouse.deleted', 'No')
            ->orderBy('tbl_warehouse.id', 'DESC')
            ->get();
        $output = array('data' => array());
        $i = 1;
        foreach ($warehouses as $warehouse) {
            $status = "";
            if ($warehouse->status == 'Active') {
                $status = '<center><i class="fas fa-check-circle" style="color:green; font-size:16px;"></i></center>';
            } else {
                $status = '<center><i class="fas fa-times-circle" style="color:red; font-size:16px;"></i></center>';
            }

            $button = '<td style="width: 12%;">
                        <div class="btn-group">
                            <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
                                <i class="fas fa-cog"></i>  <span class="caret"></span></button>
                                <ul class="dropdown-menu dropdown-menu-right" style="border: 1px solid gray;" role="menu">
                                <li class="action liDropDown" onclick="editWarehouse(' . $warehouse->id . ')"  ><a  class="btn" ><i class="fas fa-edit"></i> Edit </a></li>
                                </li>
                            </li>
                                <li class="action liDropDown"><a   class="btn"  onclick="confirmDelete(' . $warehouse->id . ')" ><i class="fas fa-trash-alt"></i> Delete </a></li>
                                </li> 
                                </ul>
                            </div>
                        </td>';

            $output['data'][] = array(
                $i++ . '<input type="hidden" name="id" id="id" value="' . $warehouse->id . '" />',
                $warehouse->wareHouseName,
                $warehouse->wareHouseAddress,
                $status,
                $button
            );
        }
        return $output;
    }

    public function storeWarehouse(Request $request)
    {
        $request->validate([
            'warehouseName' => 'required|max:255|regex:/^([a-zA-Z0-9_ "\.\-\s\,\;\:\/\&\$\%\(\)]+\s)*[a-zA-Z0-9_ "\.\-\s\,\;\:\/\&\$\%\(\)]+$/u',
            'description' => 'nullable|max:500|regex:/^([a-zA-Z0-9_ "\.\-\s\,\;\:\/\&\$\%\(\)]+\s)*[a-zA-Z0-9_ "\.\-\s\,\;\:\/\&\$\%\(\)]+$/u',
        ]);
        $warehouse = new Warehouse();
        $warehouse->wareHouseName = $request->warehouseName;
        $warehouse->wareHouseAddress = $request->description;
        $warehouse->createdBy  = auth()->user()->id;
        $warehouse->createdDate  = date('Y-m-d H:i:s');
        //$warehouse->save();
        return response()->json("Warehouse saved successfulluy!");
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
        $warehouse->lastUpdatedBy  = auth()->user()->id;
        $warehouse->lastUpdatedDate  = date('Y-m-d H:i:s');
        $warehouse->save();
        return response()->json("Warehouse updated successfulluy!");
    }

    public function deleteWarehouse(Request $request)
    {
        $warehouse = Warehouse::find($request->id);
        $warehouse->deleted = 'Yes';
        $warehouse->deletedBy  = auth()->user()->id;
        $warehouse->deletedDate  = date('Y-m-d H:i:s');
        $warehouse->save();
        return response()->json("Warehouse Delete successfulluy!");
    }
    // End Warehouse Section

    // Start Warehouse Transfer
    public function warehouseTransferView()
    {
        $data['warehouses'] = Warehouse::where('deleted', 'No')->where('status', 'Active')->get();
        $data['products'] = Product::where('deleted', 'No')->where('status', 'Active')->where('current_stock', '>', 0)->get();
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
            $warehouse_data .= '<option value="' . $warehouse->id . '">' . $warehouse->wareHouseName . '</option>';
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
            $WarehouseTransfer = new WarehouseTransfer();
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
            $Currentstock = Currentstock::where("tbl_productsId", $product_id)
                ->where("tbl_wareHouseId", $warehouse_id)
                ->where("deleted", 'No');
            if ($Currentstock->first()) {
                $Currentstock->increment('currentStock', $quantity);
                $Currentstock->increment('transferTo', $quantity);
            } else {
                $Currentstock_insert = new Currentstock();
                $Currentstock_insert->tbl_productsId = $product_id;
                $Currentstock_insert->tbl_wareHouseId = $warehouse_id;
                $Currentstock_insert->currentStock = $quantity;
                $Currentstock_insert->transferTo = $quantity;
                $Currentstock_insert->entryBy = auth()->user()->id;
                $Currentstock_insert->entryDate = date('Y-m-d H:i:s');
                $Currentstock_insert->save();
            }

            $warehouse_id = $request->warehouseFrom;
            $Currentstock = Currentstock::where("tbl_productsId", $product_id)
                ->where("tbl_wareHouseId", $warehouse_id)
                ->where("deleted", 'No');
            if ($Currentstock->first()) {
                $Currentstock->decrement('currentStock', $quantity);
                $Currentstock->increment('transferFrom', $quantity);
            } else {
                $Currentstock_insert = new Currentstock();
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
            return response()->json(['error' => 'Warehouse Transfer rollBack ' . $e]);
        }
    }

    public function viewWarehousesTransfer()
    {
        $data = "";
        $warehouseTransfers = DB::table('tbl_warehouse_transfer')
            ->join('tbl_warehouse', 'tbl_warehouse_transfer.tbl_current_warehouse_id', 'tbl_warehouse.id')
            ->join('tbl_warehouse as warehouse', 'tbl_warehouse_transfer.tbl_transfer_warehouse_id', 'warehouse.id')
            ->join('products', 'tbl_warehouse_transfer.tbl_products_id', 'products.id')
            ->where('tbl_warehouse_transfer.deleted', 'No')
            ->select('tbl_warehouse_transfer.id', 'tbl_warehouse_transfer.transfer_stock', 'warehouse.wareHouseName as warehouse_to', 'tbl_warehouse.wareHouseName as warehouse_from', 'products.name', 'tbl_warehouse_transfer.transferDate')
            ->get();
        //dd($warehouseTransfers);
        $output = array('data' => array());
        $i = 1;
        foreach ($warehouseTransfers as $warehouseTransfer) {
            $button = '<a   class="btn"  onclick="confirmDelete(' . $warehouseTransfer->id . ')" ><i class="fas fa-trash-alt"></i> </a>';
            $output['data'][] = array(
                $i++ . '<input type="hidden" name="id" id="id" value="' . $warehouseTransfer->id . '" />',
                'ProductName: ' . $warehouseTransfer->name . '<br>Date: ' . $warehouseTransfer->transferDate,
                $warehouseTransfer->warehouse_from,
                $warehouseTransfer->warehouse_to,
                $warehouseTransfer->transfer_stock,
                $button
            );
        }

        return $output;
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
        $Currentstock = Currentstock::where("tbl_productsId", $product_id)
            ->where("tbl_wareHouseId", $warehouse_id)
            ->where("deleted", 'No');
        if ($Currentstock->first()) {
            $Currentstock->decrement('currentStock', $quantity);
            $Currentstock->increment('transferToDelete', $quantity);
        } else {
            $Currentstock_insert = new Currentstock();
            $Currentstock_insert->tbl_productsId = $product_id;
            $Currentstock_insert->tbl_wareHouseId = $warehouse_id;
            $Currentstock_insert->currentStock = -$quantity;
            $Currentstock_insert->transferToDelete = $quantity;
            $Currentstock_insert->entryBy = auth()->user()->id;
            $Currentstock_insert->entryDate = date('Y-m-d H:i:s');
            $Currentstock_insert->save();
        }


        $warehouse_id = $warehouseFrom;
        $Currentstock = Currentstock::where("tbl_productsId", $product_id)
            ->where("tbl_wareHouseId", $warehouse_id)
            ->where("deleted", 'No');
        if ($Currentstock->first()) {
            $Currentstock->increment('currentStock', $quantity);
            $Currentstock->increment('transferFromDelete', $quantity);
        } else {
            $Currentstock_insert = new Currentstock();
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
