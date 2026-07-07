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
            footer {position: fixed; bottom: 1cm; left: 0cm; right: 0cm;height: 1cm;text-align:center;}
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
            thead tr {background-color: #ffff;color: black;text-align: left;}
            .overline {text-decoration: overline;}
            .emi-table {width:80%;padding-left:10%;}
            .emi-table-title {padding-left:10px;margin-bottom:-5px;padding-left:11%;}
            .text-center{text-align:center;}
        </style>
        <title> Received Balance </title>
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
                <hr />
                {!!Session::get('companySettings')[0]['report_footer']!!}<br>
            </div>
        </footer>
        <main>
            <!-- Content Wrapper. Contains page content -->
            <div>
                <div style="text-align: center;">
                    <br><br>
                    <div class="citiestd13"> Datewise Received Balance </div>
                    {!!$info!!}
                </div>
            </div>
                  
        </main>   
    </body>
</html>
