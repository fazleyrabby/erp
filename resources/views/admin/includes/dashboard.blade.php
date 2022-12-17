@extends('admin.master')
@section('title')
    {{ Session::get('companySettings')[0]['name'] }} Dashboard
@endsection
@section('content')
    <!-- Container fluid  -->
    <!-- ============================================================== -->
    <div class="container-fluid">
        <!-- ============================================================== -->
        <!-- Sales Cards  -->
        <!-- ============================================================== -->
        <div class="row">
            <div class="col-md-5">
                <div class="row">
                    <h3 class="col-md-12 ">Party Info</h3>
                    <!-- Column -->
                    <div class="col-md-6">
                        <div class="card card-hover">
                            <div class="box bg-cyan text-center">
                                <h1 class="font-light text-white"><i class="fa fa-handshake"></i></h1>
                                <h6 class="text-white">{{$supplier}}</h6>
                                <h6 class="text-white">Total Supplier </h6>
                            </div>
                        </div>
                    </div>
                    <!-- Column -->
                    <div class="col-md-6 ">
                        <div class="card card-hover">
                            <div class="box bg-success text-center">
                                <h1 class="font-light text-white"><i class="fa fa-briefcase"></i></h1>
                                <h6 class="text-white">{{$customer + $walkin}}</h6>
                                <h6 class="text-white">Total Customer </h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-7">
                <div class="row">
                    <h3 class="col-md-12 ">Product Info</h3>
                    <!-- Column -->
                    <div class="col-md-4">
                        <div class="card card-hover">
                            <div class="box bg-cyan text-center">
                                <h1 class="font-light text-white"><i class="fa fa-handshake"></i></h1>
                                <h6 class="text-white">{{$product}}</h6>
                                <h6 class="text-white">Total Products </h6>
                            </div>
                        </div>
                    </div>
                    <!-- Column -->
                    <div class="col-md-4">
                        <div class="card card-hover">
                            <div class="box bg-warning text-center">
                                <h1 class="font-light text-white"><i class="fa fa-handshake"></i></h1>
                                <h6 class="text-white">{{$service}}</h6>
                                <h6 class="text-white">Total Services </h6>
                            </div>
                        </div>
                    </div>
                    <!-- Column -->
                    <div class="col-md-4">
                        <div class="card card-hover">
                            <div class="box bg-danger text-center">
                                <h1 class="font-light text-white"><i class="fa fa-handshake"></i></h1>
                                <h6 class="text-white">{{$damages}}</h6>
                                <h6 class="text-white">Total Damages </h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
       
        
        <div class="row">
            <h3 class="col-md-12 ">Service Booking Info</h3>
             <!-- Column -->
             <div class="col-md-6 col-lg-2 col-xlg-4">
                <div class="card card-hover">
                    <div class="box bg-cyan text-center">
                        <h1 class="font-light text-white"><i class="fa fa-handshake"></i></h1>
                        <h6 class="text-white">{{$pending}}</h6>
                        <h6 class="text-white">Pending </h6>
                    </div>
                </div>
            </div>
             <!-- Column -->
             <div class="col-md-6 col-lg-2 col-xlg-4">
                <div class="card card-hover">
                    <div class="box bg-warning text-center">
                        <h1 class="font-light text-white"><i class="fa fa-handshake"></i></h1>
                        <h6 class="text-white">{{$servicing}}</h6>
                        <h6 class="text-white">In Servicing </h6>
                    </div>
                </div>
            </div>
             <!-- Column -->
             <div class="col-md-6 col-lg-2 col-xlg-4">
                <div class="card card-hover">
                    <div class="box bg-success text-center">
                        <h1 class="font-light text-white"><i class="fa fa-handshake"></i></h1>
                        <h6 class="text-white">{{$ready}}</h6>
                        <h6 class="text-white">Ready for Delivery</h6>
                    </div>
                </div>
            </div>
             <!-- Column -->
             <div class="col-md-6 col-lg-2 col-xlg-4">
                <div class="card card-hover">
                    <div class="box bg-primary text-center">
                        <h1 class="font-light text-white"><i class="fa fa-handshake"></i></h1>
                        <h6 class="text-white">{{$delivered}}</h6>
                        <h6 class="text-white">Delivered </h6>
                    </div>
                </div>
            </div>
             <!-- Column -->
             <div class="col-md-6 col-lg-2 col-xlg-4">
                <div class="card card-hover">
                    <div class="box bg-success text-center">
                        <h1 class="font-light text-white"><i class="fa fa-handshake"></i></h1>
                        <h6 class="text-white">{{$completed}}</h6>
                        <h6 class="text-white">Completed</h6>
                    </div>
                </div>
            </div>
             <!-- Column -->
             <div class="col-md-6 col-lg-2 col-xlg-4">
                <div class="card card-hover">
                    <div class="box bg-danger text-center">
                        <h1 class="font-light text-white"><i class="fa fa-handshake"></i></h1>
                        <h6 class="text-white">{{$cancelled}}</h6>
                        <h6 class="text-white">Cancelled</h6>
                    </div>
                </div>
            </div>
        </div>
        <!-- ============================================================== -->
        <!-- Sales chart -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        <!-- Sales chart -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->

        <!-- ============================================================== -->
    </div>
    <!-- ============================================================== -->
    <!-- End Container fluid  -->
@endsection
