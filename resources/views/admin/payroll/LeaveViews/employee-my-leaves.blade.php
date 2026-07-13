@extends('admin.master')
@section('title', 'My Leaves')

@section('content')
<div class="row">
    <div class="col-md-12">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <div class="row g-3 mb-4">
            @forelse($balances as $bal)
            <div class="col-sm-6 col-lg-3">
                <div class="card">
                    <div class="card-body text-center">
                        <div class="subheader text-muted">{{ $bal->leave_type }}</div>
                        <div class="h2 mb-1">{{ $bal->remaining_days }} / {{ $bal->total_days }}</div>
                        <small class="text-muted">{{ $bal->used_days }} used</small>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12">
                <div class="alert alert-info">No leave balances configured yet. Please contact admin.</div>
            </div>
            @endforelse
        </div>

        <div class="card">
            <div class="card-header">
                <h3 class="card-title">My Leave Applications</h3>
                <div class="card-options">
                    <a href="{{ route('employee.apply-leave') }}" class="btn btn-primary btn-sm"><i class="fa fa-plus me-1"></i> Apply for Leave</a>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-vcenter">
                        <thead>
                            <tr>
                                <th>SL</th>
                                <th>Leave Type</th>
                                <th>Start Date</th>
                                <th>End Date</th>
                                <th>Days</th>
                                <th>Reason</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($leaves as $i => $leave)
                            <tr>
                                <td>{{ $leaves->firstItem() + $i }}</td>
                                <td>{{ $leave->leave_type }}</td>
                                <td>{{ $leave->leave_start_date }}</td>
                                <td>{{ $leave->leave_end_date }}</td>
                                <td>{{ $leave->days_count }}</td>
                                <td>{{ $leave->leave_reason }}</td>
                                <td>
                                    <span class="badge {{ $leave->status_badge }}">{{ $leave->leave_status }}</span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center">No leave applications found.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                {{ $leaves->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
