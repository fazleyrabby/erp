@extends('admin.master')
@section('title', 'Apply for Leave')

@section('content')
<div class="row">
    <div class="col-md-8 col-lg-6 mx-auto">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Apply for Leave</h3>
                <div class="card-options">
                    <a href="{{ route('employee.my-leaves') }}" class="btn btn-outline-secondary btn-sm"><i class="fa fa-arrow-left me-1"></i> Back to My Leaves</a>
                </div>
            </div>
            <div class="card-body">
                @if(session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif

                @if($balances->isEmpty())
                    <div class="alert alert-warning">No leave balances are configured yet. Please contact your administrator.</div>
                @else
                <form action="{{ route('employee.leave-store') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label">Leave Type <span class="text-danger">*</span></label>
                        <select name="leave_type" class="form-select" required>
                            <option value="">Select Leave Type</option>
                            @foreach($balances as $bal)
                                <option value="{{ $bal->leave_type }}" data-remaining="{{ $bal->remaining_days }}">
                                    {{ $bal->leave_type }} ({{ $bal->remaining_days }} days remaining)
                                </option>
                            @endforeach
                            <option value="Casual Leave">Casual Leave</option>
                            <option value="Medical Leave">Medical Leave</option>
                            <option value="Earn Leave">Earn Leave</option>
                            <option value="Duty Leave">Duty Leave</option>
                        </select>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Start Date <span class="text-danger">*</span></label>
                            <input type="date" name="leave_start_date" class="form-control" value="{{ old('leave_start_date') }}" required min="{{ date('Y-m-d') }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">End Date <span class="text-danger">*</span></label>
                            <input type="date" name="leave_end_date" class="form-control" value="{{ old('leave_end_date') }}" required min="{{ date('Y-m-d') }}">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Reason <span class="text-danger">*</span></label>
                        <textarea name="leave_reason" class="form-control" rows="4" placeholder="Please describe your reason for leave..." required>{{ old('leave_reason') }}</textarea>
                    </div>

                    <div class="text-end">
                        <button type="submit" class="btn btn-primary"><i class="fa fa-paper-plane me-1"></i> Submit Application</button>
                    </div>
                </form>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
