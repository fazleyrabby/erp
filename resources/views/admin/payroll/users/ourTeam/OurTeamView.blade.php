@extends('admin.master')
@section('title')
Admin Our Team -View
@endsection
@section('content')

    <section class="content-header" style="padding: 0px 1.0rem;">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h3>Create Team Member</h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{url('/home')}}">Home</a></li>
                        
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
        
    </section>
    <section class="content">
        <div class="container-fluid">
            <div class="row g-3">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <a href="{{url('/payroll/ourTeam/add')}}" class="btn btn-success btn-icon-split">
                                <span class="icon text-white-50">
                                    <i class="fas fa-plus"></i>
                                </span>
                                <span class="text">Add Team Member</span>
                            </a>
                            <h3 class="text-center text-success">{{Session::get('message')}}</h3>
                        </div>
                      



                
                        <!-- /.card-header -->
                        <div class="card-body">
                            <table id="tbl_category" class="table table-bordered table-striped dt_view">
                            
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
                                        <td><img src = "{{ asset('/frontEnd/images/team/'.trim($emp->member_image)) }}" width="150" height="150" /></td>
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
                              
                              <a class="dropdown-item" href="{{route('memberEdit', $emp->member_id)}}"><i class="fas fa-exchange-alt me-2"></i> Edit </a>
                                 
                          <a class="dropdown-item" href="{{route('changeMemberStatus',$emp->member_id)}}" onclick="return swalConfirmLink(event, this)" data-item="banner" data-action="status"><i class="fas fa-exchange-alt me-2"></i> Change Status </a>

                                                    </div>
                                                </div>
                                            </td>
                                            @endforeach
                                        </tr>
                                    </tbody>
                                   
                                </table>
                            </div>
                        </div>

               




                    </div>
                </div>
            </div>
        @endsection



  