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

<div class="row g-3">
    <div class="col-12">
        <div class="card card-sm">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="col-auto">
                        <span class="bg-primary text-white avatar">
                            <i class="fa fa-user-circle fa-lg"></i>
                        </span>
                    </div>
                    <div class="col ps-3">
                        <div class="h2 mb-0 fw-bold">{{ auth()->user()->name }}</div>
                        <div class="text-secondary">Employee Dashboard</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12">
        <h3 class="mb-3">Leave Balances</h3>
        <div class="row g-3">
            @forelse($balances as $bal)
            <div class="col-md-6 col-lg-3">
                <div class="card card-sm">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-auto">
                                <span class="bg-{{ $bal->remaining_days > 0 ? 'success' : 'danger' }} text-white avatar">
                                    <i class="fa fa-calendar-check-o fa-lg"></i>
                                </span>
                            </div>
                            <div class="col">
                                <div class="h2 mb-0 fw-bold">{{ $bal->remaining_days }}</div>
                                <div class="text-secondary">{{ $bal->leave_type }} ({{ $bal->used_days }} used of {{ $bal->total_days }})</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-sm-6 col-lg-3">
                <div class="card card-sm">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-auto">
                                <span class="bg-secondary text-white avatar">
                                    <i class="fa fa-calendar fa-lg"></i>
                                </span>
                            </div>
                            <div class="col">
                                <div class="h2 mb-0 fw-bold">--</div>
                                <div class="text-secondary">Available Leave</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforelse

            <div class="col-sm-6 col-lg-3">
                <div class="card card-sm">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-auto">
                                <span class="bg-info text-white avatar">
                                    <i class="fa fa-clock-o fa-lg"></i>
                                </span>
                            </div>
                            <div class="col">
                                <div class="h2 mb-0 fw-bold">{{ $presentDays }} / {{ $totalWorkingDays }}</div>
                                <div class="text-secondary">Attendance ({{ date('F') }}) — {{ $attendancePct }}% Present</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-sm-6 col-lg-3">
                <div class="card card-sm">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-auto">
                                <span class="bg-warning text-white avatar">
                                    <i class="fa fa-file-text fa-lg"></i>
                                </span>
                            </div>
                            <div class="col">
                                <div class="h2 mb-0 fw-bold">{{ $totalLeaves }}</div>
                                <div class="text-secondary">My Applications ({{ $pendingLeaves }} pending)</div>
                            </div>
                        </div>
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
                            <a href="{{ route('employee.apply-leave') }}" class="btn btn-primary w-100 py-3">
                                <i class="fa fa-calendar me-2"></i> Apply for Leave
                            </a>
                        </div>
                        <div class="col-md-4">
                            <a href="{{ route('employee.my-attendance') }}" class="btn btn-success w-100 py-3">
                                <i class="fa fa-clock-o me-2"></i> My Attendance
                            </a>
                        </div>
                        <div class="col-md-4">
                            <a href="{{ route('employee.my-leaves') }}" class="btn btn-info w-100 py-3">
                                <i class="fa fa-list me-2"></i> My Leaves
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
