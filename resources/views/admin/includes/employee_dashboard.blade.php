@extends('admin.master')
@section('title', 'Employee Dashboard')
@section('content')

@php
    $employee = auth()->user()->employee;
    $year = date('Y');
    $month = date('m');
    $balances = $employee ? \App\Models\payroll\LeaveBalance::where('employee_id', $employee->id)->where('year', $year)->get() : collect();
    $totalLeaves = $employee ? \App\Models\payroll\Leave::where('employee_id', $employee->id)->whereYear('created_at', $year)->where('deleted', 'No')->count() : 0;
    $pendingLeaves = $employee ? \App\Models\payroll\Leave::where('employee_id', $employee->id)->where('leave_status', 'Pending')->where('deleted', 'No')->count() : 0;
    $presentDays = $employee ? \App\Models\payroll\PayrollAttendence::forEmployee($employee->id)->forMonth($year, $month)->where('time_in', '!=', null)->count() : 0;
    $totalWorkingDays = now()->startOfMonth()->diffInDays(now()->endOfMonth()) + 1;
    $attendancePct = $totalWorkingDays > 0 ? round(($presentDays / $totalWorkingDays) * 100) : 0;
@endphp

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

    @forelse($balances as $bal)
    <div class="col-sm-6 col-lg-3">
        <div class="card {{ $bal->remaining_days > 0 ? 'bg-success-lt' : 'bg-danger-lt' }}">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="subheader">{{ $bal->leave_type }}</div>
                </div>
                <div class="h1 mb-3">{{ $bal->remaining_days }} Days</div>
                <div class="d-flex mb-2">
                    <div>{{ $bal->used_days }} used of {{ $bal->total_days }}</div>
                </div>
            </div>
        </div>
    </div>
    @empty
    <div class="col-sm-6 col-lg-3">
        <div class="card bg-secondary-lt">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="subheader">Available Leave</div>
                </div>
                <div class="h1 mb-3">-- Days</div>
                <div class="d-flex mb-2">
                    <div>No balance configured</div>
                </div>
            </div>
        </div>
    </div>
    @endforelse

    <div class="col-sm-6 col-lg-3">
        <div class="card bg-info-lt">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="subheader">Attendance ({{ date('F') }})</div>
                </div>
                <div class="h1 mb-3">{{ $presentDays }} / {{ $totalWorkingDays }}</div>
                <div class="d-flex mb-2">
                    <div>{{ $attendancePct }}% Present</div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-lg-3">
        <div class="card bg-warning-lt">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="subheader">My Applications</div>
                </div>
                <div class="h1 mb-3">{{ $totalLeaves }}</div>
                <div class="d-flex mb-2">
                    <div>{{ $pendingLeaves }} pending</div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 mt-4">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Quick Actions</h3>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-4">
                        <a href="{{ route('employee.apply-leave') }}" class="btn btn-outline-primary w-100 py-3">
                            <i class="fa fa-calendar fa-2x mb-2 d-block"></i>
                            Apply for Leave
                        </a>
                    </div>
                    <div class="col-md-4">
                        <a href="{{ route('employee.my-attendance') }}" class="btn btn-outline-success w-100 py-3">
                            <i class="fa fa-clock-o fa-2x mb-2 d-block"></i>
                            My Attendance
                        </a>
                    </div>
                    <div class="col-md-4">
                        <a href="{{ route('employee.my-leaves') }}" class="btn btn-outline-info w-100 py-3">
                            <i class="fa fa-list fa-2x mb-2 d-block"></i>
                            My Leaves
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
