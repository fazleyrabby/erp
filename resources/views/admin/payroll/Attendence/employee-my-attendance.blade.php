@extends('admin.master')
@section('title', 'My Attendance')

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
            <div class="col-sm-6 col-lg-3">
                <div class="card bg-info-lt">
                    <div class="card-body text-center">
                        <div class="subheader text-muted">Present This Month</div>
                        <div class="h2 mb-1">{{ $presentDays }} / {{ $totalWorkingDays }}</div>
                        <small class="text-muted">{{ $totalWorkingDays > 0 ? round(($presentDays / $totalWorkingDays) * 100) : 0 }}%</small>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-lg-3">
                <div class="card {{ $todayAttendence && $todayAttendence->time_in ? ($todayAttendence->time_out ? 'bg-success-lt' : 'bg-warning-lt') : 'bg-secondary-lt' }}">
                    <div class="card-body text-center">
                        <div class="subheader text-muted">Today ({{ now()->format('d M Y') }})</div>
                        <div class="h2 mb-1">
                            @if($todayAttendence && $todayAttendence->time_in)
                                {{ $todayAttendence->time_in }}
                                @if($todayAttendence->time_out)
                                    - {{ $todayAttendence->time_out }}
                                @else
                                    <span class="text-warning" id="liveTimerLabel">(Clocked In)</span>
                                    <div id="liveTimer" class="fs-3 fw-bold text-success" data-time-in="{{ $todayAttendence->time_in }}">00:00:00</div>
                                @endif
                            @else
                                <span class="text-muted">Not Clocked In</span>
                            @endif
                        </div>
                        @if($todayAttendence && $todayAttendence->working_hour)
                            <small class="text-muted">{{ $todayAttendence->working_hour }} hrs</small>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-3 mb-4">
            <div class="col-md-6">
                @if(!$todayAttendence || !$todayAttendence->time_in)
                    <a href="{{ route('employee.clock-in') }}" class="btn btn-success btn-lg w-100 py-3"
                       onclick="event.preventDefault(); document.getElementById('clock-in-form').submit();">
                        <i class="fa fa-clock-o fa-2x mb-2 d-block"></i>
                        Clock In
                    </a>
                    <form id="clock-in-form" action="{{ route('employee.clock-in') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                @elseif($todayAttendence && $todayAttendence->time_in && !$todayAttendence->time_out)
                    <a href="{{ route('employee.clock-out') }}" class="btn btn-danger btn-lg w-100 py-3"
                       onclick="event.preventDefault(); document.getElementById('clock-out-form').submit();">
                        <i class="fa fa-clock-o fa-2x mb-2 d-block"></i>
                        Clock Out
                    </a>
                    <form id="clock-out-form" action="{{ route('employee.clock-out') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                @else
                    <div class="alert alert-success text-center mb-0 py-4">
                        <i class="fa fa-check-circle fa-2x mb-2 d-block"></i>
                        Today's shift completed
                    </div>
                @endif
            </div>
            <div class="col-md-6">
                <form method="GET" class="row g-2">
                    <div class="col-md-5">
                        <select name="year" class="form-control">
                            @for($y = date('Y') - 2; $y <= date('Y'); $y++)
                                <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                            @endfor
                        </select>
                    </div>
                    <div class="col-md-5">
                        <select name="month" class="form-control">
                            @for($m = 1; $m <= 12; $m++)
                                <option value="{{ sprintf('%02d', $m) }}" {{ $month == sprintf('%02d', $m) ? 'selected' : '' }}>
                                    {{ date('F', mktime(0, 0, 0, $m, 1)) }}
                                </option>
                            @endfor
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary w-100">View</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Attendance Log - {{ date('F', mktime(0, 0, 0, $month, 1)) }} {{ $year }}</h3>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-vcenter table-hover">
                        <thead>
                            <tr>
                                <th>SL</th>
                                <th>Date</th>
                                <th>Time In</th>
                                <th>Time Out</th>
                                <th>Working Hours</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($attendences as $i => $att)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $att->date }}</td>
                                <td>{{ $att->time_in }}
                                    @if($att->time_in_status)
                                        <span class="badge {{ $att->time_in_status == 'Late' ? 'bg-danger' : 'bg-success' }}">{{ $att->time_in_status }}</span>
                                    @endif
                                </td>
                                <td>{{ $att->time_out ?? '--' }}
                                    @if($att->time_out_status)
                                        <span class="badge {{ $att->time_out_status == 'Early' ? 'bg-warning' : 'bg-info' }}">{{ $att->time_out_status }}</span>
                                    @endif
                                </td>
                                <td>{{ $att->working_hour ?? '--' }}</td>
                                <td>
                                    @if($att->shift_status == 'Completed')
                                        <span class="badge bg-success">Completed</span>
                                    @elseif($att->shift_status == 'Incomlete')
                                        <span class="badge bg-danger">Incomplete</span>
                                    @elseif($att->time_in && !$att->time_out)
                                        <span class="badge bg-warning">Active</span>
                                    @elseif($att->time_in)
                                        <span class="badge bg-info">Present</span>
                                    @else
                                        <span class="badge bg-secondary">--</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center">No attendance records for this month.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('javascript')
<script>
$(function() {
    var $timer = $('#liveTimer');
    if ($timer.length) {
        var timeIn = $timer.data('time-in');
        if (timeIn) {
            var parts = timeIn.split(':');
            var clockIn = new Date();
            clockIn.setHours(parseInt(parts[0]), parseInt(parts[1]), parseInt(parts[2] || 0), 0);
            function tick() {
                var now = new Date();
                var diff = Math.floor((now - clockIn) / 1000);
                var h = String(Math.floor(diff / 3600)).padStart(2, '0');
                var m = String(Math.floor((diff % 3600) / 60)).padStart(2, '0');
                var s = String(diff % 60).padStart(2, '0');
                $('#liveTimer').text(h + ':' + m + ':' + s);
            }
            tick();
            setInterval(tick, 1000);
        }
    });
</script>
@endsection
