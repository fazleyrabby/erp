@extends('admin.master')
@section('title')
    Admin Bill List
@endsection


@section('content')
    <div class="container-fluid">
        <section class="content box-border">
            <div class="card">
                <div class="card-header">
                    <h3>Bill List
                            <button type="button" class="btn  btn-primary" onclick="paybill()"><i class="fab fa-amazon-pay"></i>
                                Pay bills</button>
                            <button type="button" class="btn  btn-primary float-right" onclick="create()"><i class="fa fa-plus-circle"></i>
                                Add bills</button>
                    </h3>
                    <h3 class="text-center text-success">{{ Session::get('message') }}</h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover dataTable no-footer" id="manageBillTable" width="100%">
                            <thead>
                                <tr class="bg-light">
                                    <td width="5%" class="text-center">Sl</td>
                                    <td width="20%" class="text-center">Supplier</td>
                                    <td width="15%" class="text-center">Transaction Date</td>
                                    <td width="25%" class="text-center">Expense Reason</td>
                                    <td width="10%" class="text-center">Amount</td>
                                    <td width="10%" class="text-center">Payment Status</td>
                                    <td width="10%" class="text-center">Status</td>
                                    <td width="5%" class="text-center">Action</td>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div><!-- Card Content end -->

                

                 <!-- edit modal -->
                <div class="card-body btn-page">
                    <div class="modal fade" id="editModal" tabindex="-1" role="dialog"
                        aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">Edit COA</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                                        <i class="fas fa-window-close"></i></button>
                                </div>
                                <div class="modal-body">
                                    <input type="hidden" id="editId">
                                    <div class="form-group">

                                        <label class="col-form-label">Name</label>
                                        <input type="text" class="form-control" id="editName" name="name"
                                            placeholder="Name">
                                        <span class="text-danger" id="editNameError">{{ $errors->has('name') ? $errors->first('name') : '' }}</span>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-form-label">Slug</label>
                                        <input type="text" class="form-control" id="editSlug" name="slug"
                                            placeholder="Slug">
                                        <span class="text-danger" id="editSlugError">{{ $errors->has('slug') ? $errors->first('slug') : '' }}</span>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-form-label">Code</label>
                                        <input type="text" class="form-control" id="editCode" name="code"
                                            placeholder="Code">
                                        <span
                                            class="text-danger" id="codeError">{{ $errors->has('code') ? $errors->first('code') : '' }}</span>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-form-label">Parent</label>
                                        <select type="text" class="form-control" id="editParent_id" name="parent_id">
                                            <option value="0">Select Parent</option>
                                           
                                        </select>
                                        <span
                                            class="text-danger" id="editParent_idError">{{ $errors->has('parent_id') ? $errors->first('parent_id') : '' }}</span>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-form-label">Status</label>
                                        <select type="text" class="form-control" id="editStatus" name="Status">
                                            <option value=""selected disabled>Select Parent</option>
                                            <option value="Active">Active</option>
                                            <option value="Inactive">Inactive</option>
                                        </select>
                                        <span
                                            class="text-danger" id="editStatusError">{{ $errors->has('Status') ? $errors->first('parent_id') : '' }}</span>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn  btn-secondary mr-auto" data-dismiss="modal">x
                                        Close</button>
                                    <button  class="btn  btn-primary" onclick="updateCoa()"><i class="fa fa-save"></i>
                                        Save</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div><!-- create model End -->
        </section>
    </div><!-- pc-container end -->
@endsection


@section('javascript')

    <script>
         
        function create(){
            window.location.href = "{{ route('addBills')}}";
        }
    
        function paybill(){
            window.location.href = "{{ route('payBills')}}";
        }
    
        function seeBills(id){
            window.open("{{url('account/bill/details')}}"+"/"+id);
        }

        $(document).ready(function(){
            table = $('#manageBillTable').DataTable({
                'ajax': "{{route('getBill')}}",
                processing:true,
            });
        });



    </script>

@endsection
