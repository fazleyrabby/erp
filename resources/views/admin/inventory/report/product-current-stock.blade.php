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
                    <div class="row g-3">
                        <!-- Left col -->
                        <section class="col-md-12">
                            <!-- Custom tabs (Charts with tabs)-->
                            <div class="card">
                                <div class="card-header">
                                    <h3>Current Stock of Products
                                        <a class="btn btn-success float-right" href="{{ url('sale/') }}"> <i
                                                class="fa fa-plus-circle"></i> view Sale</a>
                                    </h3>
                                </div><!-- /.card-header -->
                                <div class="card-body">
                                    <div class="row g-3">
                                        @csrf
                                        <div class="form-group mb-3 col-md-4">
                                            <label>Category: </label>
                                            <select id="category" name="category" class="form-control input-sm">
                                                <option value="">Select Category</option>
                                                @foreach ($categories as $category)
                                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                                @endforeach
                                            </select>

                                        </div>
                                        <div class="form-group mb-3 col-md-4">
                                            <label>Brand: </label>
                                            <select id="brand" name="brand" class="form-control input-sm">
                                                <option value="">Select Brand</option>
                                            </select>
                                        </div>
                                        {{-- <div class="form-group mb-3 col-md-4">
                                            <label>Product: </label>
                                            <select id="product" name="product" class="form-control input-sm">
                                                <option value="">Select Product</option>
                                            </select>
                                        </div> --}}
                                        <div class="form-group mb-3 col-md-4">
                                            <label> </label>
                                            <button type="button" class="btn btn-success btn-lg btn-block"
                                                onclick="generateCurrentStock()">Generate Current Stock</button>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                        </div>
                                        <div class="col-md-3"></div>
                                        <div class="col-md-3">
                                            <div id="createReport">
                                                {{-- <button type="button" class="btn btn-success btn-lg btn-block float-right"
                                                    onclick="generateReport(-1)"><i class="fas fa-print"> Generate Report
                                                    </i> </button> --}}
                                            </div>
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
            clear();
            loadBrands(categoryId);
        })
        $("#brand").change(function() {
            var categoryId = $("#category").val();
            var brandId = $("#brand").val();
            $('#loading').show();
            setTimeout(function() {
                $('#loading').hide();
            }, 100);
            // loadProducts(categoryId, brandId);
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

        // to generate current-stock of product (CategoryWise, BrandWise)
        const generateCurrentStock = () => {
            var categoryId = $("#category").val();
            var brandId = $("#brand").val();
            var productId = $("#product").val();
            var ids = 0;
            if (categoryId == "") {
                Swal.fire({
                    // title: 'Error!',
                    text: 'please select Category.',
                    icon: 'error',
                    confirmButtonText: 'Ok'
                })
                return 0;
            }

            if (categoryId != "" || brandId != "") {
                ids = [categoryId, brandId];
            } else {
                ids = [categoryId];   
            }

            window.open("{{ url('report/current-stock-report') }}" + "/" + ids);

        }

        function clearTable() {
            Swal.fire({
                title: "Are you sure ?",
                text: "You will not be able to recover this imaginary file!",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Yes, clear !",
                closeOnConfirm: false
            }).then((result) => {
                if (result.isConfirmed) {
                    $('#loading').show();
                    clear();
                    setTimeout(function() {
                        $('#loading').hide();
                    }, 300);

                } else {
                    Swal.fire("Cancelled", "Your imaginary Expense is safe :)", "error");
                }
            });
        }

        function clear() {
            let brandData = "<option value=''>Select Brand</option>";
            let productData = "<option value=''>Select Product</option>";
            $("#brand").html(brandData);
            $("#product").html(productData);
            $("#manageCurrentProductStockTable").html('');
            $("#createReport").html('');
        }
    </script>
@endsection
