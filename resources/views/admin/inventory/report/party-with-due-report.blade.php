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
    <title> Party with dues</title>
</head>

<body>
    <!-- Content Wrapper. Contains page content -->
    <header>
        <h4>{{Session::get('companySettings')[0]['name']}}</h4>
        <div class="supAddressFont">
            {!! Session::get('companySettings')[0]['report_header'] !!}
        </div>
    </header>


    <footer>


        <div class="signatures">

            <div class="row " style="font-size:14px;">
                <div class="column">
                    
                    <br>-----------------------<br>
                    Customer Signature
                </div>
                <div class="column">
                    {{ Session::get('userName') }}
                    <br>-----------------------<br>
                    Created By
                </div>
                <div class="column">
                    
                    <br>-----------------------<br>
                    Authorized Signature
                </div>
            </div><br><br>
            <hr />
            <div style="font-size:10px;">{!! Session::get('companySettings')[0]['report_footer'] !!}</div>


        </div>

    </footer>
    <main>

        <!-- Content Wrapper. Contains page content -->
        <div>
            <div style="text-align: center;">
                <div class="citiestd13"> Party Ledger With Dues Date {{ $startAndEndDate[0] }} To {{ $startAndEndDate[1] }}</div>
                <table cellspacing="0" cellpadding="3">
                    <tr>
                        <td width="70%" class="supAddress">
                            @foreach ($partiesWithDue as $user)
                                <div><strong>Party Type : </strong>{{ $startAndEndDate[2] }}</div>
                                {{-- <div><strong>Address: </strong>user->address</div> --}}
                            @break
                            @endforeach
                        </td>
                        <td width="30%" class="supAddress">
                            @foreach ($partiesWithDue as $info)
                                <div><strong id="invoiceNo">Printed Date: </strong> {{ todayDate() }}</div>
                                <div><strong>Printed By: </strong> {{ Session::get('userName') }}</div>
                                {{-- <div><strong>Entry By: </strong> info->entryBy</div> --}}
                            @break
                            @endforeach
                        </td>
                    </tr>
                </table>
                <table border="1" class="invoice-info" cellspacing="0" cellpadding="3">
                    <thead>
                        <tr>
                            <th width="6%">SL</th>
                            <th>Name</th>
                            <th>Contact</th>
                            <th>Address</th>
                            <th width="15%">Dues Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $i = 1;
                            $totalBalance = 0;
                        @endphp
                        @foreach ($partiesWithDue as $info)
                            @php
                                $totalBalance += $info->current_due;
                            @endphp
                            <tr>
                                <td class="textCenter">{{ $i++ }}</td>
                                <td class="citiestd15">{{ $info->name }} - {{ $info->code }}</td>
                                <td class="citiestd15">{{ $info->contact }} / {{ $info->alternate_contact }}
                                </td>
                                <td class="citiestd15">{{ $info->address }}</td>
                                <td class="textRight">{{ $info->current_due }}</td>
                            </tr>
                        @endforeach
                        <tr>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td class="text-center">Total = </td>
                            <td class="textRight"> {!! Session::get('companySettings')[0]['currency'] . ' ' . numberFormat($totalBalance) !!}</td>
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
