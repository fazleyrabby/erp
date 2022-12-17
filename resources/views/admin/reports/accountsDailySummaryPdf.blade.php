<html>
<head>
     <style>
            #footer { position: fixed; right: 0px; bottom: 20px; text-align: center;border-top: 1px solid black;}
            #footer .page:after { content: counter(1, decimal); }
            @page {margin: 0.2cm 0.2cm 0.2cm 0.2cm;}

            /** Define now the real margins of every page in the PDF **/
            body { margin-top: 4.1cm;margin-left: 0.5cm;margin-right: 0.5cm;margin-bottom: 4cm;font-family: "Arial Narrow";font-size: 12pt;}

            /** Define the header rules **/
            header {position: fixed;top: .5cm;left: 0cm;right: 0cm;text-align:center;font-family: "Arial Narrow";font-size: 12pt;}
            

            /** Define the footer rules **/
            footer {position: fixed; bottom: 1cm; left: 0cm; right: 0cm;height: 1.8cm;text-align:center;font-family: "Arial Narrow";font-size: 10pt;}
            .column {float: left;width: 33.33%;height:30px;}

            /* Clear floats after the columns */
            .row:after {content: "";display: table;clear: both;}
            .signatures{padding-bottom:-500px;font-family: "Arial Narrow";font-size: 10pt;}
            .citiestd13 {background-color: rgb(242, 242, 242);border:1px solid gray;color: black;text-align: center;font-size: 13px;padding: 5px;}
            .supAddressFont {font-size:11px;}
            .underAlignment {text-align:right;font-size:13px;}
            .underAlignmentLeft {text-align:left;font-size:13px;}
            table {width:100%; border-collapse: collapse;margin-top: 10px;font-size: 0.8em; min-width: 400px;box-shadow: 0 0 20px rgba(0, 0, 0, 0.15);}
            /* .td-text{
                text-align:right;
                padding-right:35px ;
            } */
            thead tr {background-color: #ffff;color: black;text-align: left;}
            /* th, td {
                padding: 12px 15px;
            } */
            .overline {text-decoration: overline;}
            .emi-table {width:80%;padding-left:10%;}
            .emi-table-title {padding-left:10px;margin-bottom:-5px;padding-left:11%;}

            .text-center{text-align:center;}
            .column2 {
                float: left;
                width: 50%;
                padding: 10px;
                height: 300px; /* Should be removed. Only for demonstration */
                }

                /* Clear floats after the columns */

            .row2:after {
                content: "";
                display: table;
                clear: both;
                }

            .textLeft {
                text-align: left;
                font-size: 14px;
            }
            .text-right {
                text-align: right;
                padding-right:65px;
            }
        </style>
    <title>Daily Accounts Summary  {{$date}}</title>
</head>
<body>
<!-- Content Wrapper. Contains page content -->
    <header>
        <div class="row2">
            <div class="column2">
                <img src="{{'upload/images/'.Session::get('companySettings')[0]['logo']}}" width='320' height='125'>
                <div class="supAddressFont">
                    {!!Session::get("companySettings")[0]["report_header"]!!}
                </div>
            </div>
            <div class="column2">
               
            </div>
        </div>
            
    </header>


    <footer>
   
   
           <div class="signatures">
           
                <div class="row">
                    <div class="column">
                        Ali Tech
                        <br>-----------------------<br>                 
                        Customer Signature
                    </div>
                    <div class="column"> 
                        {{Session::get('userName')}}
                        <br>-----------------------<br>
                         Created By                        
                    </div>
                    <div class="column">
                        Manager
                        <br>-----------------------<br>                       
                        Authorized Signature
                    </div>                 
                </div><br><br>
                <hr />
                 <div style="font-size:10px;">{!!Session::get('companySettings')[0]['report_footer']!!}</div>
  
        
        </div>
        
    </footer>
<main>
        
<!-- Content Wrapper. Contains page content -->
<div>
    <div style="text-align: center;">
        <div class="citiestd13">Accounts Summary  (Date: {{$date}})</div>
        <table  cellspacing="0" cellpadding="3">
            <tr>
                <td width="60%" class="supAddress">
                  
                </td>
                <td width="40%" class="supAddress">
                   
                </td>
            </tr>
        </table>   
        <table class="table" border="1" width="100%">
            <tr>
                <th>Income</th>
                <th>Expense</th>
            </tr>
            <tr>
                <td>
                    <table class="table" >
                        <tbody>
                            <tr>
                                <td width="70%">Cash in hand</td>
                                <td width="30%" class="text-right">{{numberFormat($openingbalance)}}</td>
                            </tr>
                            @php
                                $totalIncome=0;
                            @endphp
                            @foreach($allIncomes as $income)
                                @php 
                                    $incomeAmounts=DB::table('tbl_acc_voucher_details')
                                                ->join('tbl_acc_vouchers','tbl_acc_voucher_details.tbl_acc_voucher_id','=','tbl_acc_vouchers.id')
                                                ->select('tbl_acc_voucher_details.*','tbl_acc_vouchers.transaction_date')
                                                ->where('tbl_acc_vouchers.transaction_date','=',$date)
                                                ->where('tbl_acc_voucher_details.tbl_acc_coa_id','=',$income->id)
                                                ->where('tbl_acc_vouchers.deleted','=','No')
                                                ->where('tbl_acc_vouchers.status','=','Active')
                                                ->get();
                                    $amountDebitSum=0; 
                                @endphp 
                                @foreach($incomeAmounts as $amount) 
                                    @php 
                                        $amountDebitSum +=$amount->debit;
                                    @endphp
                                @endforeach 
                                <tr>
                                    <td width="70%">{{$income->name}}</td>
                                    <td width="30%" class="text-right">{{numberFormat($amountDebitSum)}} </td>
                                </tr>
                                @php
                                    $totalIncome +=$amountDebitSum;
                                @endphp
                            @endforeach

                            @php
                                $totalSale=0;
                            @endphp 
                            @foreach($allsales as $sale)
                                @php 
                                    $saleAmounts=DB::table('tbl_acc_voucher_details')
                                                ->join('tbl_acc_vouchers','tbl_acc_voucher_details.tbl_acc_voucher_id','=','tbl_acc_vouchers.id')
                                                ->select('tbl_acc_voucher_details.*','tbl_acc_vouchers.transaction_date')
                                                ->where('tbl_acc_vouchers.transaction_date','=',$date)
                                                ->where('tbl_acc_voucher_details.tbl_acc_coa_id','=',$sale->id)
                                                ->where('tbl_acc_vouchers.deleted','=','No')
                                                ->where('tbl_acc_vouchers.status','=','Active')
                                                ->get();
                                    $amountDebitSum=0; 
                                @endphp 
                                @foreach($saleAmounts as $amount) 
                                    @php 
                                        $amountDebitSum +=$amount->debit;
                                    @endphp
                                @endforeach 
                                <tr>
                                    <td width="70%">{{$sale->name}}</td>
                                    <td width="30%" class="text-right">{{numberFormat($amountDebitSum)}} </td>
                                </tr>
                                @php
                                    $totalSale +=$amountDebitSum;
                                @endphp
                            @endforeach
                            @php 
                                $totalIncomeWithOpening=$openingbalance+$totalIncome +$totalSale;
                            @endphp
                            
                        </tbody>
                    </table>
                </td>
                <td>
                    <table class="table" >
                        <tbody>
                            <tr>
                                <td width="70%">Bill</td>
                                <td width="30%" class="text-right">{{numberFormat($billAmount)}}</td>
                            </tr>

                            @php
                                $totalpurchase=0;
                            @endphp 
                            @foreach($allpurchases as $purchase)
                                @php 
                                    $purchaseAmounts=DB::table('tbl_acc_voucher_details')
                                                ->join('tbl_acc_vouchers','tbl_acc_voucher_details.tbl_acc_voucher_id','=','tbl_acc_vouchers.id')
                                                ->select('tbl_acc_voucher_details.*','tbl_acc_vouchers.transaction_date')
                                                ->where('tbl_acc_vouchers.transaction_date','=',$date)
                                                ->where('tbl_acc_voucher_details.tbl_acc_coa_id','=',$purchase->id)
                                                ->where('tbl_acc_vouchers.deleted','=','No')
                                                ->where('tbl_acc_vouchers.status','=','Active')
                                                ->get();
                                    $amountDebitSum=0; 
                                @endphp 
                                @foreach($purchaseAmounts as $amount) 
                                    @php 
                                        $amountDebitSum +=$amount->debit;
                                    @endphp
                                @endforeach 
                                <tr>
                                    <td width="70%">{{$purchase->name}}</td>
                                    <td width="30%" class="text-right">{{numberFormat($amountDebitSum)}} </td>
                                </tr>
                                @php
                                    $totalpurchase +=$amountDebitSum;
                                @endphp
                            @endforeach


                            @php
                                $expenseSum=0;
                            @endphp
                            @foreach($allExpense as $expense)
                            @php
                                $incomeAmounts=DB::table('tbl_acc_voucher_details')
                                        ->join('tbl_acc_vouchers','tbl_acc_voucher_details.tbl_acc_voucher_id','=','tbl_acc_vouchers.id')
                                        ->select('tbl_acc_voucher_details.*','tbl_acc_vouchers.transaction_date','tbl_acc_vouchers.type')
                                        ->where('tbl_acc_vouchers.transaction_date','=',$date)
                                        ->where('tbl_acc_voucher_details.tbl_acc_coa_id','=',$expense->id)
                                        ->where('tbl_acc_vouchers.deleted','=','No')
                                        ->where('tbl_acc_vouchers.status','=','Active')
                                        ->get();
                                $amountSum=0;
                            @endphp
                            @foreach($incomeAmounts as $amount){
                                @php 
                                        $amountSum +=$amount->credit;
                                @endphp
                            @endforeach
                                
                            <tr>
                                <td width="70%">{{$expense->name}}</td>
                                <td width="30%" class="text-right">{{numberFormat($amountSum)}}</td>
                            </tr>
                            @php
                                $expenseSum +=$amountSum;
                            @endphp
                            @endforeach
                            @php
                                $totalExpense= $expenseSum+ $billAmount + $totalpurchase;
                            @endphp
                        </tbody>
                    </table>
                </td>
            </tr>
            
            <tr>
                <td>
                    <table class="table table-bordered table-hover dataTable no-footer">
                        <tbody>
                            <tr>
                                <td width="70%">Total Income:  {{Session::get('companySettings')[0]['currency']}}</td>
                                <td width="30%" class="text-right"> {{numberFormat($totalIncomeWithOpening)}}</td>
                            </tr>
                        </tbody>
                    </table>
                </td>
                <td>
                    <table class="table table-bordered table-hover dataTable no-footer">
                        <tbody>
                            <tr>
                                <td width="70%">Total Expense: {{Session::get('companySettings')[0]['currency']}} </td>
                                <td width="30%" class="text-right"> {{numberFormat($totalExpense)}} </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
            @php 
                $due=$totalIncomeWithOpening-$totalExpense;
                if($totalIncomeWithOpening < $totalExpense){
                    $incomeClosing=$due;
                    $expenseClosing='0';
                }elseif($totalIncomeWithOpening > $totalExpense){
                    $incomeClosing='0';
                    $expenseClosing=$due;
                }else{
                    $incomeClosing='0';
                    $expenseClosing='0';
                }
            @endphp 
            <tr>
                <td>
                    <table class="table table-bordered table-hover dataTable no-footer">
                        <tbody>
                            <tr>
                                <td width="70%">Balance Closing: {{Session::get('companySettings')[0]['currency']}}</td> 
                                <td width="30%" class="text-right">{{numberFormat($incomeClosing)}}</td>
                            </tr>
                        </tbody>
                    </table>
                </td>
                <td>
                    <table class="table table-bordered table-hover dataTable no-footer">
                        <tbody>
                            <tr>
                                <td width="70%">Balance Closing: {{Session::get('companySettings')[0]['currency']}}</td>
                                <td width="30%" class="text-right">{{numberFormat($expenseClosing)}}</td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
            @php 
                $totalIncomeWithDue=$totalIncomeWithOpening-$incomeClosing;
                $totalExpenseWithDue=$totalExpense+$expenseClosing;
            @endphp
            <tr>
                <td>
                    <table class="table table-bordered table-hover dataTable no-footer">
                        <tbody>
                            <tr>
                                <td width="70%">Total : {{Session::get('companySettings')[0]['currency']}}</td>
                                <td width="30%" class="text-right">{{numberFormat($totalIncomeWithDue)}} </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
                <td>
                    <table class="table table-bordered table-hover dataTable no-footer">
                        <tbody>
                            <tr>
                                <td width="70%">Total : {{Session::get('companySettings')[0]['currency']}}</td>
                                <td width="30%" class="text-right">{{numberFormat($totalExpenseWithDue)}} </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </table>



    </div>
</div>

<div class="notePart">
    <h4 style="text:danger;">Note:</h4>
    <h5><strong> Today Cash In Hands: </strong> {{numberFormat($due)}} {{Session::get('companySettings')[0]['currency']}} ({{numberToWord($due)}} only)</h5>
</div>

            </div>
            </div>
        </div>
    </section>
  </div>
    </main>   
</body>
</html>