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
                                    <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
                                        <i class="fas fa-cog"></i>  <span class="caret"></span></button>
                                        <ul class="dropdown-menu dropdown-menu-right" style="border: 1px solid gray;" role="menu">
                                            <li class="action"><a href="{{route('memberEdit', $emp->id)}}" class="btn"><i class="fas fa-edit"></i> Edit </a></li>
                                            <li class="action"><a href="{{route('changeMemberStatus',$emp->id)}}" class="btn" onclick="return confirm('Are you sure you want to change status of this employee?');"><i class="fas fa-exchange-alt"></i> Change Status </a></li>
                                        </ul>
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



  