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
            footer {position: fixed; bottom: 2cm; left: 0cm; right: 0cm;height: 1.8cm;text-align:center;font-family: "Arial Narrow";font-size: 10pt;}
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
        </style>
    <title>Party Ledger</title>
</head>
<body>
<!-- Content Wrapper. Contains page content -->
    <header>
        <div class="row2">
            <div class="column2">
                <h4>{{Session::get('companySettings')[0]['name']}}</h4>
                <div class="supAddressFont">
                    {!!Session::get("companySettings")[0]["report_header"]!!}
                </div>
            </div>
            <div class="column2">
                <div class="textLeft"><strong>Customer Name:</strong> {{ $party->name . ' - ' . $party->code }}</div>
                <div class="textLeft"><strong>Phone: </strong> {{ $party->contact }}</div>
                <div class="textLeft"><strong>Address: </strong> {{ $party->address }}</div>
                <div class="textLeft"><strong>From Date: </strong> {{ $date_from}}</div>
                <div class="textLeft"><strong>To Date: </strong> {{ $date_to }}</div>
            </div>
        </div>
            
    </header>


    <footer>
   
   
           <div class="signatures">
           
                <div class="row g-3">
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
        <div class="citiestd13">Party Ledger </div>
        <table  cellspacing="0" cellpadding="3">
            <tr>
                <td width="60%" class="supAddress">
                  
                </td>
                <td width="40%" class="supAddress">
                   
                </td>
            </tr>
        </table>   
        <table border="1" class="invoice-info" cellspacing="0" cellpadding="3">
            <thead>
                <tr class="bg-light">
                    <td width="5%" class="text-center">Sl</td>
                    <td width="10%" class="text-center">Transaction Date</td>
                    <td width="20%" class="text-center">Voucher Title</td>
                    <td width="5%" class="text-center">Transaction No</td>
                    <td width="25%" class="text-center">Particulars</td>
                    <td width="10%" class="text-center">Debit</td>
                    <td width="10%" class="text-center">Credit</td>
                    <td width="15%" class="text-center">Balance</td>
                </tr>
            </thead>
            <tbody>
                @php 
                    $i = 1;
                    $data='';
                    $table='';
                    $total='';
                    $totalDebit=0;
                    $totalCredit=0;
                    $button='';
                    $balance= 0;

                @endphp
                    <tr>
                        <td colspan="5"class="text-center">Opening Balance:</td>
                        <td style="text-align:right;">{{numberFormat($debit)}}</td>
                        <td style="text-align:right;">{{numberFormat($credit)}}</td>
                        <td style="text-align:right;">{{numberFormat($debitToCredit)}}</td>
                    </tr>
                @foreach($vouchers as $voucher)
                    @php 
                    if($voucher->debit){
                        $balance += $voucher->debit;
                    }else{
                        $balance -= $voucher->credit;
                    }
                    @endphp
                    <tr>
                        <td class="text-center">{{$i++}}</td>
                        <td class="text-center">{{$voucher->transaction_date}}</td>
                        <td>{{$voucher->voucher_title}}</td>
                        <td class="text-center"># {{$voucher->voucherId}}</td>
                        <td>{{$voucher->particulars}}</td>
                        <td style="text-align:right;">{{$voucher->debit}}</td>
                        <td style="text-align:right;">{{$voucher->credit}}</td>
                        <td style="text-align:right;">{{numberFormat($balance)}}</td>
                    </tr>
                    @php 
                        $totalDebit +=$voucher->debit;
                        $totalCredit +=$voucher->credit;
                    @endphp

                @endforeach
                @php 
                    $due= $totalDebit-$totalCredit; 
                @endphp
                    <tr>
                        <td colspan="5" style="text-align:right;"><b>Total:</b></td>
                            <td style="text-align:right;"><b>{{$totalDebit}}.00</b></td>
                        <td style="text-align:right;"><b>{{$totalCredit}}.00</b></td>
                        <td style="text-align:right;"> <b> {{$due}}.00</b></td>
                    </tr>
                    @php 
                        if($totalDebit < $totalCredit){
                            $aDebit=$due;
                            $aCredit='0';
                        }elseif($totalDebit > $totalCredit){
                            $aDebit='0';
                            $aCredit=$due;
                        }else{
                            $aDebit='0';
                            $aCredit='0';
                        }
                    @endphp
                    <tr>
                        <td colspan="5" style="text-align:right;"><b>Closing Balance:</b></td>
                            <td style="text-align:right;"><b>{{$aDebit}}.00</b></td>
                        <td style="text-align:right;"><b>{{$aCredit}}.00</b></td>
                        <td></td>
                    </tr>
                    @php 
                        $totalDebitWithDue=$totalDebit-$aDebit;
                        $totalCreditWithDue=$totalCredit+$aCredit;
                    @endphp
                    <tr>
                        <td colspan="5"></td>
                        <td style="text-align:right;"><b>{{$totalDebitWithDue}}.00</b></td>
                        <td style="text-align:right;"><b>{{$totalCreditWithDue}}.00</b></td>
                        <td></td>
                    </tr>

                    <tr>
                        <td colspan="8" style="text-align:center;">
                            <b>In word: </b> {{ numberToWord($totalCredit) }} {{ Session::get('companySettings')[0]['currency'] }}
                        </td>
                    </tr>
            </tbody>
        </table>
    </div>
</div>



            </div>
            </div>
        </div>
    </section>
  </div>
    </main>   
</body>
</html>