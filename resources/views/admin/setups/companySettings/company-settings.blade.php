@extends('admin.master')
@section('title')
    {{ Session::get('companySettings')[0]['name'] }} Settings
@endsection
@section('Shop Management')
@endsection
@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="container-fluid">
        
            <div class="card">
                <div class="card-header">
                    <h3> Company Details
                        <!-- <a class="btn btn-success float-right" href=""> <i class="fa fa-list"></i>View Shops </a> -->
                    </h3>
                </div><!-- /.card-header -->
                <div class="card-body">
                    <form id="editShopForm" method="post" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="editId" id="editId" value="1">
                        <div class="form-row">

                            <div class="form-group mb-3 col-md-4">
                                <label for="name">Shop Name<font style="color:red">*</font> </label>
                                <input type="text" name="name" id="companyName" class="form-control form-group mb-3-sm"
                                    placeholder="Shop Name">
                                <span class="text-danger" id="companyNameError"></span>
                            </div>
                            <div class="form-group mb-3 col-md-4">
                                <label for="email">Email<font style="color:red">*</font> </label>
                                <input type="text" name="email" id="companyEmail" class="form-control form-group mb-3-sm"
                                    placeholder="Shop email">
                            </div>
                            <div class="form-group mb-3 col-md-4">
                                <label for="phone">Phone No<font style="color:red">*</font> </label>
                                <input type="text" name="phone" id="companyPhone" class="form-control form-group mb-3-sm"
                                    placeholder="Shop Phone">
                            </div>
                            <div class="form-group mb-3 col-md-6">
                                <label for="address">Address<font style="color:red">*</font> </label>
                                <input type="text" name="address" id="companyAddress" class="form-control form-group mb-3-sm"
                                    placeholder="Shop Name">
                            </div>
                            <div class="form-group mb-3 col-md-6">
                                <label for="website">Website<font style="color:red">*</font> </label>
                                <input type="text" name="website" id="companyWebsite" class="form-control form-group mb-3-sm"
                                    placeholder="Shop Website">
                            </div>
                            <div class="form-group mb-3 col-md-4">
                                <label for="name">Month Year Format<font style="color:red">*</font> </label>
                                <input type="text" name="month_year" id="month_year" class="form-control form-group mb-3-sm">
                            </div>
                            <div class="form-group mb-3 col-md-4">
                                <label for="name">Currency<font style="color:red">*</font> </label>
                                <input type="text" name="currency" id="currency" class="form-control form-group mb-3-sm"
                                    placeholder="Currency">
                            </div>
                            <div class="form-group mb-3 col-md-4">
                                <label for="manage_stock_to_sale">Manage_stock_to Sale 545</label>
                                <select class="form-control" name="manage_stock_to_sale" id="companyStockManage">
                                    <option value="">Select Option</option>
                                    <option value="Yes">Yes</option>
                                    <option value="No">No </option>
                                </select>
                                <span class="text-danger" id="companyStockManageError"></span>
                            </div>

                            <div class="form-group mb-3 col-md-4" style="display:none;">
                                <label for="barcode_exits">Barcode_Exists</label>
                                <select class="form-control" name="barcode_exists" id="companyBarcode">
                                    <option value="">Select Options </option>
                                    <option value="Yes">Yes</option>
                                    <option value="No">No</option>
                                </select>
                            </div>

                            <div class="form-group mb-3 col-md-12">
                                <label for=""> Default Supplier : </label>
                                <select class="form-control input-sm" id="default_party" name="default_party">
                                    <option value="">Select Supplier</option>
                                    <option value="-999">$supplier->name</option>
                                </select>
                            </div>
                            <div class="form-group mb-3 col-md-12">
                                <label for="">Report Header</label>
                                <textarea class="ckeditor form-control" id="company_report_header" name="company_report_header"></textarea>
                            </div>
                            <div class="form-group mb-3 col-md-12">
                                <label for="">Report footer</label>
                                <textarea class="ckeditor form-control" id="company_report_footer" name="company_report_footer"></textarea>
                            </div>

                            <div class="form-group mb-3 col-md-12">
                                <label for="">Terms and Conditions</label>
                                <textarea class="ckeditor form-control" id="company_terms_conditions" name="company_terms_conditions"></textarea>
                            </div>

                            <div class="form-group mb-3 col-md-4">
                                <label for="watermark">Watermark <font style="color:red">*</font> </label>
                                <input type="file" name="watermark" id="companyWatermark"
                                    class="form-control form-group mb-3-sm" />
                                <img id="watermark" src="{{ asset('upload/no_image.png') }}"
                                    style="width: 100px;height: 110px; border:1px solid #000000;margin-top: 1%;">
                            </div>
                            <div class="form-group mb-3 col-md-4">
                                <label for="logo">Shop Logo <font style="color:red">*</font> </label>
                                <input type="file" name="logo" id="companyLogo"
                                    class="form-control form-group mb-3-sm" />
                                    <img id="logo" src="{{ asset('upload/no_image.png') }}"
                                    style="width: 200px;height: 110px; border:1px solid #000000;margin-top: 1%;">
                                
                            </div>


                            <div class="form-group mb-3 col-md-4">
                                <label for="logo">Shop Logo(vertical) <font style="color:red">*</font> </label>
                                <input type="file" name="vertical_logo" id="companyLogo_vertical"
                                    class="form-control form-group mb-3-sm" />
                                <img id="vertical_logo" src="{{ asset('upload/no_image.png') }}"
                                    style="width: 200px;height: 110px; border:1px solid #000000;margin-top: 1%;">
                            </div>

                            <div class="form-group mb-3 col-md-4" style="padding-top: 30px">
                                <button type="submit" class="btn btn-primary">Update</button>
                            </div>

                        </div>

                    </form>

                </div>
                <!-- /.card -->
            </div>
            <!-- /.card -->
        @endsection

@section('javascript')
    <script type="text/javascript">
        $(document).ready(function() {
            $('.ckeditor').ckeditor();
        });

        $(function() {
            $("select").select2();
        });

        editShop(1);

        function editShop(id) {
            $.ajax({
                url: "{{ route('company.settings.edit') }}",
                method: "GET",
                data: {
                    "id": id
                },
                datatype: "json",
                success: function(result) {
                    var watermarkImageString = '{{ asset('upload/images') }}' + "/" + result.watermark;
                    var logoImageString = '{{ asset('upload/images') }}' + "/" + result.logo;
                    var vertical_logoImageString = '{{ asset('upload/images') }}' + "/" + result.vertical_logo;
                    $("#companyLogo").val("");
                    $("#logo").attr("src", logoImageString);
                    $("#companyLogo_vertical").val("");
                    $("#vertical_logo").attr("src", vertical_logoImageString);
                    $("#currency").val(result.currency);
                    $("#companyName").val(result.name);
                    $("#companyEmail").val(result.email);
                    $("#companyPhone").val(result.phone);
                    $("#companyAddress").val(result.address);
                    $("#companyWebsite").val(result.website);
                    $("#month_year").val(result.month_year);
                    $("#companyStockManage").val(result.manage_stock_to_sale).trigger("change");
                    $("#default_party").val(result.default_party).trigger('change');
                    CKEDITOR.instances.company_report_header.setData(result.report_header);
                    CKEDITOR.instances.company_report_footer.setData(result.report_footer);
                    CKEDITOR.instances.company_terms_conditions.setData(result.terms_conditions);
                    $("#companyWatermark").val("");
                    $("#watermark").attr("src", watermarkImageString);
                    
                    $("#companyBarcode").val(result.barcode_exists);
                    //$("#logo_vertical").attr("src", logoImageVerticalString);
                    $('#companyName').focus();
                },
                error: function(response) {
                    alert(response);
                },
            });
        }

        $("#editShopForm").submit(function(e) {
            e.preventDefault();
            var companyName = $("#companyName").val();
            var companyEmail = $("#companyEmail").val();
            var companyPhone = $("#companyPhone").val();
            var companyAddress = $("#companyAddress").val();
            var companyWebsite = $("#companyWebsite").val();
            var month_year = $("#month_year").val();
            var default_party = $("#default_party").val();
            var company_report_header = CKEDITOR.instances.company_report_header.getData();
            var company_report_footer = CKEDITOR.instances.company_report_footer.getData();
            var company_terms_conditions = CKEDITOR.instances.company_terms_conditions.getData();
            var companyStockManage = $("#companyStockManage").val();
            var companyBarcode = $("#companyBarcode").val();
            var currency = $("#currency").val();
            var companyWatermark = $("#companyWatermark")[0].files[0];
            var companyLogo = $("#companyLogo")[0].files[0];
            var companyLogo_vertical = $("#companyLogo_vertical")[0].files[0];
            var _token = $('input[name="_token"]').val();
            var id = $("#editId").val();
            var fd = new FormData();
            fd.append('id', id);
            fd.append('companyName', companyName);
            fd.append('companyEmail', companyEmail);
            fd.append('companyPhone', companyPhone);
            fd.append('companyAddress', companyAddress);
            fd.append('companyWebsite', companyWebsite);
            fd.append('month_year', month_year);
            fd.append('company_report_header', company_report_header);
            fd.append('company_report_footer', company_report_footer);
            fd.append('company_terms_conditions', company_terms_conditions);
            fd.append('companyStockManage', companyStockManage);
            fd.append('companyBarcode', companyBarcode);
            fd.append('companyWatermark', companyWatermark);
            fd.append('companyLogo', companyLogo);
            fd.append('companyLogo_vertical', companyLogo_vertical);
            fd.append('currency', currency);
            fd.append('default_party', default_party);
            fd.append('_token', _token);
            $.ajax({
                url: "{{ route('company.settings.update') }}",
                method: "POST",
                data: fd,
                contentType: false,
                processData: false,
                success: function(result) {
                    Swal.fire("Updated!", result.success, "success");
                    editShop(id);
                },
                beforeSend: function() {
                    $('#loading').show();
                    clearForm();
                },
                complete: function() {
                    $('#loading').hide();
                },
                error: function(response) {
                    $('#companyNameError').text(response.responseJSON.errors.companyName);
                    $('#companyStockManageError').text(response.responseJSON.errors.companyStockManage);
                    Swal.fire({
                        title: 'Error!',
                        text: 'Please, Check Required Field!',
                        icon: 'error',
                        confirmButtonText: 'Ok'
                    });
                },
            })
        });

        function clearForm() {
            $('#companyNameError').text('');
            $('#companyStockManageError').text('');
        }

        $(document).ready(function() {
            $('#companyWatermark').change(function(e) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    $('#watermark').attr('src', e.target.result);
                }
                reader.readAsDataURL(e.target.files['0']);
            });
            $('#companyLogo').change(function(e) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    $('#logo').attr('src', e.target.result);
                }
                reader.readAsDataURL(e.target.files['0']);
            });

            $('#companyLogo_vertical').change(function(e) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    $('#vertical_logo').attr('src', e.target.result);
                }
                reader.readAsDataURL(e.target.files['0']);
            });

        });

        Mousetrap.bind('ctrl+shift+r', function(e) {
            e.preventDefault();
            editShop(1);
        });
        Mousetrap.bind('ctrl+shift+s', function(e) {
            e.preventDefault();
            $("#editShopForm").trigger('submit');
        });
        Mousetrap.bind('ctrl+shift+u', function(e) {
            e.preventDefault();
            $("#editShopForm").trigger('submit');
        });
    </script>
@endsection
