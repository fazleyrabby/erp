<html>

<head>
   <style>
            #footer { position: fixed; right: 0px; bottom: 10px; text-align: center;border-top: 1px solid black;}
            #footer .page:after { content: counter(1, decimal); }
            @page {margin: 0.2cm 0.2cm 0.2cm 0.2cm;}

            /** Define now the real margins of every page in the PDF **/
            body { margin-top: 4.1cm;margin-left: 0.5cm;margin-right: 0.5cm;margin-bottom: 3cm;}

            /** Define the header rules **/
            header {position: fixed;top: .5cm;left: 0cm;right: 0cm;text-align:center;}


            /** Define the footer rules **/
            footer {position: fixed; bottom: 2cm; left: 0cm; right: 0cm;height: 1.8cm;text-align:center;}
            .column {float: left;width: 33.33%;height:30px;}

            /* Clear floats after the columns */
            .row:after {content: "";display: table;clear: both;}
            .signatures{padding-bottom:-500px;}
            .citiestd13 {background-color: rgb(242, 242, 242);border:1px solid gray;color: black;text-align: center;font-size: 13px;padding: 5px;}
            .supAddressFont {font-size:11px;}
            .supAddressFontEmi {font-size:13px;}
            .underAlignment {text-align:right;font-size:13px;}
            .underAlignmentLeft {text-align:left;font-size:13px;}
            .textLeft{text-align: left;font-size:12px;}
            .textRight{text-align: right;}
            .textCenter{text-align: center;}
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
            /*img {
              margin-top: 25px
            }*/
        </style>
    <title> Party Ledger </title>
</head>

<body>
<!-- Content Wrapper. Contains page content -->
    <header>
        <!-- Content Header (Page header) -->

            <h4>{{Session::get('companySettings')[0]['name']}}</h4>
            <div class="supAddressFont">
                {!!Session::get("companySettings")[0]["report_header"]!!}
            </div>
             

    </header>


    <footer>
   
   
           <div class="signatures">
           
                <div class="row ">
                    <div class="column">
                     
                        <br>-----------------------<br>                 
                        Customer Signature
                    </div>
                    <div class="column"> 
                       
                        <br>-----------------------<br>
                         Created By                        
                    </div>
                    <div class="column">
                      
                        <br>-----------------------<br>                       
                        Authorized Signature
                    </div>                 
                </div><br><br>
                <hr />
                {!!Session::get('companySettings')[0]['report_footer']!!}<br>
  
        
        </div>
        
    </footer>
<main>
        
<!-- Content Wrapper. Contains page content -->
<div>
    <div style="text-align: center;">
        <div class="citiestd13"> Party Ledger </div>
        <table  cellspacing="0" cellpadding="3">
            <tr>
                <td width="50%" class="supAddress">
                    @foreach ($party as $info)
                            <div><strong> Party Name : </strong> {{ $info->name }} </div>
                           
                            <div><strong> Phone : </strong> {{ $info->contact }}  </div>
                            <div><strong> Address : </strong> {{ $info->address }} </div>
                            @break
                    @endforeach
                </td>
                <td width="20%" class="supAddress"></td>
                <td width="30%" class="supAddress">
                        <div><strong>Start Date</strong> {{ $startAndEndDate[0] }}</div>
                        <div><strong>End Date: </strong> {{ $startAndEndDate[1] }}</div>
                        <div><strong>Print Date: </strong> {{ todayDate() }}</div>
                        <div><strong>Printed By: </strong> {{ Session::get('userName') }}</div>                    
                </td>
            </tr>
        </table>   
        <table border="1" class="invoice-info" cellspacing="0" cellpadding="3">
            <thead>
                         
                    <tr>
                    <th style="width:5%;">SL#</th>
                    <th style="width:10%;">Date</th>
                    <th style="width:14%;">Invoice No</th>
                    <th style="width:20%;">Voucher Type</th>
                    <th  style="width:17%;">Debit</th>
                    <th  style="width:17%;">Credit</th>
                    <th  style="width:17%;">Balance</th>
                   
                </tr>
            </thead>
            <tbody>
            @php 
                    $i = 1;
                    
                    $totalIn = 0;
                    $totalOut = 0;
                    $balance = $openingBalance;
                    $dr = 0;
                    $cr=$openingBalance;
                    $type=0;
                    $dueDr=0;
                    $dueCr=0;
                    $amount=0;
                    $grandDr=0;
                    $grandCr= $openingBalance;
                    
                @endphp
                        <tr>
                            <td>1</td>
                            <td colspan="3" class="citiestd15">Opening Balance - Before {{ $startAndEndDate[0] }}
                            </td>
                            <td class="text-center"></td>
                            <td class="text-center">{{ $cr}}</td>
                            <td></td>
                        </tr>  
               


               @foreach ($partyLedger as $report)
               
                @php
                    $type=$report->type;
                    $amount=$report->amount;
                

                if($type=='Payment Received'){
                    $cr=$amount;
                    $dr= '';
                    $balance = $balance + $cr;
                }

                else if($type=='Payable'){
                    $cr=$amount;
                    $dr= '';
                    $balance = $balance + $cr;
                }

                else if($type=='Adjustment'){
                    $cr=$amount;
                    $dr= '';
                    $balance = $balance + $cr;
                }
                else if($type=='Party Payable'){
                    $dr=$amount;
                    $cr= '';
                    $balance = $balance - $dr;
                }
                else if($type=='Payment'){
                    $dr=$amount;
                    $cr= '';
                    $balance = $balance - $dr;
                }
                else if($type=='Payment Adjustment'){
                    $dr=$amount;
                    $cr= '';
                    $balance = $balance - $dr;
                }
                else if($type=='Discount'){
                    $cr=$amount;
                    $dr= '';
                    $balance = $balance + $cr;
                }
               
                @endphp
               <tr>
                   <td class="text-center">{{$i++}}</td>
                   <td class="citiestd15 text-center">{{ $report->paymentDate }}</td> 
                   <td class="text-center">{{$report->voucherNo}} </td>
                   <td class="text-center">{{ $report->type }}</td>
                   <td class="text-center">{{$dr}}</td>
                   <td class="text-center">{{$cr}}</td>
                   <td class="text-center">{{ $balance }}</td>
               </tr>
               @php  $grandDr += substr($dr, 0, -3);
                     $grandCr += substr($cr, 0, -3);
                     $due=$grandDr-$grandCr;
                    $totalCredit=$grandCr+$due;
                    $dueDr=0;
                    $dueCr=0;
                    if($due < 0){
                        $dueDr=-($due);
                    }else{
                        $dueCr=$due;
                    }


                @endphp

               @endforeach
              
                <tr>
                    <td></td>
                    <td colspan="3" class="text-center">Total Balance </td>
                     <td class="text-center" ><b>{{$grandDr}}</b></td>
                     <td class="text-center"><b>{{$grandCr}}</b></td>
                     <td ></td>
                </tr>
                <tr>
                    <td></td>
                    <td colspan="3"  class="text-center"><b>Closing Balance</b></td>
                     <td class="text-center" ><b>{{$dueDr}}</b></td>
                     <td class="text-center"><b>{{$dueCr}}</b></td>
                    <td></td>
                </tr>
                <tr>
                    <td></td>
                    <td colspan="3"  class="text-center"></td>
                     <td class="text-center" ><b>{{($grandDr+$dueDr)}}</b></td>
                     <td class="text-center"><b>{{$grandCr + $dueCr}}</b></td>
                    <td></td>
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
