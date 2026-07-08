@extends('admin.master')
@section('title')
    Admin Expense List
@endsection


@section('content')
    <div class="container-fluid">
        <section class="content box-border">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Expense List</h3>
                    <div class="card-actions">
                        <button type="button" class="btn btn-primary" onclick="create()">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-plus" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                            Add Expense
                        </button>
                    </div>
                    <h3 class="text-center text-success">{{ Session::get('message') }}</h3>
                </div>
                <div class="card-body">
                    <x-filter-bar route="{{ route('expenseView') }}" searchPlaceholder="Search expenses..." :sortOptions="['id' => 'ID', 'transaction_date' => 'Date', 'tbl_acc_expenses.particulars' => 'Particulars', 'amount' => 'Amount']" :defaultSort="'id'" :defaultDirection="'DESC'" />
                    <div class="table-responsive">
                        <table class="table table-vcenter table-bordered" id="manageExpenseTable" width="100%">
                            <thead>
                                <tr class="bg-light">
                                    <th width="5%" class="text-center">Sl</th>
                                    <th width="20%" class="text-center">Supplier</th>
                                    <th width="15%" class="text-center">Transaction Date</th>
                                    <th width="25%" class="text-center">Expense Reason</th>
                                    <th width="15%" class="text-center">Amount</th>
                                    <th width="12%" class="text-center">Status</th>
                                    <th width="8%" class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($expenses as $i => $expense)
                                <tr>
                                    <td class="text-center">{{ $expenses->firstItem() + $i }}<input type="hidden" name="id" value="{{ $expense->id }}" /></td>
                                    <td>{{ $expense->member_name }}</td>
                                    <td class="text-center">{{ $expense->transaction_date }}</td>
                                    <td>{{ $expense->particulars }}</td>
                                    <td class="text-end">{{ $expense->amount }}</td>
                                    <td class="text-center">
                                        @if ($expense->status == 'Active')
                                            <i class="fas fa-check-circle" style="color:green; font-size:16px;" title="{{ $expense->status }}"></i>
                                        @else
                                            <i class="fas fa-times-circle" style="color:red; font-size:16px;" title="{{ $expense->status }}"></i>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-primary dropdown-toggle btn-sm" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                 <i class="fas fa-cog"></i>
                                             </button>
                                             <div class="dropdown-menu dropdown-menu-end">
                                                 <a class="dropdown-item" href="#/" onclick="seeExpense({{ $expense->id }})"><i class="fa fa-file-pdf me-2"></i> See Details</a>
                                             </div>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center text-muted">No expenses found.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-3">
                        {{ $expenses->links() }}
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
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <input type="hidden" id="editId">
                                    <div class="form-group mb-3">

                                        <label class="col-form-label">Name</label>
                                        <input type="text" class="form-control" id="editName" name="name"
                                            placeholder="Name">
                                        <span class="text-danger" id="editNameError">{{ $errors->has('name') ? $errors->first('name') : '' }}</span>
                                    </div>
                                    <div class="form-group mb-3">
                                        <label class="col-form-label">Slug</label>
                                        <input type="text" class="form-control" id="editSlug" name="slug"
                                            placeholder="Slug">
                                        <span class="text-danger" id="editSlugError">{{ $errors->has('slug') ? $errors->first('slug') : '' }}</span>
                                    </div>
                                    <div class="form-group mb-3">
                                        <label class="col-form-label">Code</label>
                                        <input type="text" class="form-control" id="editCode" name="code"
                                            placeholder="Code">
                                        <span
                                            class="text-danger" id="codeError">{{ $errors->has('code') ? $errors->first('code') : '' }}</span>
                                    </div>
                                    <div class="form-group mb-3">
                                        <label class="col-form-label">Parent</label>
                                        <select type="text" class="form-control" id="editParent_id" name="parent_id">
                                            <option value="0">Select Parent</option>
                                           
                                        </select>
                                        <span
                                            class="text-danger" id="editParent_idError">{{ $errors->has('parent_id') ? $errors->first('parent_id') : '' }}</span>
                                    </div>
                                    <div class="form-group mb-3">
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
                                    <button type="button" class="btn  btn-secondary mr-auto" data-bs-dismiss="modal">x
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
            window.location.href = "{{ route('addExpense')}}";
        }
    
        function seeExpense(id){
            window.open("{{url('expense/details')}}"+"/"+id);
        }





    </script>

@endsection
