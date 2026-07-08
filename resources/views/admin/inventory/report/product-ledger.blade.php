@extends('admin.master')
@section('title')
{{ Session::get('companySettings')[0]['name'] }} Report
@endsection
@section('content')

    <!-- Content Header (Page header) -->
    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <!-- Small boxes (Stat box) -->
            <!-- Main row -->
            <div id="msg_error"></div>
            <form id="productLedger" method="POST" enctype="multipart/form-data">
                <div class="row g-3">
                    <!-- Left col -->
                    <section class="col-md-12">
                        <!-- Custom tabs (Charts with tabs)-->
                        <div class="card">
                           <div class="card-header">
                                <h3> Product Ledger</h3>
                            </div><!-- /.card-header -->
                            <div class="card-body">
                                <div class="row g-3">
                                    @csrf
                                    <div class="form-group mb-3 col-md-4">
                                        <label>Product: </label>
                                        <select id="products" name="products" class="form-control input-sm">
                                            <option value="">Select Product</option>
                                            @foreach ($products as $product)
                                            <option value="{{ $product->id }}">{{ $product->name.' - '.$product->code }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group mb-3 col-md-4">
                                        <label>Date From: </label>
                                        <input type="date" class="form-control" id="dateFrom"
                                               value="{{ todayDate() }}" aria-describedby="emailHelp">

                                    </div>
                                    <div class="form-group mb-3 col-md-4">
                                        <label>Date To: </label>
                                        <input type="date" class="form-control" id="dateTo"
                                               aria-describedby="emailHelp" value="{{ todayDate() }}">
                                    </div>
                                    <div class="form-group mb-3 col-md-3"><button type="button" class="btn btn-primary btn-lg btn-block " id="btnLedgerDetails"
                                                onclick="productDetailsLedger()"> Product Details Ledger</button></div>
                                    <div class="form-group mb-3 col-md-5"></div>
                                    <div class="form-group mb-3 col-md-4">
                                        <button type="button" class="btn btn-primary btn-lg btn-block "
                                                onclick="generateReport()"> Generate Report</button>
                                    </div>
                                    
                                     <div class="form-group mb-3 col-md-12">
                                            <label>Product Details: </label>
                                            <table border="1" style="width:100%;text-align:center;">
                                                <thead>
                                                    <tr>
                                                        <th width="5%">SL#</th>
                                                        <th width="50%">Product</th>
                                                        <th width="15%">Warehouse</th>
                                                        <th width="30%">Current Stock</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="manageproductLedgerTable"></tbody>
                                            </table>
                                            </table>
                                        </div> 
                                </div>
                            </div>
                            <div class="card-footer">
                                <div class="row g-3">
                                    <div class="col-md-12">
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
        

        @endsection
        @section('javascript')
        <script>
        $(function() {
            $("select").select2();
        });
        $("#btnLedgerDetails").hide();
        // var table;
        $("#category").change(function() {
            var categoryId = $("#category").val();
            loadBrands(categoryId);
            //loadProducts(categoryId,'');
        })
        $("#brand").change(function() {
            var categoryId = $("#category").val();
            var brandId = $("#brand").val();
            loadProducts(categoryId, brandId);
        })

        $("#products").change(function() {
            $('#loading').show();

            setTimeout(function() {
                $('#loading').hide();

            }, 100);
        });

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
                    $("#barcodeError").text("No such product available in your system 1 " + JSON.stringify(
                        response));
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
                    $("#products").html(productData);
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




        const generateReport = () => {
            let productId = $("#products").val();
            let dateFrom = $("#dateFrom").val();
            let dateTo = $("#dateTo").val();
            if (productId == "" || dateFrom == "" || dateTo == "") {
                Swal.fire({
                    // title: 'Error!',
                    text: 'Select Product Please',
                    icon: 'error',
                    confirmButtonText: 'Ok'
                })
                return 0;
            }
            $.ajax({
                url: "{{ url('report/warehouseWiseStock/') }}"+"/"+productId,
                method: "GET",
                success: function(result) {
                    $("#manageproductLedgerTable").html(result);
                    $("#btnLedgerDetails").show();
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
        function productDetailsLedger(){
            let productId = $("#products").val();
            let dateFrom = $("#dateFrom").val();
            let dateTo = $("#dateTo").val();
            if (productId == "" || dateFrom == "" || dateTo == "") {
                Swal.fire({
                    // title: 'Error!',
                    text: 'Select Product, DateFrom and DateTo.',
                    icon: 'error',
                    confirmButtonText: 'Ok'
                })
                return 0;
            }else{
                window.open("{{ url('report/product-report') }}" + "/" + productId + "/" + dateFrom + "/" + dateTo);
            }
        }







        function clearLedgerTable() {
            Swal.fire({
                title: "Are you sure ?",
                text: "You will not be able to recover this imaginary file!",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Yes, clear cart!",
                closeOnConfirm: false
            }).then((result) => {
                if (result.isConfirmed) {
                    $("#barcode").val("");
                    var _token = $('input[name="_token"]').val();
                    var fd = new FormData();
                    fd.append('_token', _token);
                    $.ajax({
                        url: "{{ url('sale/clearCart') }}",
                        method: "POST",
                        data: fd,
                        contentType: false,
                        processData: false,
                        datatype: "json",
                        success: function(result) {
                            $("#barcode").focus();
                            if (result == "Success") {
                                fetchCart();
                                clearSalesForm();
                                window.localStorage.removeItem('isSave');
                            } else {
                                alert(JSON.stringify(response));
                            }
                        },
                        beforeSend: function() {
                            $('#loading').show();
                        },
                        complete: function() {
                            $('#loading').hide();
                        },
                        error: function(response) {
                            alert(JSON.stringify(response));
                        }
                    })
                } else {
                    Swal.fire("Cancelled", "Your imaginary Expense is safe :)", "error");
                }
            })
        }








        function clearSalesForm() {
            $("#supplier").val("");
            $("#total_amount").text("0");
            $("#discount").val("0");
            $("#transport").val("0");
            $("#grandTotal").text("0");
            $("#currentDue").text("0");
            $("#totalWithDue").text("0");
            $("#payment").val("0");
            //emi clear
            $('#downPayment').val(0);
            $("#noOfTenure").val(0);
            $('#perTenurAmount').text('');
            $('#startDate').val('');
            $(".tenurDate").remove();
        }
    </script>
        @endsection
