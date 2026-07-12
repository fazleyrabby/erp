@extends('admin.master')
@section('title', 'Employee Dashboard')
@section('content')

<div class="row row-deck row-cards mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h3 class="card-title text-white">Welcome, {{ auth()->user()->name }}</h3>
            </div>
            <div class="card-body">
                <p>Welcome to your Employee Self-Service (ESS) Portal. Here you can manage your attendance, request leaves, and view your payslips.</p>
            </div>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="col-sm-6 col-lg-3">
        <div class="card bg-success-lt">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="subheader">Available Leave</div>
                </div>
                <div class="h1 mb-3">12 Days</div>
                <div class="d-flex mb-2">
                    <div>Casual: 7 | Sick: 5</div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-lg-3">
        <div class="card bg-info-lt">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="subheader">Attendance This Month</div>
                </div>
                <div class="h1 mb-3">22 Days</div>
                <div class="d-flex mb-2">
                    <div>100% Present</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Action Shortcuts -->
    <div class="col-12 mt-4">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Quick Actions</h3>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-4">
                        <a href="#" class="btn btn-outline-primary w-100 py-3">
                            <i class="fa fa-calendar fa-2x mb-2 d-block"></i>
                            Apply for Leave
                        </a>
                    </div>
                    <div class="col-md-4">
                        <a href="#" class="btn btn-outline-success w-100 py-3">
                            <i class="fa fa-clock-o fa-2x mb-2 d-block"></i>
                            My Attendance Log
                        </a>
                    </div>
                    <div class="col-md-4">
                        <a href="#" class="btn btn-outline-info w-100 py-3">
                            <i class="fa fa-file-text-o fa-2x mb-2 d-block"></i>
                            Download Payslip
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
