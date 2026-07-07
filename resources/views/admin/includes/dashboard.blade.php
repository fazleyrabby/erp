@extends('admin.master')
@section('title')
    {{ Session::get('companySettings')[0]['name'] }} Dashboard
@endsection
@section('content')
    <div class="row g-3">
        <div class="col-12">
            <h3 class="mb-3">Party Info</h3>
            <div class="row g-3">
                <div class="col-md-6 col-lg-3">
                    <div class="card card-sm">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-auto">
                                    <span class="bg-cyan text-white avatar">
                                        <i class="fa fa-handshake fa-lg"></i>
                                    </span>
                                </div>
                                <div class="col">
                                    <div class="h2 mb-0 fw-bold">{{$supplier}}</div>
                                    <div class="text-secondary">Total Supplier</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="card card-sm">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-auto">
                                    <span class="bg-success text-white avatar">
                                        <i class="fa fa-briefcase fa-lg"></i>
                                    </span>
                                </div>
                                <div class="col">
                                    <div class="h2 mb-0 fw-bold">{{$customer + $walkin}}</div>
                                    <div class="text-secondary">Total Customer</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12">
            <h3 class="mb-3">Product Info</h3>
            <div class="row g-3">
                <div class="col-md-4">
                    <div class="card card-sm">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-auto">
                                    <span class="bg-cyan text-white avatar">
                                        <i class="fa fa-cube fa-lg"></i>
                                    </span>
                                </div>
                                <div class="col">
                                    <div class="h2 mb-0 fw-bold">{{$product}}</div>
                                    <div class="text-secondary">Total Products</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card card-sm">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-auto">
                                    <span class="bg-warning text-white avatar">
                                        <i class="fa fa-cogs fa-lg"></i>
                                    </span>
                                </div>
                                <div class="col">
                                    <div class="h2 mb-0 fw-bold">{{$service}}</div>
                                    <div class="text-secondary">Total Services</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card card-sm">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-auto">
                                    <span class="bg-danger text-white avatar">
                                        <i class="fa fa-exclamation-triangle fa-lg"></i>
                                    </span>
                                </div>
                                <div class="col">
                                    <div class="h2 mb-0 fw-bold">{{$damages}}</div>
                                    <div class="text-secondary">Total Damages</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12">
            <h3 class="mb-3">Service Booking Info</h3>
            <div class="row g-3">
                <div class="col-md-6 col-lg-2">
                    <div class="card card-sm">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-auto">
                                    <span class="bg-cyan text-white avatar">
                                        <i class="fa fa-clock fa-lg"></i>
                                    </span>
                                </div>
                                <div class="col">
                                    <div class="h2 mb-0 fw-bold">{{$pending}}</div>
                                    <div class="text-secondary">Pending</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-2">
                    <div class="card card-sm">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-auto">
                                    <span class="bg-warning text-white avatar">
                                        <i class="fa fa-wrench fa-lg"></i>
                                    </span>
                                </div>
                                <div class="col">
                                    <div class="h2 mb-0 fw-bold">{{$servicing}}</div>
                                    <div class="text-secondary">In Servicing</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-2">
                    <div class="card card-sm">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-auto">
                                    <span class="bg-success text-white avatar">
                                        <i class="fa fa-check-circle fa-lg"></i>
                                    </span>
                                </div>
                                <div class="col">
                                    <div class="h2 mb-0 fw-bold">{{$ready}}</div>
                                    <div class="text-secondary">Ready for Delivery</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-2">
                    <div class="card card-sm">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-auto">
                                    <span class="bg-primary text-white avatar">
                                        <i class="fa fa-truck fa-lg"></i>
                                    </span>
                                </div>
                                <div class="col">
                                    <div class="h2 mb-0 fw-bold">{{$delivered}}</div>
                                    <div class="text-secondary">Delivered</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-2">
                    <div class="card card-sm">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-auto">
                                    <span class="bg-green text-white avatar">
                                        <i class="fa fa-flag-checkered fa-lg"></i>
                                    </span>
                                </div>
                                <div class="col">
                                    <div class="h2 mb-0 fw-bold">{{$completed}}</div>
                                    <div class="text-secondary">Completed</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-2">
                    <div class="card card-sm">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-auto">
                                    <span class="bg-danger text-white avatar">
                                        <i class="fa fa-ban fa-lg"></i>
                                    </span>
                                </div>
                                <div class="col">
                                    <div class="h2 mb-0 fw-bold">{{$cancelled}}</div>
                                    <div class="text-secondary">Cancelled</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
