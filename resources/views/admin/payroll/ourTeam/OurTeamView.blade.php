@extends('admin.master')
@section('title')
Admin Our Team -View
@endsection
@section('content')
    <div class="container-fluid">
        <section class="content box-border">
            <div class="card">
                <div class="card-header">
                    <h3>Employee List <a class="btn btn-primary float-right" href="{{route('teamAdd')}}"> <i class="fa fa-plus-circle"></i> Add New Employee</a></h3>
                </div> 
                <h3 class="text-center text-success">{{Session::get('message')}}</h3>
            </div>
                <div class="col-md-12">
                <div class="table-responsive">
                <table id="tbl_category" width="100%" class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Image</th>
                            <th>Name</th>
                            <th>Desingnation</th>
                            <th>Grade</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                            @php
                            $serial = 0;
                            @endphp
                    <tbody>
                    @foreach($members as $emp)
                        

                        <tr>
                            <td>{{  ++$serial}}</td>
                            <td><img src = "{{ asset('/upload/employee_image/'.trim($emp->member_image)) }}" width="150" height="150" /></td>
                            <td>{{ $emp->member_name }}</td>
                            <td>{{ $emp->member_desingnation }}</td>
                            <td>
                            {{ $emp->grade_name }}
                            </td>
                            <td> {{ $emp->status }} </td>
                                <td style="width: 12%;">
                                  <div class="btn-group">
                                     <button type="button" class="btn btn-primary dropdown-toggle btn-sm" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                         <i class="fas fa-cog"></i></button>
                                         <div class="dropdown-menu dropdown-menu-end">
                                             <a class="dropdown-item" href="{{route('memberEdit', $emp->id)}}"><i class="fas fa-edit me-2"></i> Edit</a>
                                             <a class="dropdown-item" href="{{route('changeMemberStatus',$emp->id)}}" onclick="return swalConfirmLink(event, this)" data-item="employee" data-action="status"><i class="fas fa-exchange-alt me-2"></i> Change Status</a>
                                         </div>
                                     </div>
                                </td>
                                @endforeach
                            </tr>
                        </tbody>
                </table>
                </div>
                </div>
        </section>
    </div>
    @endsection



  