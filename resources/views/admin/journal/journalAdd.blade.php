@extends('admin.master')
@section('title')
    Admin Add Journal
@endsection


@section('content')
    <div class="container-fluid">
        <section class="content box-border">
            <div class="card">
                <div class="card-header">
                    <h3>Add Journal</h3>
                    <h3 class="text-center text-danger">{{ Session::get('message') }}</h3>
                </div>
                <div class="card-body">
                    <form action="{{route('journalStore')}}" method="post">
                        @csrf
                    <div class="row">
                        <div class="col-md-4">
                            <label class="form-label">Transaction Date</label>
                            <input type="date" class="form-control" name="transaction_date">
                            <span class="text-danger" id="transaction_dateError">{{ $errors->has('transaction_date') ? $errors->first('transaction_date') : '' }}</span>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Reference</label>
                            <input type="text" class="form-control" name="reference" placeholder="Reference ">
                            <span class="text-danger" id="referenceError">{{ $errors->has('reference') ? $errors->first('reference') : '' }}</span>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Particulars</label>
                            <input type="text" class="form-control" name="internal_information" placeholder="Internal information">
                            <span class="text-danger" id="internal_informationError">{{ $errors->has('internal_information') ? $errors->first('internal_information') : '' }}</span>
                        </div>
                        
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover dataTable no-footer" id="manageJournalTable" width="100%">
                            <thead>
                                <tr class="bg-light">
                                    <td width="5%" class="text-center">Sl</td>
                                    <td width="30%" class="text-center">Account</td>
                                    <td width="30%" class="text-center">Particulars</td>
                                    <td width="15%" class="text-center">Debit</td>
                                    <td width="15%" class="text-center">Credit</td>
                                    <td width="5%" class="text-center">Action</td>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="row0">
                                    <td>#</td>
                                    <td>
                                        <select class="form-control ddl_account" name="account[]">
                                            <option value="0" selected>Choose COA</option>
                                            @php 
                                                $status='';
                                            @endphp
                                            @foreach($coas as $coa)
                                            @php
                                                if($coa->parent_id == '0' && $coa->unused == 'No'){
                                                    $status='';
                                                }elseif($coa->parent_id !== '0' && $coa->unused == 'No'){
                                                    $status='..';
                                                }else{
                                                    $status='.....';
                                                }
                                            @endphp
                                            <option  value="{{$coa->id}}">{{$status}} {{$coa->name}} - {{$coa->code}}</option>
                                            @endforeach
                                        </select>
                                        <span class="text-danger" id="accountError">{{ $errors->has('account') ? $errors->first('account') : '' }}</span>
                                    </td>
                                    <td>
                                        <input type="text" class="form-control" name="particular[]" placeholder="Particulars">
                                        <span class="text-danger" id="particularError">{{ $errors->has('particular') ? $errors->first('particular') : '' }}</span>
                                    </td>
                                    <td>
                                        <input type="number" class="form-control" name="debit[]" value="0" oninput="changeDebit(this)" onkeyup="equalization()" style="text-align:right;">
                                        <span class="text-danger" id="debitError">{{ $errors->has('debit') ? $errors->first('debit') : '' }}</span>
                                    </td>
                                    <td>
                                        <input type="number" class="form-control" name="credit[]" value="0" oninput="changeCredit(this)" onkeyup="equalization()" style="text-align:right;">
                                        <span class="text-danger" id="creditError">{{ $errors->has('credit') ? $errors->first('credit') : '' }}</span>
                                    </td>
                                    <td>
                                        <label style="display:none;">.</label><br><br>
                                        <a href="#/" class="text-danger" onclick="remove_btn(this)"><i class="fas fa-trash"></i></a>
                                    </td>
                                </tr>
                                <tr class="row1">
                                    <td>#</td>
                                    <td>
                                        <select class="form-control ddl_account" name="account[]">
                                            <option value="0" selected>Choose COA</option>
                                            @php 
                                                $status='';
                                            @endphp
                                            @foreach($coas as $coa)
                                            @php
                                                if($coa->parent_id == '0' && $coa->unused == 'No'){
                                                    $status='';
                                                }elseif($coa->parent_id !== '0' && $coa->unused == 'No'){
                                                    $status='..';
                                                }else{
                                                    $status='.....';
                                                }
                                            @endphp
                                            <option  value="{{$coa->id}}">{{$status}} {{$coa->name}} - {{$coa->code}}</option>
                                            @endforeach
                                        </select>
                                        <span class="text-danger" id="accountError">{{ $errors->has('account') ? $errors->first('account') : '' }}</span>
                                    </td>
                                    <td>
                                        <input type="text" class="form-control" name="particular[]" placeholder="Particulars">
                                        <span class="text-danger" id="particularError">{{ $errors->has('particular') ? $errors->first('particular') : '' }}</span>
                                    </td>
                                    <td>
                                        <input type="number" class="form-control" name="debit[]" value="0" oninput="changeDebit(this)" onkeyup="equalization()" style="text-align:right;">
                                        <span class="text-danger" id="debitError">{{ $errors->has('debit') ? $errors->first('debit') : '' }}</span>
                                    </td>
                                    <td>
                                        <input type="number" class="form-control" name="credit[]" value="0" oninput="changeCredit(this)" onkeyup="equalization()" style="text-align:right;">
                                        <span class="text-danger" id="creditError">{{ $errors->has('credit') ? $errors->first('credit') : '' }}</span>
                                    </td>
                                    <td>
                                        <label style="display:none;">.</label><br><br>
                                        <a href="#/" class="text-danger" onclick="remove_btn(this)"><i class="fas fa-trash"></i></a>
                                    </td>
                                </tr>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td>#</td>
                                    <td>
                                        
                                    </td>
                                    <td>
                                       
                                    </td>
                                    <td>
                                        <input type="text" class="form-control" name="debitTotal" id="debitTotal"  style="text-align:right;">
                                        <span class="text-danger" id="debitTotalError">{{ $errors->has('debitTotal') ? $errors->first('debitTotal') : '' }}</span>
                                    </td>
                                    <td>
                                        <input type="text" class="form-control" name="creditTotal" id="creditTotal"   style="text-align:right;">
                                        <span class="text-danger" id="creditTotalError">{{ $errors->has('creditTotal') ? $errors->first('creditTotal') : '' }}</span>
                                    </td>
                                    <td>
                                        
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                        
                    </div>
                    <div class="row">
                        <div class="col-md-12 ">
                            <div class="add-btn">
                                <button class="btn btn-primary m-2" id="btn_addRow"> <i class="fas fa-plus"></i> Add row</button>
                            </div>
                            <!-- <button type="submit" class="btn btn-success float-right  m-2" > <i class="fas fa-save"></i> Save</button> -->
                            <div class="submit-btn" id="submitBtn"></div><br><br>
                            <div class="submit-text" id="submitTxt"></div>

                        </div>
                    </div>
                    </form>
                </div><!-- Card Content end -->
               
               
        </section>
    </div><!-- pc-container end -->
@endsection


@section('javascript')
    <script>
        $(".ddl_account").each(function() {
            select2Class($(this));
        });
        function select2Class(ddl_account){
			$(ddl_account).select2({
                placeholder: "Select COA",
                allowClear: true,
                width:'100%'
		    });
        }

     
        function changeCredit(ctrl){  
            $(ctrl).parent().prev('td').find('input[name="debit[]"]').val("0");
            totalDbtCdt();
        }                           


        function changeDebit(ctrl){
            $(ctrl).parent().next('td').find('input[name="credit[]"]').val("0");
            totalDbtCdt();
        }          
               

        function totalDbtCdt(){
            var totalDebit = 0; 
            var totalCredit = 0; 
            $("input[name='debit[]']").each(function() {
                totalDebit += parseFloat($(this).val());
            });
            $("input[name='credit[]']").each(function() {
                totalCredit += parseFloat($(this).val());                                 
            });
            $("#debitTotal").val(totalDebit);
            $("#creditTotal").val(totalCredit);
        }     
        
        



        function equalization(){
            var debitTotal=$('#debitTotal').val();
            var creditTotal=$('#creditTotal').val();
            $.ajax({
                url:"{{route('getEqualization')}}",
                method:"GET",
                data:{"debitTotal":debitTotal,"creditTotal":creditTotal},
                datatype:"json",
                success:function(result){
                // alert(JSON.stringify(result));
                $('#submitBtn').html(result.button);
                $('#submitTxt').html(result.text);
                },error:function(response){
                // alert(JSON.stringify(response));
                }, beforeSend: function () {
                    $('#loading').show();
                },complete: function () {
                    $('#loading').hide();
                }
            });
        }


        var rowIndex = 2;
        $("#btn_addRow").click(function (e){
            e.preventDefault();
            var trData = $("#manageJournalTable tbody tr:last").html();
            $("#manageJournalTable tbody").append("<tr class='row"+rowIndex+"'>"+trData+"</tr>");
            var ddl = $("#manageJournalTable tbody tr:last td:nth-child(2) select");
            $(ddl).next().remove();
            var ind = $(".ddl_account").length-2;
            select2Class($(".ddl_account").eq(ind));
            select2Class($(".ddl_account").eq(ind+1));
            rowIndex++;
        })

         



        var i = 0;
        function remove_btn(remove_row){
            var rowNo = $('#manageJournalTable tbody tr').length;
            if(rowNo > 2){
                $(remove_row).parent().parent().remove();
            }else{
                Swal.fire("Last 2 rows cannot be removed");
                //alert("Not possible to remove last 2 rows");
            }
            totalDbtCdt();
            equalization();
        }






    </script>
@endsection
