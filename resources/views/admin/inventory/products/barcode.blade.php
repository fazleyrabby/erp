@extends('admin.master')
@section('title')
    {{ Session::get('companySettings')[0]['name'] }} Report
@endsection
@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <!-- Small boxes (Stat box) -->
                <!-- Main row -->
                <div id="msg_error"></div>
                <form id="saleProducts" method="POST" enctype="multipart/form-data">
                    <div class="row">
                        <!-- Left col -->
                        <section class="col-md-12">
                            <!-- Custom tabs (Charts with tabs)-->
                            <div class="card">
                                <div class="card-header">
                                    <h3> Barcode Generator for Product
                                        <a class="btn btn-success float-right" href="{{ route('sale.add') }}"> <i
                                                class="fa fa-plus-circle"></i> Add Sale</a>
                                    </h3>
                                </div><!-- /.card-header -->
                                <div class="card-body">
                                    <div class="row">
                                        @csrf
                                        <div class="form-group col-md-4">
                                            <label>Category: </label>
                                            <select id="category" name="category" class="form-control input-sm">
                                                <option value="">Select Category</option>
                                                @foreach ($categories as $category)
                                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label>Brand: </label>
                                            <select id="brand" name="brand" class="form-control input-sm">
                                                <option value="">Select Brand</option>
                                            </select>
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label>Product: </label>
                                            <select id="product" name="product" class="form-control input-sm">
                                                <option value="">Select Product</option>
                                            </select>
                                        </div>
                                        <div class="form-group col-md-8"></div>
                                        <div class="form-group col-md-4">
                                            <label> </label>
                                            <button type="button" class="btn btn-info btn-lg btn-block p-3"
                                                onclick="viewProduct()"> View Product Barcode </button>
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label> Product Name </label>
                                            <input type="text" id="productName" class="form-control input-sm" value=""
                                                readonly>
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label> Purchase Price </label>
                                            <input type="text" id="purchasePrice" class="form-control input-sm" value=""
                                                readonly>
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label> Sale Price </label>
                                            <input type="text" id="salePrice" class="form-control input-sm" value="">
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label> Product Qty </label>
                                            <input type="text" id="quantity" class="form-control input-sm" value="">
                                        </div>

                                        {{-- <div class="form-group col-md-12">
                                            <label>Product Details: </label>
                                            <table border="1" style="width:100%;text-align:center;">
                                                <thead>
                                                    <tr>
                                                        <th>SL#</th>
                                                        <th>Entry Date</th>
                                                        <th>Type</th>
                                                        <th>Stock In</th>
                                                        <th>Stock Out</th>
                                                        <th>Balance</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="manageProductTable222"></tbody>
                                            </table>
                                            </table>
                                        </div> --}}
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <h3>Barcode</h3>
                                            <div id="barcode"></div>
                                            <div id="createReport"></div>
                                        </div>
                                    </div>
                                </div>
                                <!-- /.card -->

                                <!-- /.card -->
                            </div>
                        </section>
                        <!-- /.Left col -->
                        <!-- right col (We are only adding the ID to make the widgets sortable)-->

                        <!-- /.row (main row) -->

                    </div><!-- /.container-fluid -->
        </section>
        </form>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->

@endsection
@section('javascript')

    <script>
        $(function() {
            $("select").select2();
        });

        // var table;
        $("#category").change(function() {
            var categoryId = $("#category").val();
            loadBrands(categoryId);
            loadProducts(-1, -1);
            clear();
        })
        $("#brand").change(function() {
            var categoryId = $("#category").val();
            var brandId = $("#brand").val();
            loadProducts(categoryId, brandId);
            clear();
        })

        function loadBrands(categoryId) {
            var _token = $('input[name="_token"]').val();
            var fd = new FormData();
            fd.append('id', categoryId);
            fd.append('_token', _token);
            $.ajax({
                url: "{{ url('brands/categoryWiseView') }}",
                method: "POST",
                data: fd,
                contentType: false,
                processData: false,
                datatype: "json",
                success: function(result) {
                    var brandData = "<option value=''>Select Brand</option>";
                    for (var i = 0; i < result.length; i++) {
                        brandData += "<option value='" + result[i]["id"] + "'>" + result[i]["name"] +
                            "</option>";
                    }
                    if (brandData == "<option value=''>Select Brand</option>") {
                        brandData = "<option value=''>No Available Brand</option>";
                    }
                    $("#brand").html(brandData);
                },
                beforeSend: function() {
                    $('#loading').show();
                },
                complete: function() {
                    $('#loading').hide();
                },
                error: function(response) {
                    //alert(JSON.stringify(response));
                }
            })
        }

        function loadProducts(categoryId, brandId) {
            var _token = $('input[name="_token"]').val();
            var fd = new FormData();
            fd.append('categoryId', categoryId);
            fd.append('brandId', brandId);
            fd.append('_token', _token);
            $.ajax({
                url: "{{ url('products/brandAndCategoryWise') }}",
                method: "POST",
                data: fd,
                contentType: false,
                processData: false,
                datatype: "json",
                success: function(result) {
                    var productData = "<option value=''>Select Product</option>";
                    for (var i = 0; i < result.length; i++) {
                        productData += "<option value='" + result[i]["id"] + "'>" + result[i]["name"] +
                            " ( available-" + result[i]["current_stock"] + " )</option>";
                    }
                    if (productData == "<option value=''>Select Product</option>") {
                        productData = "<option value=''>No Available Product</option>";
                    }
                    $("#product").html(productData);
                },
                beforeSend: function() {
                    $('#loading').show();
                },
                complete: function() {
                    $('#loading').hide();
                },
                error: function(response) {
                    $("#barcodeError").text("No such product available in your system 1 " + JSON.stringify(
                        response));
                    //alert(JSON.stringify(response));
                }
            })
        }

        $("#product").change(function() {
            //viewCurrentStock(); // function call

            $('#loading').show();
            setTimeout(function() {
                $('#loading').hide();
            }, 100);
            clear();
        })

        // to get current stock of individual product
        var categoryId = 0;
        var brandId = 0;
        var id = 0;
        const viewProduct = () => {

            categoryId = $("#category").val();
            brandId = $("#brand").val();
            id = $("#product").val();
            var _token = $('input[name="_token"]').val();
            if (categoryId == "" || brandId == "" || id == "") {
                Swal.fire({
                    // title: 'Error!',
                    text: 'Select category>brand>product.!',
                    icon: 'error',
                    confirmButtonText: 'Ok'
                })
                return 0;
            }
            var fd = new FormData();
            fd.append('id', id);
            fd.append('categoryId', categoryId);
            fd.append('brandId', brandId);
            fd.append('_token', _token);
            $.ajax({
                url: "{{ url('barcode/get-product') }}",
                method: "POST",
                data: fd,
                contentType: false,
                processData: false,
                datatype: "json",
                success: function(result) {
                    //alert(result[0]["id"]);
                    productId = result[0]["id"];
                    $("#productName").val(result[0]["name"]);
                    $("#purchasePrice").val(result[0]["purchase_price"]);
                    $("#salePrice").val(result[0]["sale_price"]);
                    $("#barcode").html(result[0]["barcode"]);
                    let gererateBtn =
                        '<button type="button"  class="btn btn-lg btn-info my_button float-right " onclick="generateBarcode()"><i class="fa fa-barcode"> Generate Barcode </i> </button>';
                    $("#createReport").html(gererateBtn);
                },
                beforeSend: function() {
                    $('#loading').show();
                },
                complete: function() {
                    $('#loading').hide();
                },
                error: function(response) {
                    //alert(JSON.stringify(response));
                    $("#msg_error").html(JSON.stringify(response));
                    $("#barcodeError").text("No such product available in your system");
                    //alert(JSON.stringify(response));
                }
            });
        }

        // to print Barcode a product
        const generateBarcode = () => {
            let categoryId = $("#category").val();
            let brandId = $("#brand").val();
            let productId = $("#product").val();
            let salePrice = $("#salePrice").val();
            let quantity = $("#quantity").val();
            const data = [categoryId, brandId, productId, salePrice, quantity];
            clear();
            window.open("{{ url('barcode/generate-barcode') }}" + "/" + data);
        }

        function clear() {
            $("#productName").val('');
            $("#purchasePrice").val('');
            $("#salePrice").val('');
            $("#quantity").val('');
            $("#barcode").html('');
            $("#createReport").html('');
        }
    </script>
@endsection
