@extends('admin.master')
@section('title')
Account Settings
@endsection
@section('Shop Management')

@endsection
@section('content')


<!-- Content Wrapper. Contains page content -->
<div class="container-fluid">
  
    <div class="card">
      <div class="card-header">
        <h3> Account Setting
          <!-- <a class="btn btn-success float-right" href=""> <i class="fa fa-list"></i>View Shops </a> -->
        </h3>
        <h3 class="text-center text-success">{{ Session::get('message') }}</h3>
      </div><!-- /.card-header -->
      <div class="card-body">
        <form action="{{route('accountSettingStore')}}" method="post" enctype="multipart/form-data">
          @csrf
          <input type="hidden" name="editId" id="editId" value="1">


          <div class="row g-3">
            <table class="m-2">
              <thead>
                @php
                $sl=1;
                @endphp
                <tr>
                  <th width="10%" class="text-center">Sl</th>
                  <th width="40%">Chart of accounts</th>
                  <th width="25%">Limit from</th>
                  <th width="25%">Limit to</th>
                </tr>
              </thead>
              <tbody>
                @foreach($coas as $coa)
                <tr>
                  <td class="text-center">{{$sl++}}</td>
                  <td>
                    {{$coa->name}}<input type="hidden" class="form-control" name="account_id[]" value="{{$coa->id}}">
                    <span class="text-danger" id="account_idError">{{ $errors->has('account_id') ? $errors->first('account_id') : '' }}</span>
                  </td>
                  <td>
                    <input type="text" class="form-control" id="limit_from" name="limit_from[]" value="{{$coa->limit_from}}">
                    <span class="text-danger" id="limit_fromError">{{ $errors->has('limit_from') ? $errors->first('limit_from') : '' }}</span>
                  </td>
                  <td>
                    <input type="text" class="form-control" name="limit_to[]" value="{{$coa->limit_to}}">
                    <span class="text-danger" id="limit_toError">{{ $errors->has('limit_to') ? $errors->first('limit_to') : '' }}</span>
                  </td>
                </tr>
                @endforeach
              </tbody>
            </table>
          </div>
          <div class="form-row">
            <div class="form-group mb-3 col-md-12" style="padding-top: 30px">
              <button type="submit" class="btn btn-primary float-right">Update</button>
            </div>
          </div>
        </form>
      </div>
      <!-- /.card -->
    </div>
    <!-- /.card -->
  @endsection

@section('javascript')

<script>

</script>

@endsection