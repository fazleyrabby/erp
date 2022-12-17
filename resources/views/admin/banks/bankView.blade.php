@extends('admin.master')
@section('title')
    Admin Banks List
@endsection


@section('content')
    <div class="container-fluid">
        <section class="content box-border">
            <div class="card">
                <div class="card-header">
                    <h3>Bank List
                            <button type="button" class="btn  btn-primary float-right" onclick="create()"><i class="fas fa-money-bill-alt"></i>
                                Transactions</button>
                    </h3>
                    <h3 class="text-center text-success">{{ Session::get('message') }}</h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover dataTable no-footer" id="manageBankTable" width="100%">
                            <thead>
                                <tr class="bg-light">
                                    <td width="5%" class="text-center">Sl</td>
                                    <td width="90%" class="text-center">Banks</td>
                                    <td width="5%" class="text-center">Status</td>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div><!-- Card Content end -->
        </section>
    </div><!-- pc-container end -->
@endsection


@section('javascript')

    <script>
         
        function create(){
            window.location.href = "{{ route('transactionsView')}}";
        }
    
        
    
        function seeBills(id){
            window.open("{{url('account/bill/details')}}"+"/"+id);
        }

        $(document).ready(function(){
            table = $('#manageBankTable').DataTable({
                'ajax': "{{route('getBanks')}}",
                processing:true,
            });
        });



    </script>

@endsection
