@extends('admin.master')
@section('title')
    Admin Banks List
@endsection


@section('content')
    <div class="container-fluid">
        
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Bank List</h3>
                    <div class="card-actions">
                        <button type="button" class="btn btn-primary" onclick="create()">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-arrows-exchange" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M7 10h14l-4 -4"/><path d="M17 14h-14l4 4"/></svg>
                            Transactions
                        </button>
                    </div>
                </div>
                <h3 class="text-center text-success">{{ Session::get('message') }}</h3>
                <div class="card-body">
                    <x-filter-bar route="{{ route('bankView') }}" searchPlaceholder="Search banks..." :sortOptions="['id' => 'ID', 'name' => 'Name']" :defaultSort="'id'" :defaultDirection="'DESC'" />
                    <div class="table-responsive">
                        <table class="table table-vcenter table-bordered table-hover" id="manageBankTable" width="100%">
                            <thead>
                                <tr class="bg-light">
                                    <td width="5%" class="text-center">Sl</td>
                                    <td width="90%" class="text-center">Banks</td>
                                    <td width="5%" class="text-center">Status</td>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($banks as $i => $bank)
                                <tr>
                                    <td class="text-center">{{ $banks->firstItem() + $i }}<input type="hidden" name="id" id="id" value="{{ $bank->id }}" /></td>
                                    <td>
                                        <span>{{ $bank->name }}
                                        @foreach ($bankChildsData[$bank->id] ?? [] as $child)
                                            <br>{{ $child->name }}--<b>Amount:</b>{{ $child->amount }}
                                        @endforeach
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        @if ($bank->status == 'Active')
                                        <i class="fas fa-check-circle" style="color:green; font-size:16px;" title="{{ $bank->status }}"></i>
                                        @else
                                        <i class="fas fa-times-circle" style="color:red; font-size:16px;" title="{{ $bank->status }}"></i>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3" class="text-center py-4 text-muted">No banks found.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    {{ $banks->links() }}
                </div><!-- Card Content end -->
        <!-- pc-container end -->
@endsection


@section('javascript')

    <script>
         
        function create(){
            window.location.href = "{{ route('transactionsView')}}";
        }
    
        
    
        function seeBills(id){
            window.open("{{url('account/bill/details')}}"+"/"+id);
        }

        function reloadDt(){
            location.reload();
        }



    </script>

@endsection
