@extends('admin.master')
@section('title')
    {{ Session::get('companySettings')[0]['name'] }} Products
@endsection
@section('content')
    <style type="text/css">


    </style>
    <div class="content-wrapper">
        <section class="content box-border">
            <div class="card">
                <div class="card-header">
                    <h3 style="float:left;"> Product List </h3>
                    <a class="btn btn-cyan float-right" onclick="create()"><i class="fa fa-plus circle"></i> Add
                        Product
                    </a>
                    <a class="btn btn-cyan float-right" onclick="createService()" style="margin-right:10px;">
                        <i class="fa fa-plus circle"></i> 
                        Add Service
                    </a>
                    <!-- <a class="btn btn-primary" style="margin-left:20px;" onclick="reloadDt()">
                        <i class="fas fa-sync"></i> Refresh 
                    </a> -->
                </div><!-- /.card-header -->
                <div class="card-body">
                    <!--data listing table-->
                    <div class="table-responsive">
                        <table id="manageProductTable" width="100%" class="table table-bordered table-hover ">
                            <thead>
                                <tr>
                                    <td width="5%">SL#</td>
                                    <td width="30%">Product Info</td>
                                    <td width="21%">Product Info</td>
                                    <td width="10%">Image</td>
                                    <td width="10%">Stock</td>
                                    <td width="12%">Price</td>
                                    <td width="5%">Status</td>
                                    <td width="7%">Actions</td>
                                </tr>
                            </thead>
                        </table>
                        <!--data listing table-->
                    </div>
                </div>
            </div>
        </section>
    </div>


    <!-- modal -->
    <div class="modal fade" id="modal">
        <div class="modal-dialog" style="max-width: 80%;" role="document">
            <!-- style, added by Md Hamid -->
            <div class="modal-content">
                <div class="modal-header float-left">
                    <h4 class="modal-title float-left"> Add Product</h4>

                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i
                            class="fas fa-window-close"></i></button>
                </div>
                <div class="modal-body">
                    <form id="productForm" method="POST" enctype="multipart/form-data" action="#">
                        @csrf
                        <div class="row">
                            <div class="form-group col-md-3">
                                <label> Category <span class="text-danger"> * </span></label><br>
                                <select name="category_id" id="category_id" class="form-control input-sm">
                                    <option value="" selected="selected">Select Category</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                                <span class="text-danger" id="categoryError"></span>
                            </div>
                            <div class="form-group col-md-3">
                                <label> Brand <span class="text-danger"> * </span></label><br>
                                <select name="brand_id" id="brand_id" class="form-control input-sm">
                                    <option value="" selected>Select Brand</option>
                                    @foreach ($brands as $brand)
                                        <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                                    @endforeach
                                </select>
                                <span class="text-danger" id="brandError"></span>
                            </div>
                            <div class="form-group col-md-6">
                                <label> Product Name <span class="text-danger"> * </span></label>
                                <input class="form-control input-sm" id="name" type="text" name="name"
                                    placeholder=" Product name">
                                <span class="text-danger" id="nameError"></span>
                            </div>
                            @if (Session::get('companySettings')[0]['barcode_exists'] == 'Yes')
                                <div class="form-group col-md-6">
                                    <label>Barcode</label>
                                    <input class="form-control input-sm" id="barcode_no" type="text" name="barcode_no">
                                    <span class="text-danger" id="barcode_noError"></span>
                                </div>
                            @endif
                            <div class="form-group col-md-3">
                                <label>Model No <span class="text-danger"> * </span></label>
                                <input class="form-control input-sm" id="model_no" type="text" name="model_no"
                                    placeholder=" Model Number ">
                                <span class="text-danger" id="model_noError"></span>
                            </div>
                            <div class="form-group col-md-3">
                                <label> Unit <span class="text-danger"> * </span></label>
                                <select name="unit_id" id="unit_id" class="form-control input-sm">
                                    <option value="" selected="selected"> Select Unit </option>
                                    @foreach ($units as $unit)
                                        <option value="{{ $unit->id }}">{{ $unit->name }}</option>
                                    @endforeach
                                </select>
                                <span class="text-danger" id="unitError"></span>
                            </div>
                            <div class="form-group col-md-3">
                                <label>Purchase Price <span class="text-danger"> * </span></label>
                                <input class="form-control input-sm" id="minimum_price" type="number"
                                    name="minimum_price" placeholder=" Minimum price " min="0">
                                <span class="text-danger" id="minimum_priceError"></span>
                            </div>
                            <div class="form-group col-md-3">
                                <label>Sell price <span class="text-danger"> * </span></label>
                                <input class="form-control input-sm" id="maximum_price" type="number"
                                    name="maximum_price" placeholder=" Maximum price " min="0">
                                <span class="text-danger" id="maximum_priceError"></span>
                            </div>
                            <div class="form-group col-md-3 d-none">
                                <label> Discount Amount</label>
                                <input class="form-control input-sm" id="discount" type="text" value=""
                                    name="discount">
                                <span class="text-danger" id="discountError"></span>
                            </div>
                            <div class="form-group col-md-3">
                                <label> Opening Stock <span class="text-danger"> * </span></label>
                                <input class="form-control input-sm openingStock" min="0" id="opening_stock"
                                    type="number" name="opening_stock" placeholder="opening stock"
                                    onchange="checkType()">
                                <span class="text-danger" id="opening_stockError"></span>
                            </div>
                            <div class="form-group col-md-3">
                                <label> Reminder Stock <span class="text-danger"> * </span></label>
                                <input class="form-control input-sm" min="0" id="remainder_quantity"
                                    type="number" name="remainder_quantity">
                                <span class="text-danger" id="remainder_quantityError"></span>
                            </div>
                            <div class="form-group col-md-3">
                                <label> Select Warehouse <span class="text-danger"> * </span></label>
                                <select class="form-control input-sm" id="stock_warehouse" name="stock_warehouse">
                                    <option value="" selected> Select Warehouse </option>
                                    @foreach ($warehouses as $warehouse)
                                        <option value="{{ $warehouse->id }}">{{ $warehouse->wareHouseName }}
                                        </option>
                                    @endforeach
                                </select>
                                <span class="text-danger" id="stock_warehouseError"></span>
                            </div>
                            <div class="form-group col-md-3">
                                <label>Notes</label>
                                <input class="form-control input-sm" id="notes" type="text" name="notes"
                                    placeholder=" notes about product ">
                                <span class="text-danger" id="notesError"></span>
                            </div>
                            {{-- serializ --}}
                            <div class="form-group col-md-3">
                                <label> Type <span class="text-danger"> * </span></label>
                                <select id="type" name="type" class="form-control input-sm"
                                    onchange="checkType()">
                                    <option value="regular"> Regular </option>
                                    <option value="serialize"> Serialize </option>
                                  
                                </select>
                                <span class="text-danger" id="typeError"></span>
                            </div>
                            <div class="form-group col-md-3">
                                <label> Stock Check <span class="text-danger"> * </span></label>
                                <select id="stockCheck" name="stockCheck" class="form-control input-sm">
                                    <option value="No"> No </option>
                                    <option value="Yes"> Yes</option>
                                </select>
                                <span class="text-danger" id="stockCheckError"></span>
                            </div>
                            <div class="row col-md-6">
                                <div class="form-group col-md-6">
                                    <label>Items In Box: <span class="text-danger"></span></label>
                                    <input class="form-control input-sm serialize" id="itemsInBox" type="number"
                                        min="0" name="itemsInBox" placeholder=" Number " onchange="checkType()"
                                        disabled>
                                    <span class="text-danger" id="itemsInBoxError"></span>
                                </div>
                                <div class="form-group col-md-6 d-none" id="showBtn">
                                    <label style="color: white;">.</label>
                                    <button type="button" class="btn btn-success form-control " onclick="checkType()"><i
                                            class="fa fa-table"></i>
                                        Show Serialize Table</button>
                                </div>
                            </div>
                            {{-- serializ --}}
                            <div class="form-group col-md-12">
                                <div class="row">
                                    <div class="form-group col-md-3">
                                        <label for="">Image</label>
                                        <input type="file" name="image" id="image"
                                            class="form-control form-control-sm">
                                        <span class="text-danger" id="imageError"></span>

                                        <div class="row">
                                            <div class="form-group col-12">
                                                <img id="showImage" src=" {{ asset('upload/no_image.png') }} "
                                                    alt="Image Not Found"
                                                    style="width: 60%;height: 90px; border:1px solid #000000;margin: 2% 0% 0% 0%;">
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Start spec -->
                                    <div class=" col-md-9" id="specSection">
                                        <div class="row">
                                            <div class=" col-4">
                                                <label> Product Specification</label>
                                                <input class="form-control input-sm" id="specName" type="text"
                                                    name="spec" placeholder=" Name">
                                                <span class="text-danger" id="specError"></span>
                                            </div>
                                            <div class=" col-4">
                                                <label>Value </label>
                                                <input class="form-control input-sm" id="specValue" type="text"
                                                    name="spec" placeholder=" Value">
                                                <span class="text-danger" id="specError"></span>
                                            </div>
                                            <div class=" col-4">
                                                <button type="button" class="btn btn-cyan btn-md add mt-4 "
                                                    onclick="addNewSpecRow();"><span class="glyphicon glyphicon-plus"
                                                        style="font-size: 18px; font-weight:800;"><strong>+</strong></span></button>
                                            </div>
                                            <!--new spec append here...-->
                                        </div>
                                    </div>
                                    <!-- End spec -->
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary mr-auto" data-dismiss="modal">x
                                Close</button>
                            <button type="submit" class="btn btn-cyan " id="saveProduct"><i class="fa fa-save"></i>
                                Save Product</button>
                        </div>
                    </form>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->




     <!-- service modal -->
     <div class="modal fade" id="servicemodal">
        <div class="modal-dialog" style="max-width: 80%;" role="document">
            <!-- style, added by Md Hamid -->
            <div class="modal-content">
                <div class="modal-header float-left">
                    <h4 class="modal-title float-left"> Add Service</h4>

                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i
                            class="fas fa-window-close"></i></button>
                </div>
                <div class="modal-body">
                    
                       
                        <div class="row">
                           
                            <div class="form-group col-md-6">
                                <label> Service Name <span class="text-danger"> * </span></label>
                                <input class="form-control input-sm" id="service_name" type="text" name="service_name"
                                    placeholder=" Service name">
                                <span class="text-danger" id="service_nameError"></span>
                            </div>
                            @if (Session::get('companySettings')[0]['barcode_exists'] == 'Yes')
                                <div class="form-group col-md-6 d-none">
                                    <label>Barcode</label>
                                    <input class="form-control input-sm" id="barcode_no" type="text" name="barcode_no">
                                    <span class="text-danger" id="barcode_noError"></span>
                                </div>
                            @endif
                            <div class="form-group col-md-3 d-none">
                                <label>Model No <span class="text-danger"> * </span></label>
                                <input class="form-control input-sm" id="model_no" type="text" name="model_no"
                                    placeholder=" Model Number "  value="0123">
                                <span class="text-danger" id="model_noError"></span>
                            </div>
                            <div class="form-group col-md-3 d-none">
                                <label> Unit <span class="text-danger"> * </span></label>
                                <select name="unit_id" id="unit_id" class="form-control input-sm">
                                    <option value="No unit" selected="selected"> Select Unit </option>
                                    @foreach ($units as $unit)
                                        <option value="{{ $unit->id }}">{{ $unit->name }}</option>
                                    @endforeach
                                </select>
                                <span class="text-danger" id="unitError"></span>
                            </div>
                            <div class="form-group col-md-3">
                                <label>Minimum Price <span class="text-danger"> * </span></label>
                                <input class="form-control input-sm" id="serviceminimum_price" type="number"
                                    name="serviceminimum_price" placeholder=" Minimum price " min="0">
                                <span class="text-danger" id="serviceminimum_priceError"></span>
                            </div>
                            <div class="form-group col-md-3">
                                <label>Maximum Price <span class="text-danger"> * </span></label>
                                <input class="form-control input-sm" id="servicemaximum_price" type="number"
                                    name="servicemaximum_price" placeholder=" Maximum price " min="0">
                                <span class="text-danger" id="servicemaximum_priceError"></span>
                            </div>
                            <div class="form-group col-md-3 d-none">
                                <label> Discount Amount</label>
                                <input class="form-control input-sm" id="discount" type="text" value="0.00"
                                    name="discount">
                                <span class="text-danger" id="discountError"></span>
                            </div>
                            <div class="form-group col-md-3 d-none">
                                <label> Opening Stock <span class="text-danger"> * </span></label>
                                <input class="form-control input-sm openingStock" min="0" id="opening_stock"
                                    type="number" name="opening_stock" placeholder="opening stock"
                                    onchange="checkType()" value="0">
                                <span class="text-danger" id="opening_stockError"></span>
                            </div>
                            <div class="form-group col-md-3 d-none">
                                <label> Reminder Stock <span class="text-danger"> * </span></label>
                                <input class="form-control input-sm" min="0" id="remainder_quantity"
                                    type="number" name="remainder_quantity" value="0">
                                <span class="text-danger" id="remainder_quantityError"></span>
                            </div>
                            <div class="form-group col-md-3 d-none">
                                <label> Select Warehouse <span class="text-danger"> * </span></label>
                                <select class="form-control input-sm" id="stock_warehouse" name="stock_warehouse">
                                    <option value="No Warehouse" selected> Select Warehouse </option>
                                    @foreach ($warehouses as $warehouse)
                                        <option value="{{ $warehouse->id }}">{{ $warehouse->wareHouseName }}
                                        </option>
                                    @endforeach
                                </select>
                                <span class="text-danger" id="stock_warehouseError"></span>
                            </div>
                            
                            {{-- serializ --}}
                            <div class="form-group col-md-3 d-none">
                                <label> Type <span class="text-danger"> * </span></label>
                                <select id="servicetype" name="servicetype" class="form-control input-sm"
                                    onchange="checkType()">
                                  
                                    <option value="service" selected> Service </option>
                                </select>
                                <span class="text-danger" id="typeError"></span>
                            </div>
                            <div class="form-group col-md-3 d-none">
                                <label> Stock Check <span class="text-danger"> * </span></label>
                                <select id="stockCheck" name="stockCheck" class="form-control input-sm">
                                    <option value="No" selected> No </option>
                                    <option value="Yes"> Yes</option>
                                </select>
                                <span class="text-danger" id="stockCheckError"></span>
                            </div>
                            <div class="row col-md-6">
                                <div class="form-group col-md-6 d-none">
                                    <label>Items In Box: <span class="text-danger"></span></label>
                                    <input class="form-control input-sm serialize" id="itemsInBox" type="number"
                                        min="0" name="itemsInBox" placeholder=" Number " onchange="checkType()"
                                        disabled>
                                    <span class="text-danger" id="itemsInBoxError"></span>
                                </div>
                                <div class="form-group col-md-6 d-none" id="showBtn">
                                    <label style="color: white;">.</label>
                                    <button type="button" class="btn btn-cyan form-control " onclick="checkType()"><i
                                            class="fa fa-table"></i>
                                        Show Serialize Table</button>
                                </div>
                            </div>
                            {{-- serializ --}}
                            <div class="form-group col-md-12">
                                <div class="row">
                                    <div class="form-group col-md-3 d-none">
                                        <label for="">Image</label>
                                        <input type="file" name="serviceimage" id="serviceimage"
                                            class="form-control form-control-sm">
                                        <span class="text-danger" id="serviceimageError"></span>

                                        <div class="row">
                                            <div class="form-group col-12">
                                                <img id="showImage" src=" {{ asset('upload/no_image.png') }} "
                                                    alt="Image Not Found"
                                                    style="width: 60%;height: 90px; border:1px solid #000000;margin: 2% 0% 0% 0%;">
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Start spec -->
                                    <div class="form-group col-md-3 ">
                                        <label>Notes</label>
                                        <input class="form-control input-sm" id="servicenotes" type="text" name="servicenotes"
                                            placeholder=" Notes about service... ">
                                        <span class="text-danger" id="servicenotesError"></span>
                                    </div>
                                    <div class=" col-md-9" id="specSection">
                                        <div class="row">
                                            <div class=" col-5">
                                                <label> Product Specification</label>
                                                <input class="form-control input-sm" id="servicespecName" type="text"
                                                    name="servicespec" placeholder=" Name">
                                                <span class="text-danger" id="specError"></span>
                                            </div>
                                            <div class=" col-5">
                                                <label>Value </label>
                                                <input class="form-control input-sm" id="servicespecValue" type="text"
                                                    name="servicespec" placeholder="Value">
                                                <span class="text-danger" id="specError"></span>
                                            </div>
                                            <div class=" col-2">
                                                <button type="button" class="btn btn-cyan btn-md add mt-4 "
                                                    onclick="addNewSpecRow();"><span class="glyphicon glyphicon-plus"
                                                        style="font-size: 18px; font-weight:800;"><strong>+</strong></span></button>
                                            </div>
                                            <!--new spec append here...-->
                                        </div>
                                    </div>
                                   
                                    <!-- End spec -->
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary mr-auto" data-dismiss="modal">x
                                Close</button>
                            <button  class="btn btn-cyan "  onclick="saveService()"><i class="fa fa-save"></i>
                                Save Service</button>
                        </div>
                    
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->



    <!-- edit modal -->
    <div class="modal fade" id="editModal">
        <div class="modal-dialog" style="max-width: 80%;">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Update Product</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i
                            class="fas fa-window-close"></i></button>
                </div>
                <div class="modal-body">

                    <form id="editProductForm" method="POST" enctype="multipart/form-data" action="#">
                        @csrf
                        <div class="row">
                            <input type="hidden" name="editId" id="editId">
                            <div class="form-group col-md-3" id="CategoryDiv">
                                <label> Category <span class="text-danger"> * </span></label><br>
                                <select name="editCategory" id="editCategory" class="form-control input-sm">
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                                <span class="text-danger" id="editCategoryError"></span>
                            </div>
                            <div class="form-group col-md-3" id="BrandDiv">
                                <label> Brand <span class="text-danger"> * </span></label><br>
                                <select name="editBrand" id="editBrand" class="form-control input-sm">
                                    @foreach ($brands as $brand)
                                        <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                                    @endforeach
                                </select>
                                <span class="text-danger" id="editBrandError"></span>
                            </div>
                            <div class="form-group col-md-6">
                                <label> Product Name <span class="text-danger"> * </span></label>
                                <input class="form-control input-sm" id="editName" type="text" name="editName">
                                <span class="text-danger" id="editNameError"></span>
                            </div>
                            @if (Session::get('companySettings')[0]['barcode_exists'] == 'Yes')
                                <div class="form-group col-md-6">
                                    <label>Barcode</label>
                                    <input class="form-control input-sm" id="editBarcode" type="text"
                                        name="editBarcode">
                                    <span class="text-danger" id="editBarcodeError"></span>
                                </div>
                            @endif
                            <div class="form-group col-md-3" id="UnitDiv">
                                <label> Unit <span class="text-danger"> * </span></label>
                                <select name="editUnit" id="editUnit" class="form-control input-sm">
                                    @foreach ($units as $unit)
                                        <option value="{{ $unit->id }}">{{ $unit->name }}</option>
                                    @endforeach
                                </select>
                                <span class="text-danger" id="editUnitError"></span>
                            </div>

                            <div class="form-group col-md-3" id="ModelDiv">
                                <label>Model No <span class="text-danger"> * </span></label>
                                <input class="form-control input-sm" id="editModelNo" type="text" name="editModelNo">
                                <span class="text-danger" id="editModelNoError"></span>
                            </div>
                            <div class="form-group col-md-3" >
                                <label>Purchase Price <span class="text-danger"> * </span></label>
                                <input class="form-control input-sm" id="editMinimumPrice" type="number"
                                    name="editMinimumPrice" placeholder=" Minimum price ">
                                <span class="text-danger" id="editMinimumPriceError"></span>
                            </div>
                            <div class="form-group col-md-3">
                                <label>Sell Price <span class="text-danger"> * </span></label>
                                <input class="form-control input-sm" id="editMaximumPrice" type="number"
                                    name="editMaximumPrice" placeholder=" Maximum price ">
                                <span class="text-danger" id="editMaximumPriceError"></span>
                            </div>

                            <div class="form-group col-md-3 d-none">
                                <label> Discount Amount</label>
                                <input class="form-control input-sm" id="editDiscount" type="text"
                                    name="editDiscount">
                                <span class="text-danger" id="editDiscountError"></span>
                            </div>
                            <div class="form-group col-md-3" id="OpeningStockDiv">
                                <label> Opening Stock </label>
                                <input class="form-control input-sm" id="editOpenStock" type="number"
                                    name="editOpenStock" disabled>
                                <span class="text-danger" id="editOpenStockError"></span>
                            </div>
                            <div class="form-group col-md-3" id="ReminderStockDiv">
                                <label> Reminder Stock <span class="text-danger"> * </span></label>
                                <input class="form-control input-sm" id="editRemainder" type="number"
                                    name="editRemainder">
                                <span class="text-danger" id="editRemainderError"></span>
                            </div>
                            <div class="form-group col-md-3">
                                <label> Status <span class="text-danger"> * </span></label>
                                <select id="editStatus" name="editStatus" class="form-control input-sm">
                                    <option value="Active">Active</option>
                                    <option value="Inactive">Inactive</option>
                                </select>
                                <span class="text-danger" id="editStatusError"></span>
                            </div>
                            <div class="form-group col-md-3">
                                <label>Notes</label>
                                <input class="form-control input-sm" id="editNotes" type="text" name="editNotes"
                                    placeholder="notes about product">
                                <span class="text-danger" id="editNotesError"></span>
                            </div>
                            <div class="form-group col-md-3" id="TypeDiv">
                                <label> Type <span class="text-danger"> * </span></label>
                                <select id="editType" name="editType" class="form-control input-sm">
                                    <option value="regular"> Regular </option>
                                    <option value="serialize"> Serialize </option>
                                   
                                </select>
                                <span class="text-danger" id="typeError"></span>
                            </div>
                            <div class="form-group col-md-3" id="StockCheckDiv">
                                <label> Stock Check <span class="text-danger"> * </span></label>
                                <select id="editStockCheck" name="editStockCheck" class="form-control input-sm">
                                    <option value="Yes"> Yes</option>
                                    <option value="No"> No </option>
                                </select>
                                <span class="text-danger" id="stockCheckError"></span>
                            </div>
                            <div class="row col-md-6" id="editSP">
                                <div class="form-group col-md-6">
                                    <label>Pics Per Box: <span class="text-danger"></span></label>
                                    <input class="form-control input-sm serialize" id="editPicsPerBox" type="text"
                                        name="editPicsPerBox" disabled>
                                    <span class="text-danger" id="editPicsPerBoxError"></span>
                                </div>
                            </div>
                            <div class="form-group col-md-12">
                                <div class="row">
                                    <div class="form-group col-md-3 " id="ImageDiv">
                                        <label for="">Edit Image</label>
                                        <input type="file" name="editImage" id="editImage"
                                            class="form-control form-control-sm">
                                        <span class="text-danger" id="editImageError"></span>
                                        <div class="row">
                                            <div class="form-group col-12">
                                                <img id="showImage" src="{{ asset('upload/no_image.png') }}"
                                                    style="width: 60%;height: 90px; border:1px solid #000000;margin: 2% 0% 0% 0%;">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-2" id="ShowImageDiv">
                                        <img id="editShowImage" src="{{ url('upload/no_image.png') }}"
                                            style="width: 100%; border:1px solid #ececec;">
                                    </div>
                                    <!-- Start edit spec -->
                                    <div class="col-md-7" id="editSpecSection">
                                        <div class="row">
                                            <div class="col-12"></div>

                                            <div class="col-10">
                                                <label> Product Specification</label>
                                            </div>
                                            <div class=" col-2">
                                                <button type="button" class="btn btn-cyan btn-md add"
                                                    onclick="addSpecRowForEdit();"><span class="glyphicon glyphicon-plus"
                                                        style="font-size: 18px; font-weight:800;"><strong>+</strong></span></button>
                                            </div>

                                            <!--edit spec append here...-->
                                        </div>
                                    </div>
                                    <!-- End spec -->
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary mr-auto" data-dismiss="modal">x
                                Close</button>
                            <button type="submit" class="btn btn-cyan" id="updateProduct"><i
                                    class="fa fa-save"></i>
                                Update Product</button>
                        </div>
                    </form>

                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->

    <!-- edit open stock modal -->
    <div class="modal fade" id="editOpenStockModal">
        <div class="modal-dialog" style="max-width: 50%;">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Update Open Stock Prodcuts</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i
                            class="fas fa-window-close"></i></button>
                </div>
                <div class="modal-body">
                    <form id="editOpenStockProductForm" method="POST" enctype="multipart/form-data" action="#">
                        @csrf
                        <div class="row">

                            <div class="form-group col-md-12">
                                <input type="hidden" name="editOpenStockId" id="editOpenStockId">
                                <label> Product Name <span class="text-danger"> * </span></label>
                                <input class="form-control input-sm" id="editOpenStockName" type="text"
                                    name="editOpenStockName" disabled>
                                <span class="text-danger" id="editOpenStockNameError"></span>
                            </div>
                            <div class="form-group col-md-6">
                                <label> Opening Stock </label>
                                <input class="form-control input-sm" id="editOpenStockInsert" type="number"
                                    name="editOpenStockInsert" onchange="checkTypeForOpenStockEdit();">
                                <span class="text-danger" id="editOpenStockError"></span>
                            </div>
                            <div class="form-group col-md-6">
                                <label> Select Warehouse <span class="text-danger"> * </span></label>
                                <select class="form-control input-sm" id="edit_open_stock_warehouse"
                                    name="edit_open_stock_warehouse">
                                    <option value="" selected>Select Warehouse</option>
                                    @foreach ($warehouses as $warehouse)
                                        <option value="{{ $warehouse->id }}">{{ $warehouse->wareHouseName }}
                                        </option>
                                    @endforeach
                                </select>
                                <span class="text-danger" id="edit_open_stock_warehouseError"></span>
                            </div>
                            <table border="1" class="text-center editSerializeProductTable">
                                <thead>
                                    <tr>
                                        <th>SL.</th>
                                        <th>SL. Number</th>
                                        <th>Quantity</th>
                                    </tr>
                                </thead>
                                <tbody id="editSerializeProductStockData"></tbody>
                            </table>
                            <div class="editSerializeProductTable">
                                <strong>Total Sale Quantity: <span name="totalStockQuantity"
                                        id="totalStockQuantity"></span></strong><span class="ml-5 text-danger">** Openning
                                    Stock
                                    & Total Qty Must Be Same</span>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary mr-auto" data-dismiss="modal">x
                                Close</button>
                            <button type="submit" class="btn btn-cyan " id="saveStock"><i class="fa fa-save"></i>
                                Update Opening Stock</button>
                        </div>
                    </form>
                    <table>
                        <thead>
                            <tr>
                                <th>Warehouse Name</th>
                                <th>Opening Stock</th>
                                <th>Current Stock</th>
                            </tr>
                        </thead>
                        <tbody id="initialStockData"></tbody>
                    </table>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->

    <!-- Start Serialize Product Modal -->

    <div class="modal fade" id="serializeProductModal">
        <div class="modal-dialog" style="max-width: 30%;">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Add Serialize Product</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i
                            class="fas fa-window-close"></i></button>
                </div>
                <div class="modal-body card-body">
                    <div class="row">
                        <div class="form-group col-md-12">
                            <table border="1">
                                <thead>
                                    <tr>
                                        <th>Sr.</th>
                                        <th>Number Of Pics</th>
                                        <th>Serial Number</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody id="serializeProductTable" class="text-center"></tbody>
                            </table>
                            Total Quantity: <span name="totalStockQuantity" id="totalStockQuantity"></span><br><span
                                class="text-danger">** Openning Stock and Total Quantity Must Be Same</span>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary mr-auto" data-dismiss="modal">x Close</button>
                        <button type="button" class="btn btn-success " onclick="addRow();"> <span
                                class="glyphicon glyphicon-plus"
                                style="font-size: 18px; font-weight:800;"><strong>+</strong></span>
                            Add Row </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Serialize Product Modal -->
@endsection

@section('javascript')
    <script>
        //=========== Start Serialize Product ===========//
        var serialNumbers = [];
        var stockQuantities = [];
        var tempOpeningStock = 0;
        var tempitemsInBox = 0;

        function checkType() {
            let itemsInBox = $("#itemsInBox").val();
            let openingStock = $("#opening_stock").val();
            var type = $("#type").val();
            if (type == "serialize") {
                $("#itemsInBox").prop("disabled", false);
                if (openingStock > 0 && itemsInBox > 0) {
                    if (tempOpeningStock != openingStock || tempitemsInBox != itemsInBox) {
                        tempOpeningStock = openingStock;
                        tempitemsInBox = itemsInBox;
                        serialNumbers = [];
                        stockQuantities = [];
                    } else {
                        storeSerialNumbers();
                        storeStockQuantity();
                    }
                    addProductSerial(type, openingStock, itemsInBox);
                }
            } else {
                $("#showBtn").addClass("d-none");
                $("#itemsInBox").val('');
                $("#itemsInBox").prop("disabled", true);
                serialNumbers = [];
                stockQuantities = [];
            }
        }

        function storeSerialNumbers() {
            serialNumbers = $('input[id^=serialNo]').map(function(index, serialNo) {
                return $(serialNo).val();
            }).get();
        }

        function storeStockQuantity() {
            stockQuantities = $('input[name^=stockQuantity]').map(function(index, quantity) {
                return $(quantity).val();
            }).get();
        }

        function addProductSerial(type, openingStock, itemsInBox) {
            var carton = openingStock / itemsInBox;
            if (stockQuantities.length > 0) {
                carton = stockQuantities.length;
            }
            let rows = '';
            var remainingStock = openingStock;
            if (type == "serialize" && carton > 0) {
                for (let i = 0; i < carton; i++) {
                    let serialNo = i + 1;
                    remainingStock = remainingStock - itemsInBox;
                    if (remainingStock < 0) {
                        itemsInBox = parseFloat(itemsInBox) + parseFloat(remainingStock);
                    }
                    if (stockQuantities.length > 0) {
                        itemsInBox = stockQuantities[i];
                        serialNo = serialNumbers[i];
                    }
                    rows += '<tr id="row' + i + '">' +
                        '<td>' + (i + 1) + '</td>' +
                        '<td><input class="form-control input-sm stockQuantity' + i +
                        '" id="stockQuantity" value="' +
                        itemsInBox +
                        '" type="number" name="stockQuantity" placeholder=" Quantity... " required oninput="calculateTotalQuantity()"></td>';
                    if (i == 0) {
                        rows +=
                            '<td><input class="form-control input-sm serialNo0" id="serialNo" type="text" name="serialNo" placeholder=" Serial... " required oninput="generateSerialNo(this.value);" value="' +
                            serialNo + '"><td><a href="#" onclick="removeRow(' +
                            i + ')" style="color:red;"><i class="fa fa-trash"> </i> </a></td></td>';
                    } else {
                        rows +=
                            '<td><input class="form-control input-sm serialNo' + i +
                            '" id="serialNo" type="text" name="serialNo" placeholder=" Serial... " value="' + serialNo +
                            '" required><td><a href="#" onclick="removeRow(' +
                            i + ')" style="color:red;"><i class="fa fa-trash"> </i> </a></td></td>';
                    }
                    rows += '</tr>';
                }
                $("#serializeProductTable").html('');
                $("#serializeProductTable").html(rows);
                $("#totalStockQuantity").text(openingStock);
                $("#showBtn").removeClass("d-none");
                $("#serializeProductModal").modal("show");
            }
        }

        function generateSerialNo(num) {
            let len = $('input[name^=serialNo]').length;
            serialNo = parseInt(num);
            if (num > 0) {
                for (let i = 1; i <= len; i++) {
                    $(".serialNo" + i).val((serialNo + i));
                }
            }
            storeSerialNumbers();
        }

        function addRow() {
            var trId = $('#serializeProductTable tr:last').attr('id');
            trId = parseInt(trId.substring(3)) + 1;
            let serialNum = parseInt($(".serialNo" + (trId - 1)).val()) + 1;
            let rows = '';
            rows += '<tr id="row' + trId + '">' +
                '<td>' + (trId + 1) + '</td>' +
                '<td><input class="form-control input-sm stockQuantity' + trId +
                '" id="stockQuantity" type="number" name="stockQuantity" placeholder=" Quantity... " required oninput="calculateTotalQuantity()"></td>';
            rows += '<td><input class="form-control input-sm serialNo' + trId +
                '" id="serialNo" type="text" name="serialNo" placeholder=" Serial... " required value="' + serialNum +
                '" ><td><a href="#" onclick="removeRow(' +
                trId + ')" style="color:red;"><i class="fa fa-trash"> </i> </a></td></td></tr>';
            $("#serializeProductTable").append(rows);
        }

        function removeRow(rowNumber) {
            $('#row' + rowNumber).remove();
            $("#serializeProductTable").find('tr').each(function(i, el) {
                $(el).find("td").eq(0).text(i + 1);
            });
            Swal.fire({
                position: 'top-end',
                icon: 'success',
                title: 'Confirmed',
                showConfirmButton: false,
                timer: 200,
            });
            calculateTotalQuantity();
        }

        function calculateTotalQuantity() {
            var totalStockQuantity = 0;
            $('[name="stockQuantity"]').each(function() {
                var currentTxtQuantity = $(this).val();
                if (currentTxtQuantity == '') {
                    currentTxtQuantity = 0;
                }
                totalStockQuantity += parseFloat(currentTxtQuantity);
            });
            $("#totalStockQuantity").text(totalStockQuantity);
            $("#opening_stock").val(totalStockQuantity);
            tempOpeningStock = totalStockQuantity;
            storeStockQuantity();
            storeSerialNumbers();
        }
        //=========== End Serialize Product ===========//

        //===========  Spec  ===========//
        // Start add new spec row
        var rowNumber = 0;

        function addNewSpecRow() {
            var newSpecRow = '<div class="row" id="' + rowNumber + '">' +
                '<div class=" col-4 mt-2">' +
                '<input class="form-control input-sm"  id="specName" type="text" name="specName" placeholder=" Name">' +
                '<span class="text-danger" id="specError" ></span>' +
                '</div>' +
                '<div class=" col-4 mt-2">' +
                '<input class="form-control input-sm"  id="specValue" type="text" name="specValue" placeholder=" Value">' +
                '<span class="text-danger" id="specError"></span>' +
                '</div>' +
                '<div class=" col-4 mt-2">' +
                '<button type="button" class="btn btn-danger btn-md add" onclick="deleteSpecRow(' +
                rowNumber +
                ');"><span class="glyphicon glyphicon-plus" style="font-size: 18px; font-weight:800;"><strong>x</strong></span></button>' +
                '</div></div>';
            $("#specSection").append(newSpecRow);
            rowNumber++;

        } // End add new spec row

        // Start edit spec row 
        var editRowNumber = 0;

        function addSpecRowForEdit() {
            var editSpecRow = '<div class="row editSpecRow" id="' + editRowNumber + '">' +
                '<div class=" col-5 mt-2">' +
                '<input class="form-control input-sm"  id="editNewSpecName" type="text" name="editNewSpecName">' +
                '<span class="text-danger" id="specError" ></span>' +
                '</div>' +
                '<div class=" col-5 mt-2">' +
                '<input class="form-control input-sm"  id="editNewSpecValue" type="text" name="editNewSpecValue" >' +
                '<span class="text-danger" id="specError"></span>' +
                '</div>' +
                '<div class=" col-2 mt-2">' +
                '<button type="button" class="btn btn-danger btn-md add" onclick="deleteSpecRow(' +
                editRowNumber +
                ');"><span class="glyphicon glyphicon-plus" style="font-size: 18px; font-weight:800;"><strong>x</strong></span></button>' +
                '</div></div>';

            $("#editSpecSection").append(editSpecRow);
            editRowNumber++;
        } // End edit SpecRow

        // Start delete added spec row
        function deleteSpecRow(rowNum) {
            $('#' + rowNum).remove();
        }
        // End delete added SpecRow

        // Start delete editSpecRow (Edit-Form)
        function deleteSpecConfirm(rowNum) {
            deleteSpec(rowNum);
        }

        function deleteSpec(id) {
            Swal.fire({
                title: "Are you sure ?",
                text: "You will not be able to recover this Spec!",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Yes, delete Product Spec!",
                closeOnConfirm: false
            }).then((result) => {
                if (result.isConfirmed) {
                    var productId = $("#editId").val();
                    var _token = $('meta[name="csrf-token"]').attr('content');
                    var fd = new FormData();
                    fd.append('id', id);
                    fd.append('productId', productId);
                    fd.append('_token', _token);
                    $.ajax({
                        url: "{{ route('deleteSpec') }}",
                        method: "POST",
                        data: fd,
                        contentType: false,
                        processData: false,
                        success: function(result) {
                            $('#' + id).remove();
                            Swal.fire("Done!", result.success, "success");
                        },
                        error: function(response) {
                            //alert(JSON.stringify(response));
                        },
                        beforeSend: function() {
                            $('#loading').show();
                        },
                        complete: function() {
                            $('#loading').hide();
                        }
                    });
                } else {
                    Swal.fire("Cancelled", "Your imaginary Product is safe :)", "error");
                }
            })

        } // End delete editSpecRow

        //=========== End Spec  ===========//

        $(function() {
            $(document).ready(function() {
                $("#category_id").select2({
                    placeholder: "Select Category",
                    dropdownParent: $("#modal"),
                    allowClear: true,
                    width: '100%'
                });
                $("#stock_warehouse").select2({
                    placeholder: "Select Warehouse",
                    dropdownParent: $("#modal"),
                    allowClear: true,
                    width: '100%'
                });
                $("#editStock_warehouse").select2({
                    placeholder: "Select Warehouse",
                    dropdownParent: $("#modal"),
                    allowClear: true,
                    width: '100%'
                });
                $("#unit_id").select2({
                    placeholder: "Select Unit",
                    dropdownParent: $("#modal"),
                    allowClear: true,
                    width: '100%'
                });
                $("#editUnit").select2({
                    placeholder: "Select Unit",
                    dropdownParent: $("#modal"),
                    allowClear: true,
                    width: '100%'
                });
                $("#brand_id").select2({
                    placeholder: "Select Brand",
                    dropdownParent: $("#modal"),
                    allowClear: true,
                    width: '100%'
                });
                $("#editCategory").select2({
                    placeholder: "Select Category",
                    dropdownParent: $("#editModal"),
                    allowClear: true,
                    width: '100%'
                });
                $("#editBrand").select2({
                    placeholder: "Select Brand",
                    dropdownParent: $("#editModal"),
                    allowClear: true,
                    width: '100%'
                });
            });
        });

        function create() {
            reset();
            $("#modal").modal('show');
        }

        function createService() {
            reset();
            $("#servicemodal").modal('show');
        }

        $('#modal').on('shown.bs.modal', function() {
            $('#name').focus();
        })
        $('#editModal').on('shown.bs.modal', function() {
            $('#editName').focus();
        })
        var table;
        $(document).ready(function() {
            table = $('#manageProductTable').DataTable({
                'ajax': "{{ route('products.getProducts') }}",
                processing: true,
            });
        });

        $("#productForm").submit(function(e) {
            e.preventDefault();
            clearMessages();
            var specNames = $('input[id^=specName]').map(function(index, name) {
                return $(name).val();
            }).get();
            var specValues = $('input[id^=specValue]').map(function(index, value) {
                return $(value).val();
            }).get();
            var name = $("#name").val();
            var code = $("#code").val();
            var barcode_no = 0;
            var category_id = $("#category_id").val();
            var brand_id = $("#brand_id").val();
            var unit_id = $("#unit_id").val();
            var notes = $("#notes").val();
            var model_no = $("#model_no").val();
            var opening_stock = $("#opening_stock").val();
            var remainder_quantity = $("#remainder_quantity").val();
            var stock_warehouse = $("#stock_warehouse").val();
            var minimum_price = $("#minimum_price").val();
            var maximum_price = $("#maximum_price").val();
            var discount = $("#discount").val();
            var productImage = $('#image')[0].files[0];
            var _token = $('input[name="_token"]').val();
            var type = $("#type").val();
            var stockCheck = $("#stockCheck").val();
            var itemsInBox = $("#itemsInBox").val();

            var fd = new FormData();
            fd.append('name', name);
            fd.append('code', code);
            fd.append('barcode_no', barcode_no);
            fd.append('category_id', category_id);
            fd.append('unit_id', unit_id);
            fd.append('notes', notes);
            fd.append('model_no', model_no);
            fd.append('brand_id', brand_id);
            fd.append('opening_stock', opening_stock);
            fd.append('remainder_quantity', remainder_quantity);
            fd.append('stock_warehouse', stock_warehouse);
            fd.append('purchase_price', minimum_price);
            fd.append('sale_price', maximum_price);
            fd.append('discount', discount);
            fd.append('type', type);
            fd.append('stockCheck', stockCheck);
            fd.append('image', productImage);
            let chechspecNames = $.trim(specNames[0]);
            let checkspecValues = $.trim(specValues[0]);
            // Serialize Product
            if (type == "serialize") {
                storeSerialNumbers();
                storeStockQuantity();
                let totalStockQuantity = parseFloat($("#totalStockQuantity").text());
                fd.append('itemsInBox', itemsInBox);
                fd.append('serialNumbers', serialNumbers);
                fd.append('stockQuantities', stockQuantities);
                fd.append('totalStockQuantity', totalStockQuantity);
                if (opening_stock != totalStockQuantity) {
                    Swal.fire("Warning: ", "Openning Stock Must Be Equal To Total Quantity! ", "warning");
                    return 0;
                }
            } else {
                fd.append('itemsInBox', 0);
            }
            // End Serialize Product
            if (chechspecNames.length <= 0 && checkspecValues.length <= 0) {
                fd.append('specNames', -1);
                fd.append('specValues', -1);
            } else {
                fd.append('specNames', specNames);
                fd.append('specValues', specValues);
            }
            fd.append('_token', _token);
            $.ajax({
                url: "{{ route('products.store') }}",
                method: "POST",
                data: fd,
                contentType: false,
                processData: false,
                success: function(result) {
                    if (result['success']) {
                        $("#modal").modal('hide');
                        Swal.fire("Product saved!", result.success, "success");
                        reset();
                        $("#productForm").trigger("reset");
                        table.ajax.reload(null, false);
                        for (i = 0; i < rowNumber; i++) {
                            $('#' + i).remove();
                        }
                    } else {
                        Swal.fire("Error!", result['error'], "error");
                    }
                },
                error: function(response) {
                    //alert(JSON.stringify(response));
                    $('#nameError').text(response.responseJSON.errors.name);
                    $("#codeError").text(response.responseJSON.errors.code);
                    $("#barcode_noError").text(response.responseJSON.errors.barcode_no);
                    $("#categoryError").text(response.responseJSON.errors.category_id);
                    $("#brandError").text(response.responseJSON.errors.brand_id);
                    $("#unitError").text(response.responseJSON.errors.unit_id);
                    $("#opening_stockError").text(response.responseJSON.errors.opening_stock);
                    $("#remainder_quantityError").text(response.responseJSON.errors.remainder_quantity);
                    $("#stock_warehouseError").text(response.responseJSON.errors.stock_warehouse);
                    $("#minimum_priceError").text(response.responseJSON.errors.purchase_price);
                    $("#maximum_priceError").text(response.responseJSON.errors.sale_price);
                    $("#notesError").text(response.responseJSON.errors.notes);
                    $("#model_noError").text(response.responseJSON.errors.model_no);
                    $("#discountError").text(response.responseJSON.errors.discount);
                    $('#imageError').text(response.responseJSON.errors.image);
                    $('#typeError').text(response.responseJSON.errors.type);
                    $('#stockCheckError').text(response.responseJSON.errors.stockCheck);
                    if (type == "serialize") {
                        $('#itemsInBoxError').text('This field is required.');
                        Swal.fire("Error: ", "Please Check Required Field ! ", "error");
                    }
                },
                beforeSend: function() {
                    $('#loading').show();
                },
                complete: function() {
                    $('#loading').hide();
                }

            })
        })






        function saveService(){
            //alert('clicked');
            clearMessages();
            var specNames = $('input[id^=servicespecName]').map(function(index, name) {
                return $(name).val();
            }).get();
            var specValues = $('input[id^=servicespecValue]').map(function(index, value) {
                return $(value).val();
            }).get();
            var name = $("#service_name").val();
            var notes = $("#servicenotes").val();
            var minimum_price = $("#serviceminimum_price").val();
            var maximum_price = $("#servicemaximum_price").val();
            var productImage = $('#serviceimage')[0].files[0];
            var _token = $('input[name="_token"]').val();
           
          

            var fd = new FormData();
            fd.append('name', name);
            fd.append('notes', notes);
            fd.append('purchase_price', minimum_price);
            fd.append('sale_price', maximum_price);
            fd.append('image', productImage);
            let chechspecNames = $.trim(specNames[0]);
            let checkspecValues = $.trim(specValues[0]);
            // Serialize Product
         
            // End Serialize Product
            if (chechspecNames.length <= 0 && checkspecValues.length <= 0) {
                fd.append('specNames', -1);
                fd.append('specValues', -1);
            } else {
                fd.append('specNames', specNames);
                fd.append('specValues', specValues);
            }
            fd.append('_token', _token);
            $.ajax({
                url: "{{ route('products.servicestore') }}",
                method: "POST",
                data: fd,
                contentType: false,
                processData: false,
                success: function(result) {
                    //alert(JSON.stringify(result));
                    if (result['success']) {
                        $("#servicemodal").modal('hide');
                        Swal.fire("Service saved!", result.success, "success");
                        reset();
                        $("#productForm").trigger("reset");
                        table.ajax.reload(null, false);
                        for (i = 0; i < rowNumber; i++) {
                            $('#' + i).remove();
                        }
                    } else {
                        Swal.fire("Error!", result['error'], "error");
                    }
                },
                error: function(response) {
                    //alert(JSON.stringify(response));
                    $('#service_nameError').text(response.responseJSON.errors.name);
                   
                    $("#serviceminimum_priceError").text(response.responseJSON.errors.purchase_price);
                    $("#servicemaximum_priceError").text(response.responseJSON.errors.sale_price);
                    $("#servicenotesError").text(response.responseJSON.errors.notes);
                    
                    $('#imageError').text(response.responseJSON.errors.image);
                   
                },
                beforeSend: function() {
                    $('#loading').show();
                },
                complete: function() {
                    $('#loading').hide();
                }

            })
        }



        function clearMessages() {
            $('#nameError').text("");
            $("#codeError").text("");
            $("#barcode_noError").text("");
            $("#categoryError").text("");
            $("#brandError").text("");
            $("#unitError").text("");
            $("#opening_stockError").text("");
            $("#remainder_quantityError").text("");
            $("#purchase_priceError").text("");
            $("#sale_priceError").text("");
            $("#discountError").text("");
            $('#imageError').text("");
        }

        function editClearMessages() {
            $('#editNameError').text("");
            $('#editCodeError').text("");
            $('#editBarcodeError').text("");
            $('#editCategoryError').text("");
            $('#editBrandError').text("");
            $('#editUnitError').text("");
            $('#editOpenStockError').text("");
            $('#editRemainderError').text("");
            $('#editMinimumPriceError').text("");
            $('#editMaximumPriceError').text("");
            $('#editDiscountError').text("");
            $('#editImageError').text("");
            $('#edit_open_stock_warehouseError').text("");

        }

        function reset() {
            $("#name").val("");
            $("#code").val("");
            $("#barcode_no").val("");
            $("#category_id").val("").trigger("change");
            $("#brand_id").val("").trigger("change");
            $("#unit_id").val("").trigger("change");
            $("#stock_warehouse").val("").trigger("change");
            $("#opening_stock").val("");
            $("#remainder_quantity").val("");
            $("#purchase_price").val("");
            $("#sale_price").val("");
            $("#discount").val("");
            $("#image").val("")
            //$('#showImage').attr('src', "");
            $("#serializeProductTable").html('');
        }

        function editReset() {
            $("#editName").val("");
            $("#editCode").val("");
            $("#editBarcode").val("");
            $("#editCategory").val("").trigger("change");
            $("#editBrand").val("").trigger("change");
            $("#editUnit").val("");
            $("#editOpenStock").val("");
            $("#editRemainder").val("");
            $("#editPurchase").val("");
            $("#sale_price").val("");
            $("#discount").val("");
            $("#image").val("")
            $('#showImage').attr('src', "");
        }

        function editProduct(id) {
            editReset();
            editClearMessages();
            $.ajax({
                url: "{{ route('products.edit') }}",
                method: "GET",
                data: {
                    "id": id
                },
                datatype: "json",
                success: function(result) {
                    $('.editSpecRow').remove();
                    $("#editModal").modal('show');
                    if(result[0].category_id == 3){
                        $("#CategoryDiv").hide();
                        $("#BrandDiv").hide();
                        $("#UnitDiv").hide();
                        $("#ModelDiv").hide();
                        $("#OpeningStockDiv").hide();
                        $("#ReminderStockDiv").hide();
                        $("#editSP").hide();
                        $("#TypeDiv").hide();
                        $("#StockCheckDiv").hide();
                        $("#ImageDiv").hide();
                        $("#ShowImageDiv").hide();
                    }else{
                        $("#CategoryDiv").show();
                        $("#BrandDiv").show();
                        $("#UnitDiv").show();
                        $("#ModelDiv").show();
                        $("#OpeningStockDiv").show();
                        $("#ReminderStockDiv").show();
                        $("#editType").val(result[0].type);
                        $("#editSP").show();
                        $("#TypeDiv").show();
                        $("#StockCheckDiv").show();
                        $("#ImageDiv").show();
                        $("#ShowImageDiv").show();
                    }
                    $("#editName").val(result[0].name);
                    $("#editCode").val(result[0].code);
                    $("#editBarcode").val(result[0].barcode_no);
                    $("#editCategory").val(result[0].category_id).trigger("change");
                    $("#editBrand").val(result[0].brand_id).trigger("change");
                    $("#editUnit").val(result[0].unit_id);
                    $("#editModelNo").val(result[0].model_no);
                    $("#editMinimumPrice").val(result[0].purchase_price);
                    $("#editMaximumPrice").val(result[0].sale_price);
                    $("#editNotes").val(result[0].notes);
                    let openingStock = result[0].opening_stock;
                    $("#editOpenStock").val(openingStock);
                    $("#editRemainder").val(result[0].remainder_quantity);
                    $("#editDiscount").val(result[0].discount);
                    $("#editPicsPerBox").val(result[0].items_in_box);
                    const type = result[0].type;
                    $("#editType").val(type).trigger("change");
                    $("#editStockCheck").val(result[0].stock_check).trigger("change");
                    var imageString = '{{ asset('upload/product_images/thumbs') }}' + "/" + result[0].image;
                    $('#editShowImage').attr('src', imageString);
                    $("#editId").val(result[0].id);
                    if (result[0].status != "") {
                        $("#editStatus").val(result[0].status);
                    } else {
                        $("#editStatus").val("Inactive");
                    }
                    // Start Edit Spec
                    for (i = 0; i < result[1].length; i++) {
                        var setSpecRow = '<div class="row editSpecRow" id="' + result[1][i].id + '">' +
                            '<div class=" col-5 mt-2">' +
                            '<input id="specids" type="hidden"  value="' + result[1][i].id + '">' +
                            '<input class="form-control input-sm"  id="editSpecName" type="text" name="editSpecName" value="' +
                            result[1][i].specificationName + '">' +
                            '<span class="text-danger" id="specError" ></span>' +
                            '</div>' +
                            '<div class=" col-5 mt-2">' +
                            '<input class="form-control input-sm"  id="editSpecValue" type="text" name="editSpecValue" value="' +
                            result[1][i].specificationValue + '">' +
                            '<span class="text-danger" id="specError"></span>' +
                            '</div>' +
                            '<div class=" col-2 mt-2">' +
                            '<button type="button" class="btn btn-danger btn-md add" onclick="deleteSpecConfirm(' +
                            result[1][i].id +
                            ');"><span class="glyphicon glyphicon-plus" style="font-size: 18px; font-weight:800;"><strong>x</strong></span></button>' +
                            '</div></div>';
                        $("#editSpecSection").append(setSpecRow);
                        editRowNumber++;
                    }
                    // End Edit Spec
                },
                error: function(response) {
                    //alert(JSON.stringify(response));

                },
                beforeSend: function() {
                    $('#loading').show();
                },
                complete: function() {
                    $('#loading').hide();
                }
            });
        }

        var productType = null;
        var temp_items_InBox = null;

        function editOpenStock(id) {
            editClearMessages();
            $.ajax({
                url: "{{ route('editOpenStock') }}",
                method: "GET",
                data: {
                    "id": id
                },
                datatype: "json",
                success: function(result) {
                    $("#editOpenStockModal").modal('show');
                    $("#editOpenStockId").val(result.product.id);
                    $("#editOpenStockName").val(result.product.name);
                    $("#editOpenStockInsert").val(result.product.opening_stock);
                    $("#initialStockData").html(result.initialStockData);
                    // Edit Serialize Product
                    productType = result.product.type;
                    temp_items_InBox = result.product.items_in_box;
                    if (result.product.type == "serialize") {
                        $("#editSerializeProductTable").removeClass("d-none");
                        $(".editSerializeProductTable").removeClass("d-none");
                        $("#editSerializeProductStockData").html('');
                        $("#editSerializeProductStockData").html(result.serializeProductRows);
                    } else {
                        $("#editSerializeProductTable").addClass("d-none");
                        $(".editSerializeProductTable").addClass("d-none");
                    }
                    // End Edit Serialize Product
                },
                error: function(response) {
                    //alert(JSON.stringify(response));

                },
                beforeSend: function() {
                    $('#loading').show();
                },
                complete: function() {
                    $('#loading').hide();
                }
            });
        }

        // Start Edit Serialize Product For Opening Stock
        function updateCalculateTotalQuantity(saleQty, product_id, warehouse_id, tblSerializeId) {
            var totalStockQuantity = 0;
            $('[name="stockQuantity"]').each(function() {
                var currentTxtQuantity = $(this).val();
                if (currentTxtQuantity == '') {
                    currentTxtQuantity = 0;
                }
                totalStockQuantity += parseFloat(currentTxtQuantity);
            });
            $("#totalStockQuantity").text(totalStockQuantity);
            $("#editOpenStockInsert").val(totalStockQuantity);

            ediStoreSerialNumbers();
            editStoreStockQuantity();
        }

        var editSerialNumbers = [];
        var editStockQuantities = [];
        // Create Serialize Table
        function checkTypeForOpenStockEdit() {
            if (productType == "serialize") {
                let openingStock = $("#editOpenStockInsert").val();
                let itemsInBox = temp_items_InBox;
                let carton = openingStock / itemsInBox;
                let rows = '';
                var remainingStock = openingStock;
                for (let i = 0; i < carton; i++) {
                    let serialNo = i + 1;
                    remainingStock = remainingStock - itemsInBox;
                    if (remainingStock < 0) {
                        itemsInBox = parseFloat(itemsInBox) + parseFloat(remainingStock);
                    }
                    editSerialNumbers[i];
                    editStockQuantities[i];
                    rows += '<tr id="row' + i + '">' +
                        '<td>' + (i + 1) + '</td>' +
                        '<td><input class="form-control input-sm serialNo' + i +
                        '" id="editSerialNo" type="text" name="serialNo" placeholder=" Serial... " value="' + serialNo +
                        '" required></td>';
                    if (i == 0) {
                        rows +=
                            '<td><input class="form-control input-sm stockQuantity' + i +
                            '" id="stockQuantity" value="' +
                            itemsInBox +
                            '" type="number" name="stockQuantity" placeholder=" Quantity... " required oninput="updateCalculateTotalQuantity()"><td><a href="#" onclick="removeRow(' +
                            i + ')" style="color:red;"><i class="fa fa-trash"> </i> </a></td></td>';
                    } else {
                        rows +=
                            '<td><input class="form-control input-sm stockQuantity' + i +
                            '" id="stockQuantity" value="' +
                            itemsInBox +
                            '" type="number" name="stockQuantity" placeholder=" Quantity... " required oninput="updateCalculateTotalQuantity()"><td><a href="#" onclick="removeRow(' +
                            i + ')" style="color:red;"><i class="fa fa-trash"> </i> </a></td></td>';
                    }
                    rows += '</tr>';
                }
                $("#editSerializeProductStockData").html('');
                $("#editSerializeProductStockData").html(rows);
                $("#totalStockQuantity").text(openingStock);
                ediStoreSerialNumbers();
                editStoreStockQuantity();
            }
        }

        function ediStoreSerialNumbers() {
            editSerialNumbers = $('input[id^=editSerialNo]').map(function(index, serialNo) {
                return $(serialNo).val();
            }).get();
        }

        function editStoreStockQuantity() {
            editStockQuantities = $('input[name^=stockQuantity]').map(function(index, quantity) {
                return $(quantity).val();
            }).get();
        }
        // End Edit Serialize Product For Opening Stock

        $("#editProductForm").submit(function(e) {
            e.preventDefault();
            editClearMessages();
            var name = $("#editName").val();
            var code = 0;
            // var barcode_no = $("#editBarcode").val();
            var barcode_no = 0;
            var category_id = $("#editCategory").val();
            var brand_id = $("#editBrand").val();
            var unit_id = $("#editUnit").val();
            var opening_stock = $("#editOpenStock").val();
            var remainder_quantity = $("#editRemainder").val();
            var purchase_price = $("#editMinimumPrice").val();
            var sale_price = $("#editMaximumPrice").val();
            var discount = $("#editDiscount").val();
            var status = $("#editStatus").val();
            var model_no = $("#editModelNo").val();
            var notes = $("#editNotes").val();

            var specNames = [];
            var specValues = [];
            var specIds = [];
            var newSpecNames = [];
            var newSpecValues = [];
            var i = 0;

            $('[id^="specids"]').each(function() {
                specIds[i] = $(this).val();
                i++;
            });
            i = 0;
            $('input[id^="editSpecName"]').each(function() {
                specNames[i] = $(this).val();
                i++;
            });
            i = 0;
            $('input[id^="editSpecValue"]').each(function() {
                specValues[i] = $(this).val();
                i++;
            });
            i = 0;
            $('[id^="editNewSpecName"]').each(function() {
                newSpecNames[i] = $(this).val();
                i++;
            });
            i = 0;
            $('[id^="editNewSpecValue"]').each(function() {
                newSpecValues[i] = $(this).val();
                i++;
            });
            var productImage = $('#editImage')[0].files[0];
            var _token = $('input[name="_token"]').val();
            if( ($("#editType").val()) == null){
                var type= 'service';
            }else{
                var type = $("#editType").val();
            }
            
            if( ($("#editStockCheck").val()) == null){
                var stockCheck= 'No';
            }else{
                var stockCheck = $("#editStockCheck").val();
            }
            
           

            var id = $("#editId").val();
            var fd = new FormData();
            fd.append('name', name);
            fd.append('code', code);
            fd.append('barcode_no', barcode_no);
            fd.append('category_id', category_id);
            fd.append('brand_id', brand_id);
            fd.append('model_no', model_no);
            fd.append('notes', notes);
            fd.append('unit_id', unit_id);
            fd.append('opening_stock', opening_stock);
            fd.append('remainder_quantity', remainder_quantity);
            fd.append('purchase_price', purchase_price);
            fd.append('sale_price', sale_price);
            fd.append('discount', discount);
            fd.append('type', type);
            fd.append('stockCheck', stockCheck);
            fd.append('image', productImage);
            fd.append('status', status);
            fd.append('id', id);

            // End Serialize
            //Specs
            if (specIds.length <= 0) {
                fd.append('specIds', -1);
            } else {
                fd.append('specIds', specIds);
            }
            fd.append('specNames', specNames);
            fd.append('specValues', specValues);
            //New Specs
            if (newSpecNames.length == 0 || newSpecValues.length == 0) {
                fd.append('newSpecNames', -1);
                fd.append('newSpecValues', -1);
            } else {
                fd.append('newSpecNames', newSpecNames);
                fd.append('newSpecValues', newSpecValues);
            }
            // End New Specs

            fd.append('_token', _token);
            $.ajax({
                url: "{{ route('products.update') }}",
                method: "POST",
                data: fd,
                contentType: false,
                processData: false,
                success: function(result) {
                    //alert(JSON.stringify(result));
                    $("#editModal").modal('hide');
                    Swal.fire("Updated Product!", result.success, "success");
                    $('.editSpecRow').remove();
                    table.ajax.reload(null, false);
                },
                error: function(response) {
                    //alert(JSON.stringify(response));
                    $('#editNameError').text(response.responseJSON.errors.name);
                    $('#editCodeError').text(response.responseJSON.errors.code);
                    $('#editBarcodeError').text(response.responseJSON.errors.barcode_no);
                    $('#editCategoryError').text(response.responseJSON.errors.category_id);
                    $('#editBrandError').text(response.responseJSON.errors.brand_id);
                    $('#editUnitError').text(response.responseJSON.errors.unit_id);
                    $('#editOpenStockError').text(response.responseJSON.errors.opening_stock);
                    $('#editRemainderError').text(response.responseJSON.errors.remainder_quantity);
                    $('#editMinimumPriceError').text(response.responseJSON.errors.purchase_price);
                    $('#editMaximumPriceError').text(response.responseJSON.errors.sale_price);
                    $('#editDiscountError').text(response.responseJSON.errors.discount);
                    $('#editStatusError').text(response.responseJSON.errors.status);
                    $('#editImageError').text(response.responseJSON.errors.image);
                },
                beforeSend: function() {
                    $('#loading').show();
                },
                complete: function() {
                    $('#loading').hide();
                }
            })
        });

        $("#editOpenStockProductForm").submit(function(e) {
            e.preventDefault();
            var productId = $("#editOpenStockId").val();
            var openingStock = $("#editOpenStockInsert").val();
            var warehouseId = $("#edit_open_stock_warehouse").val();
            var _token = $('input[name="_token"]').val();

            var id = $("#editId").val();
            var fd = new FormData();
            fd.append('productId', productId);
            fd.append('openingStock', openingStock);
            fd.append('warehouseId', warehouseId);
            // Serialize
            if (productType == "serialize") {
                fd.append('editSerialNumbers', editSerialNumbers);
                fd.append('editStockQuantities', editStockQuantities);
            }
            fd.append('_token', _token);
            $.ajax({
                url: "{{ route('updateProductOpenStock') }}",
                method: "POST",
                data: fd,
                contentType: false,
                processData: false,
                success: function(result) {
                    $("#editOpenStockModal").modal('hide');
                    Swal.fire("Updated Stock!", result.success, "success");
                    table.ajax.reload(null, false);
                    $("#edit_open_stock_warehouse").val("").trigger("change");
                },
                error: function(response) {
                    if (response.responseJSON.errors && jQuery.inArray("warehouseId", response
                            .responseJSON.errors)) {
                        $('#edit_open_stock_warehouseError').text("Please Select Warehouse.");
                    } else {
                        Swal.fire("Updated Stock Error!", "try again", "error");
                    }

                },
                beforeSend: function() {
                    $('#loading').show();
                },
                complete: function() {
                    $('#loading').hide();
                }
            })
        })

        function confirmDelete(id) {
            Swal.fire({
                title: "Are you sure ?",
                text: "You will not be able to recover this imaginary file!",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Yes, delete Product!",
                closeOnConfirm: false
            }).then((result) => {
                if (result.isConfirmed) {
                    var _token = $('meta[name="csrf-token"]').attr('content');
                    $.ajax({
                        url: "{{ route('products.delete') }}",
                        method: "POST",
                        data: {
                            "id": id,
                            "_token": _token
                        },
                        success: function(result) {
                            Swal.fire("Done!", result.success, "success");
                            table.ajax.reload(null, false);
                        },
                        error: function(response) {
                            //alert(JSON.stringify(response));
                        },
                        beforeSend: function() {
                            $('#loading').show();
                        },
                        complete: function() {
                            $('#loading').hide();
                        }
                    });
                } else {
                    Swal.fire("Cancelled", "Your imaginary Product is safe :)", "error");
                }
            })

        }

        $(document).ready(function() {
            $('#image').change(function(e) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    $('#showImage').attr('src', e.target.result);
                }
                reader.readAsDataURL(e.target.files['0']);
            });

            $('#editImage').change(function(e) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    $('#editShowImage').attr('src', e.target.result);
                }
                reader.readAsDataURL(e.target.files['0']);
            });

        });



        Mousetrap.bind('ctrl+shift+n', function(e) {
            e.preventDefault();
            if ($('#modal.in, #modal.show').length) {

            } else {
                create();
            }
        });

        function reloadDt() {
            if ($('#modal.in, #modal.show').length) {

            } else if ($('#editModal.in, #editModal.show').length) {

            } else {
                table.ajax.reload(null, false);
            }
        }
        Mousetrap.bind('ctrl+shift+r', function(e) {
            e.preventDefault();
            reloadDt();
        });
        Mousetrap.bind('ctrl+shift+s', function(e) {
            e.preventDefault();
            if ($('#modal.in, #modal.show').length) {
                $("#productForm").trigger('submit');
            } else {
                //alert("Not Calling");
            }
        });
        Mousetrap.bind('ctrl+shift+u', function(e) {
            e.preventDefault();
            if ($('#editModal.in, #editModal.show').length) {
                $("#editProductForm").trigger('submit');
            } else {
                //alert("Not Calling");
            }
        });
        Mousetrap.bind('esc', function(e) {
            e.preventDefault();
            if ($('#editModal.in, #editModal.show').length) {
                $("#editModal").modal('hide');
            } else if ($('#modal.in, #modal.show').length) {
                $('#modal').modal('hide');
            }
        });
    </script>
@endsection
