@extends('admin.master')
@section('title')
Admin Our Team -View
@endsection
@section('content')
    <div class="container-fluid">
        
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Employee List</h3>
                    <div class="card-actions">
                        <a class="btn btn-primary" href="{{route('teamAdd')}}">
                            <i class="fa fa-plus-circle me-1"></i> Add New Employee
                        </a>
                    </div>
                </div> 
                <div class="card-body">
                    <h3 class="text-center text-success">{{Session::get('message')}}</h3>
                    <div class="table-responsive">
                        <table id="tbl_category" width="100%" class="table table-vcenter table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th width="5%">ID</th>
                                    <th width="10%">Image</th>
                                    <th>Name</th>
                                    <th>Designation</th>
                                    <th>Grade</th>
                                    <th width="10%">Status</th>
                                    <th width="10%">Action</th>
                                </tr>
                            </thead>
                            @php
                            $serial = 0;
                            @endphp
                            <tbody>
                            @foreach($members as $emp)
                                <tr>
                                    <td>{{  ++$serial}}</td>
                                    <td><img src = "{{ asset('/upload/employee_image/'.trim($emp->member_image)) }}" style="width: 50px; height: 50px; object-fit: cover;" class="rounded" /></td>
                                    <td>{{ $emp->member_name }}</td>
                                    <td>{{ $emp->member_desingnation }}</td>
                                    <td>{{ $emp->grade_name }}</td>
                                    <td> {{ $emp->status }} </td>
                                    <td>
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-primary dropdown-toggle btn-sm" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <i class="fas fa-cog"></i>
                                            </button>
                                            <div class="dropdown-menu dropdown-menu-end">
                                                <a class="dropdown-item" href="{{route('memberEdit', $emp->id)}}"><i class="fas fa-edit me-2"></i> Edit</a>
                                                <a class="dropdown-item" href="{{route('changeMemberStatus',$emp->id)}}" onclick="return swalConfirmLink(event, this)" data-item="employee" data-action="status"><i class="fas fa-exchange-alt me-2"></i> Change Status</a>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endsection



  