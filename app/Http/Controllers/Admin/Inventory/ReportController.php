<?php

namespace App\Http\Controllers\Admin\Inventory;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\inventory\Category;
use App\Models\inventory\Product;
use App\Models\inventory\Purchase;
use App\Models\inventory\Sale;
use App\Models\inventory\SaleProduct;
use App\Models\inventory\PaymentVoucher;
use App\Models\inventory\Brand;
use App\Models\inventory\Party;
use App\Models\inventory\PurchaseProduct;
use App\Models\inventory\PaymentMethod;
use App\Models\User;
use Illuminate\Support\Facades\Session;


use DB;
use PDF;

class ReportController extends Controller
{

   public function productReport(Type $var = null)
   {
      $data['categories']=Category::where('deleted','No')->where('status','Active')->get();
		$data['brands']=Brand::where('deleted','No')->where('status','Active')->get();
		$data['products']=Product::where('deleted','No')->where('status','Active')->get();

        return view('admin.inventory.report.product-ledger', $data);
   }

   public function productLedgerView(Request $request){

      $from = $request->dateFrom .' 00:00:01' ;
      $to = $request->dateTo .' 23:59:59' ;

      $purchaseProducts = DB::table("purchase_products")
      ->where('created_date','>=' ,$from)
      ->where('created_date','<=' ,$to)
      ->where('product_id', $request->productId)
      ->select("id", "product_id","created_date",  "quantity", DB::Raw("'Purchase' as report_type"));

       $saleProducts = DB::table("sale_products")
      ->where('created_date','>=' ,$from)
      ->where('created_date','<=' ,$to)
      ->where('product_id', $request->productId)
      ->select("id", "product_id", "created_date",  "quantity", DB::Raw("'Sale' as report_type"));
      $saleReturnProducts = DB::table("sale_product_returns")
      ->where('created_date','>=' ,$from)
      ->where('created_date','<=' ,$to)
      ->where('sale_product_id', $request->productId)
      ->select("id", "sale_product_id as product_id","created_date",  "return_qty as quantity", DB::Raw("'Sale Return' as report_type"));
      $damageProducts = DB::table("damage_products")
      ->where('damage_date','>=' ,$from)
      ->where('damage_date','<=' ,$to)
      ->where('products_id', $request->productId)
      ->select("id", "products_id as product_id","created_date", "damage_quantity as quantity", DB::Raw("'damage' as report_type"));

      $multipleUnion = DB::table("purchase_product_returns")
      ->where('created_date','>=' ,$from)
      ->where('created_date','<=' ,$to)
      ->where('purchase_product_id', $request->productId)
         ->select("id", "purchase_product_id as product_id","created_date", "return_qty as quantity", DB::Raw("'Purchase Return' as report_type"))
         ->union($purchaseProducts)
         ->union($saleProducts)
         ->union($saleReturnProducts)
         ->union($damageProducts)
         ->orderBy('created_date', 'ASC')
         ->get();


         $product =Product::where('deleted','No')
         ->where('deleted','No')
         ->where('status','Active')
         ->where('id', $request->productId)
         ->first();

      //$grandTotal = 777777777;
		$cart='';
		$i=1;
      $totalIn = 0;
      $totalOut = 0;
      $balance = 0;
      $openning= '<tr class="bg-success"><td>1</td><td colspan="2" >Opening Stock</td><td>'.$product->opening_stock.'</td>';
         foreach($multipleUnion as $report){
            $stockIn = '';
            $stockOut = '';
            if ($report->report_type == 'Purchase' || $report->report_type == 'Sale Return') {
               $stockIn = $report->quantity;
               $totalIn += $stockIn;
               $balance += $report->quantity;
            } else {
               $stockOut = $report->quantity;
               $totalOut += $stockOut;
               $balance -= $report->quantity;
            }

					//$totalPrice = 99;
				$cart .= '<tr><td>'.$i++.'</td>'.
				'<td>'.$report->created_date.'</td>'.
				'<td>'.$report->report_type.'</td>'.
				'<td>'.$stockIn.'</td>'.
				'<td>'.$stockOut.'</td>'.
            '<td>'.$balance.'</td>'.
				'</tr>';
		   }
		$cart .= '<tr><td colspan="2" class="text-center font-weight-bold" ></td><td class="text-center font-weight-bold">Total = </td><td class="text-center font-weight-bold">'.$totalIn.'.00</td><td class="text-center font-weight-bold">'.$totalOut.'.00</td><td class="text-center font-weight-bold">'.$balance.'.00</td></tr>';
		$data = array( 
			'cart'=>$cart, 
			'totalAmount'=>'text here'
		); 
		return $data;

	}

   public function generateProductReport($id, $from, $to)
   {
      
      $startAndEndDate = array($from, $to );
      $productId = $id;
      $from = $from .' 00:00:01' ;
      $to = $to .' 23:59:59' ;

      $purchaseProducts = DB::table("purchase_products")
      ->where('created_date','>=' ,$from)
      ->where('created_date','<=' ,$to)
      ->where('product_id', $productId)
      ->select("purchase_products.id", "purchase_products.product_id","purchase_products.created_date", "purchase_products.quantity",DB::Raw("'Purchase' as report_type"));

      $saleProducts = DB::table("sale_products")
      ->where('created_date','>=' ,$from)
      ->where('created_date','<=' ,$to)
      ->where('product_id', $productId)
      ->select("sale_products.id", "sale_products.product_id", "sale_products.created_date", "sale_products.quantity", DB::Raw("'Sale' as report_type"));
      $saleReturnProducts = DB::table("sale_product_returns")
      ->where('created_date','>=' ,$from)
      ->where('created_date','<=' ,$to)
      ->where('sale_product_id', $productId)
      ->select("sale_product_returns.id", "sale_product_returns.sale_product_id as product_id","sale_product_returns.created_date", "sale_product_returns.return_qty as quantity", DB::Raw("'Sale Return' as report_type"));
      $damageProducts = DB::table("damage_products")
      ->where('damage_date','>=' ,$from)
      ->where('damage_date','<=' ,$to)
      ->where('products_id', $productId)
      ->select("damage_products.id", "damage_products.products_id as product_id","damage_products.created_date", "damage_products.damage_quantity as quantity", DB::Raw("'damage' as report_type"));

      $productLedger = DB::table("purchase_product_returns")
      ->where('created_date','>=' ,$from)
      ->where('created_date','<=' ,$to)
      ->where('purchase_product_id', $productId)
         ->select("purchase_product_returns.id", "purchase_product_returns.purchase_product_id as product_id","purchase_product_returns.created_date", "purchase_product_returns.return_qty as quantity", DB::Raw("'Purchase Return' as report_type"))
         ->union($purchaseProducts)
         ->union($saleProducts)
         ->union($saleReturnProducts)
         ->union($damageProducts)
         ->orderBy('created_date', 'ASC')
         ->get();

         $product = DB::table('products')
            ->join('brands', 'products.brand_id', '=', 'brands.id')
            ->join('units', 'products.unit_id', '=', 'units.id')
            ->select('products.*', 'brands.name as brand_name', 'units.name as unit_name')
            ->where('products.deleted','No')
            ->where('products.status','Active')
            ->where('products.id', $productId)
            ->get();
           // return $product;


         $userId  = auth()->user()->id;
         $userName = User::where('id', $userId)->pluck('name')->first();
         session(['userName' => $userName]);

         //return $productLedger;

         //return view('admin.inventory.report.product-report', ['productLedger'=> $productLedger, 'product'=> $product ]);

         $pdf = PDF::loadView('admin.inventory.report.product-report',  ['productLedger'=> $productLedger, 'product'=> $product, 'startAndEndDate'=>$startAndEndDate]);
         return $pdf->stream('product-report-pdf.pdf', array("Attachment" => false)); 
   }

   public function partyReport(Type $var = null)
   {
      $data['customers'] = Party::where('deleted','No')->where('party_type','Customer')->where('status','Active')->get();
      $data['suppliers'] = Party::where('deleted','No')->where('party_type','Supplier')->where('status','Active')->get();

        return view('admin.inventory.report.party-ledger', $data);
   }

   public function partyLedgerView(Request $request)
   {
       $partyId = $request->partyId;
     
      $from = $request->dateFrom;
      $to = $request->dateTo;
     
      $paymentVouchers =DB::table('payment_vouchers')
            ->join('parties', 'payment_vouchers.party_id', '=', 'parties.id')
            ->select('payment_vouchers.*', 'parties.name')
            ->where('payment_vouchers.party_id', $partyId)
            ->where('payment_vouchers.paymentDate', '>=', $from)
            ->where('payment_vouchers.paymentDate', '<=', $to)
            ->where('payment_vouchers.deleted', '=', 'No')
            ->orderBy('payment_vouchers.paymentDate','ASC')
            ->get();
   
      if($paymentVouchers){
      $info='';
      $button='';
      $i=1;
      $totalIn = 0;
      $totalOut = 0;
      $balance = 0;
      $dr = 0;
      $cr=0;
      $balanceG=0;
      $grandDr=0;
      $grandCr= 0;
      $grandBalance=0;

         //-------------opening balance-------------//
     $openingBalance = DB::table('payment_vouchers')
                        ->select(DB::raw("SUM(CASE payment_vouchers.type 
                           WHEN 'Party Payable' THEN payment_vouchers.amount
                           WHEN 'Payment Received' THEN -payment_vouchers.amount 
                           WHEN 'Adjustment' THEN  -payment_vouchers.amount
                           WHEN 'Payable' THEN -payment_vouchers.amount 
                           WHEN 'Payment' THEN payment_vouchers.amount
                           WHEN 'Payment Adjustment' THEN payment_vouchers.amount 
                           WHEN 'Discount' THEN -payment_vouchers.amount
                           END) AS total"), 'payment_vouchers.party_id')
                        ->where('payment_vouchers.paymentDate', '<', $from) 
                        ->where('payment_vouchers.party_id', $partyId)
                        ->where('payment_vouchers.deleted', 'No')
                        ->groupby('payment_vouchers.party_id')
                        ->first();
     
   
if ($openingBalance == '') {
$crAmount = '';
$drAmount = '';
$balance += 0;
} else if (floatval($openingBalance->total) > 0) {
$crAmount = $openingBalance->total;
$drAmount = '';

$balance += $crAmount;
} else {
$drAmount = $openingBalance->total;
$crAmount = '';

$balance -= $drAmount;
}

$openingBalance = $balance;
$grandCr= $openingBalance;
$info .= '<tr>
            <td>'.$i++.'</td>'.
            '<td colspan="3">Opening Balance - Before '.$request->dateFrom.'</td>'.
            '<td></td>'.
            '<td>'.$balance.'</td>'.
            '<td>'.$balance.'</td>'.
            
         '</tr>';
//-------------end opening balance test-------------//


         foreach($paymentVouchers as $paymentVoucher){
            $voucherType = $paymentVoucher->voucherType;
            $type = $paymentVoucher->type;
            $amount = $paymentVoucher->amount;
            
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
           }else if($type=='Discount'){
               $cr=$amount;
               $dr= '';
               $balance = $balance + $cr;
           }

            $info .= '<tr>
                        <td>'.$i++.'</td>'.
                        '<td>'.$paymentVoucher->paymentDate.'</td>'.
                        '<td>'.$paymentVoucher->voucherNo.'</td>'.
                        '<td>'.$voucherType.'<br>'.$type.'</td>'.
                        '<td>'.$dr.'</td>'.
                        '<td>'.$cr.'</td>'.
                        '<td>'.$balance.'</td>'.
                     '</tr>';
                     $grandDr += substr($dr, 0, -3);
                     $grandCr += substr($cr, 0, -3);
                     
                     $balanceG=$grandDr-$grandCr;
                  }

      $info .= '<tr>
                     <td></td>
                     <td></td>
                     <td></td>
                     <td class="text-center font-weight-bold">Total = </td>
                     <td colspan="" class="text-center font-weight-bold" >'.$grandDr.'</td>
                     <td class="text-center font-weight-bold">'.$grandCr .'</td>
                     <td class="text-center font-weight-bold">'.$balanceG.'</td>
               </tr>';
      
      $data = array( 
         'info'=>$info, 
         'totalAmount'=>'text here',
         
      ); 
      return $data;  
   }else{
      return "";
   }
       

	}
    public function getPartyData(Request $request){
      
         $parties=Party::where('party_type','=',$request->party_type)->where('deleted','No')->get();

         $info="<option value='' selected>Select Party</option>";
         $button='';
         $generateButton='';
            foreach($parties as $party){
               $info.="<option value='".$party->id."'>".$party->name."</option>";
               }



            if($request->party_type == 'Customer'){
               $button.='<button type="button" class="btn btn-primary btn btn-block p-3" onclick="getPartyLegder()">View Customer Ledger </button>';
            }else{
               $button.='<button type="button" class="btn btn-primary btn btn-block p-3" onclick="getPartyLegder()">View Supplier Ledger </button>';
            }


             if($request->party_type == 'Customer'){
               $generateButton.=' <a type="button" id="checkOutCart" class="btn btn-success my_button float-right" style="color:#fff;" onclick="generateReport()" target="_blank"> Generate Customer Report </a>';
            }else{
               $generateButton.=' <a type="button" id="checkOutCart" class="btn btn-success my_button float-right" style="color:#fff;" onclick="generateReport()" target="_blank"> Generate Supplier Report </a>';
            } 
         $data = array( 
            'info'=>$info, 
            'button'=>$button,
             'generateButton'=>$generateButton 
         ); 
           return $data;
         
  }
   public function generatePartyReport($data = null)
   {
      $balance = 0;
      $drTotal = 0;
      $crTotal = 0;
      $dataArray = explode(",", $data);
      $startAndEndDate = array($dataArray[1], $dataArray[2]);
      $id = $dataArray[0]; //party Id
      $from = $dataArray[1];
      $to = $dataArray[2];
     //-------------opening balance-------------//
     $openingBalance = DB::table('payment_vouchers')
                        ->select(DB::raw("SUM(CASE payment_vouchers.type 
                     WHEN 'Party Payable' THEN payment_vouchers.amount
                     WHEN 'Payment Received' THEN -payment_vouchers.amount 
                     WHEN 'Adjustment' THEN  -payment_vouchers.amount
                     WHEN 'Payable' THEN -payment_vouchers.amount 
                     WHEN 'Payment' THEN payment_vouchers.amount
                     WHEN 'Payment Adjustment' THEN payment_vouchers.amount 
                     WHEN 'Discount' THEN -payment_vouchers.amount
                     END) AS total"), 'payment_vouchers.party_id')
                         ->where('payment_vouchers.paymentDate', '<', $from) 
                        ->where('payment_vouchers.party_id', $id)
                        ->where('payment_vouchers.deleted', 'No')
                        ->groupby('payment_vouchers.party_id')
                        ->first();
                        
  
  if ($openingBalance == '') {
     $crAmount = '';
     $drAmount = '';
     $balance += 0;
  } else if (floatval($openingBalance->total) > 0) {
     $crAmount = $openingBalance->total;
     $drAmount = '';

     $balance += $crAmount;
  } else {
     $drAmount = $openingBalance->total;
     $crAmount = '';

     $balance -= $drAmount;
  }
  $openingBalance = $balance;
  //-------------end opening balance test-------------//

     


      $partyLedger =DB::table('payment_vouchers')
            ->join('parties', 'payment_vouchers.party_id', '=', 'parties.id')
            ->select('payment_vouchers.*', 'parties.name')
            ->where('payment_vouchers.party_id', $id)
            ->where('payment_vouchers.paymentDate', '>=', $from)
            ->where('payment_vouchers.paymentDate', '<=', $to)
            ->where('payment_vouchers.deleted', '=', 'No')
            ->orderBy('payment_vouchers.paymentDate','ASC')
            ->get();

       $party = DB::table('parties') ->where('id', $id)->get(); 
         
      $userId  = auth()->user()->id;
      $userName = User::where('id', $userId)->pluck('name')->first();
      session(['userName' => $userName]);

     
      $pdf = PDF::loadView('admin.inventory.report.party-report',  ['partyLedger'=> $partyLedger, 'party'=> $party,  'startAndEndDate'=>$startAndEndDate,'openingBalance'=>$openingBalance]);
      return $pdf->stream('party-report-pdf.pdf', array("Attachment" => false));

	}

   public function productCurrentStock(Type $var = null)
   {
      $data['categories']=Category::where('deleted','No')->where('status','Active')->get();
		$data['brands']=Brand::where('deleted','No')->where('status','Active')->get();
		$data['products']=Product::where('deleted','No')->where('status','Active')->get();
		$productStocks = Product::where('deleted','No')
      ->where('status','Active')
      ->orderBy('created_at', 'DESC')
      ->get();

      return view('admin.inventory.report.product-current-stock', $data, ['productStocks'=>$productStocks ]);
   }

   public function currentStock(Request $request)
   {
      $currentStock = '';
      $arrayIds = array();
      if (!empty($request->categoryId) && !empty($request->brandId) && !empty($request->id)) {
          $currentStock = Product::where('deleted','No')
         ->where('status','Active')
         ->where('id', $request->id)
         ->orderBy('created_at', 'DESC')
         ->get();
         // $arrayIds[0] = $request->categoryId ;
         // $arrayIds[1] = $request->brandId ;
         // $arrayIds[2] = $request->id ;
      } elseif (!empty($request->categoryId) && !empty($request->brandId)){
         $currentStock = Product::where('deleted','No')
         ->where('status','Active')
         ->where('category_id', $request->categoryId)
         ->where('brand_id', $request->brandId)
         ->orderBy('created_at', 'DESC')
         ->get();
      } elseif (!empty($request->categoryId)){
         $currentStock = Product::where('deleted','No')
         ->where('status','Active')
         ->where('category_id', $request->categoryId)
         ->orderBy('created_at', 'DESC')
         ->get();
      }
      //return response()->json(['success'=> $arrayIds]);
      
      $info='';
      $createReport ='<button type="button" class="btn btn-dark btn-lg btn-block" onclick="currentStockReport()"><i
      class="fas fa-print">  Current Stock Report   </i></button>';
      $createReport .='<input type="hidden" id="categoryId"  value="'.$request->categoryId.'"><input type="hidden" id="brandId"  value="'.$request->brandId.'"><input type="hidden" id="productId"  value="'.$request->id.'">';
		$i=1;
         foreach($currentStock as $report){
            
				$info .= '<tr><td>'.$i++.'</td>'.
				'<td>'.$report->name.'</td>'.
				'<td>'.$report->current_stock.'</td>'.
				'<td>'.$report->purchase_price.'</td>'.
				'<td>'.$report->sale_price.'</td>'.
				'</tr>';
		   }
		
		$data = array( 
			'info'=>$info, 
			'createReport'=>$createReport
		); 
		return $data;
   }

   public function currentStockReport($ids = null)
   {
         $ids = explode(",",$ids);
         $products = '' ;
         if ($ids[0] == -1) {
            // [-1] for All Category Products
            $products = DB::table('products')
            ->join('brands', 'products.brand_id', '=', 'brands.id')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->join('units', 'products.unit_id', '=', 'units.id')
            ->select('products.*', 'brands.name as brand_name', 'categories.name as category_name', 'units.name as unit_name')
            ->where('products.deleted','No')
            ->where('products.status','Active')
            ->get();
         } elseif (!empty($ids[0]) && !empty($ids[1] && !empty($ids[2]))){
            //[categoryId & brandId & productId] for Specific Product
            $products = DB::table('products')
            ->join('brands', 'products.brand_id', '=', 'brands.id')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->join('units', 'products.unit_id', '=', 'units.id')
            ->select('products.*', 'brands.name as brand_name', 'categories.name as category_name', 'units.name as unit_name')
            ->where('products.deleted','No')
            ->where('products.status','Active')
            ->where('products.id', $ids[2])
            ->get();
      } elseif (!empty($ids[0]) && !empty($ids[1])){
            // [categoryId & brandId] for Specific Category & Brand Productss
            $products = DB::table('products')
            ->join('brands', 'products.brand_id', '=', 'brands.id')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->join('units', 'products.unit_id', '=', 'units.id')
            ->select('products.*', 'brands.name as brand_name', 'categories.name as category_name', 'units.name as unit_name')
            ->where('products.deleted','No')
            ->where('products.status','Active')
            ->where('products.category_id', $ids[0])
            ->where('products.brand_id', $ids[1])
            ->get();
      } elseif (!empty($ids[0])){
         // [categoryId] for Specific Category Products
            $products = DB::table('products')
            ->join('brands', 'products.brand_id', '=', 'brands.id')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->join('units', 'products.unit_id', '=', 'units.id')
            ->select('products.*', 'brands.name as brand_name', 'categories.name as category_name', 'units.name as unit_name')
            ->where('products.deleted','No')
            ->where('products.status','Active')
            ->where('products.category_id', $ids[0])
            ->get();
      }

      $userId  = auth()->user()->id;
      $userName = User::where('id', $userId)->pluck('name')->first();
      session(['userName' => $userName]);

      //return view('admin.inventory.report.product-current-stock-report', ['currentStock'=> $currentStock, 'product'=> $product ]);

      $pdf = PDF::loadView('admin.inventory.report.product-current-stock-report',  ['products'=> $products]);
      return $pdf->stream('product-report-pdf.pdf', array("Attachment" => false)); 


   }
   
	public function dailyCashSalesReportView(Request $request){
	    return view('admin.inventory.report.dailyCashSalespdf-view');
	}
    public function dailyCashSaleReport(Request $request){
        $salesType=$request->cName;
        $sDate=$request->startDate;
        $eDate=$request->endtDate;
        $info = '<table class="table table-bordered" id="customers">
                    <thead>
                		<tr style="background: #3f3e93;color: white;">
                			<th class="hidden"></th>
                			<th>SL#</th>
                			<th>Date</th>
                			<th>Particulars</th>
                			<th>Phone</th>
                			<th>Type</th>
                			<th>SalesOrder</th>
                			<th>Discount</th>
                			<th>CashIn</th>
                			<th>CashOut</th>
                			<th>Balance</th>
                		</tr>
                    </thead>
                <tbody>';
            if($salesType == 'Party')
            {
                $daiyCashes = DB::table('payment_vouchers')
                            ->join('parties',function($join){
                                                        $join->on('payment_vouchers.party_id', '=', 'parties.id');
                                                        $join->on('parties.deleted', '=', DB::raw("'No'"));
                                                    })
                            ->leftjoin('purchases',function($join){
                                                        $join->on('payment_vouchers.purchase_id', '=', 'purchases.id');
                                                        $join->on('purchases.deleted', '=', DB::raw("'No'"));
                                                    })
                            ->leftjoin('sales',function($join){
                                                        $join->on('payment_vouchers.sales_id', '=', 'sales.id');
                                                        $join->on('sales.deleted', '=', DB::raw("'No'"));
                                                    })
                            ->leftjoin('purchase_returns',function($join){
                                                        $join->on('payment_vouchers.purchase_return_id', '=', 'purchase_returns.id');
                                                        $join->on('purchase_returns.deleted', '=', DB::raw("'No'"));
                                                    })
                            ->leftjoin('sale_returns',function($join){
                                                        $join->on('payment_vouchers.sales_return_id', '=', 'sale_returns.id');
                                                        $join->on('sale_returns.deleted', '=', DB::raw("'No'"));
                                                    })
                            ->where('payment_vouchers.deleted','No')
                            ->where('payment_vouchers.status','Active')
                            ->where('payment_vouchers.type','!=','Payable')
                            ->where('payment_vouchers.type','!=','Party Payable')
                            ->where('payment_vouchers.type','!=','Adjustment')
                            ->where('payment_vouchers.type','!=','Payment Adjustment')
                            ->where('payment_vouchers.payment_method','Cash')
                            ->where('parties.party_type','!=','Walkin_Custome')
                            ->whereBetween('payment_vouchers.paymentDate', [$sDate, $eDate])
                            ->select('payment_vouchers.id', 'payment_vouchers.amount', 'payment_vouchers.payment_method', 'payment_vouchers.paymentDate', 'payment_vouchers.type', 
                                    'payment_vouchers.remarks', 'payment_vouchers.type', 'payment_vouchers.voucherNo', 'payment_vouchers.customerType', 'parties.name as partyName',  
                                    'parties.address as tblCity','parties.contact as partyPhone', 'purchases.purchase_no', 'purchase_returns.purchase_return_no', 
                                    'sales.sale_no', 'sale_returns.sale_return_no')
                            ->orderByDesc('payment_vouchers.id')
                            ->get();
            }else if($salesType == 'WalkinCustomer'){
                $daiyCashes = DB::table('payment_vouchers')
                            ->join('parties',function($join){
                                                        $join->on('payment_vouchers.party_id', '=', 'parties.id');
                                                        $join->on('parties.deleted', '=', DB::raw("'No'"));
                                                    })
                            ->leftjoin('purchases',function($join){
                                                        $join->on('payment_vouchers.purchase_id', '=', 'purchases.id');
                                                        $join->on('purchases.deleted', '=', DB::raw("'No'"));
                                                    })
                            ->leftjoin('sales',function($join){
                                                        $join->on('payment_vouchers.sales_id', '=', 'sales.id');
                                                        $join->on('sales.deleted', '=', DB::raw("'No'"));
                                                    })
                            ->leftjoin('purchase_returns',function($join){
                                                        $join->on('payment_vouchers.purchase_return_id', '=', 'purchase_returns.id');
                                                        $join->on('purchase_returns.deleted', '=', DB::raw("'No'"));
                                                    })
                            ->leftjoin('sale_returns',function($join){
                                                        $join->on('payment_vouchers.sales_return_id', '=', 'sale_returns.id');
                                                        $join->on('sale_returns.deleted', '=', DB::raw("'No'"));
                                                    })
                            ->where('payment_vouchers.deleted','No')
                            ->where('payment_vouchers.status','Active')
                            ->where('payment_vouchers.type','!=','Payable')
                            ->where('payment_vouchers.type','!=','Party Payable')
                            ->where('payment_vouchers.type','!=','Adjustment')
                            ->where('payment_vouchers.type','!=','Payment Adjustment')
                            ->where('payment_vouchers.payment_method','Cash')
                            ->where('parties.party_type','=','Walkin_Custome')
                            ->whereBetween('payment_vouchers.paymentDate', [$sDate, $eDate])
                            ->select('payment_vouchers.id', 'payment_vouchers.amount', 'payment_vouchers.payment_method', 'payment_vouchers.paymentDate', 'payment_vouchers.type', 
                                    'payment_vouchers.remarks', 'payment_vouchers.type', 'payment_vouchers.voucherNo', 'payment_vouchers.customerType', 'parties.name as partyName',  
                                    'parties.address as tblCity','parties.contact as partyPhone', 'purchases.purchase_no', 'purchase_returns.purchase_return_no', 
                                    'sales.sale_no', 'sale_returns.sale_return_no')
                            ->orderByDesc('payment_vouchers.id')
                            ->get();
            }
            if($salesType == 'All')
            {
                $daiyCashes = DB::table('payment_vouchers')
                            ->join('parties',function($join){
                                                        $join->on('payment_vouchers.party_id', '=', 'parties.id');
                                                        $join->on('parties.deleted', '=', DB::raw("'No'"));
                                                    })
                            ->leftjoin('purchases',function($join){
                                                        $join->on('payment_vouchers.purchase_id', '=', 'purchases.id');
                                                        $join->on('purchases.deleted', '=', DB::raw("'No'"));
                                                    })
                            ->leftjoin('sales',function($join){
                                                        $join->on('payment_vouchers.sales_id', '=', 'sales.id');
                                                        $join->on('sales.deleted', '=', DB::raw("'No'"));
                                                    })
                            ->leftjoin('purchase_returns',function($join){
                                                        $join->on('payment_vouchers.purchase_return_id', '=', 'purchase_returns.id');
                                                        $join->on('purchase_returns.deleted', '=', DB::raw("'No'"));
                                                    })
                            ->leftjoin('sale_returns',function($join){
                                                        $join->on('payment_vouchers.sales_return_id', '=', 'sale_returns.id');
                                                        $join->on('sale_returns.deleted', '=', DB::raw("'No'"));
                                                    })
                            ->where('payment_vouchers.deleted','No')
                            ->where('payment_vouchers.status','Active')
                            ->where('payment_vouchers.type','!=','Payable')
                            ->where('payment_vouchers.type','!=','Party Payable')
                            ->where('payment_vouchers.type','!=','Adjustment')
                            ->where('payment_vouchers.type','!=','Payment Adjustment')
                            ->where('payment_vouchers.payment_method','Cash')
                            ->whereBetween('payment_vouchers.paymentDate', [$sDate, $eDate])
                            ->select('payment_vouchers.id', 'payment_vouchers.amount', 'payment_vouchers.payment_method', 'payment_vouchers.paymentDate', 'payment_vouchers.type', 
                                    'payment_vouchers.remarks', 'payment_vouchers.type', 'payment_vouchers.voucherNo', 'payment_vouchers.customerType', 'parties.name as partyName',  
                                    'parties.address as tblCity','parties.contact as partyPhone', 'purchases.purchase_no', 'purchase_returns.purchase_return_no', 
                                    'sales.sale_no', 'sale_returns.sale_return_no')
                            ->orderByDesc('payment_vouchers.id')
                            ->get();
            	}
            //$query = $conn->query($sql);
    		$idNo=1;
            //$query = $conn->query($sql);
    		$weQuantity=0;
    		$i=0;
    		$weQuantitybu=0;
    		$weQuantitybu12=0;
    		$cashIn = 0;
    		$cashOut = 0;
    		$balance=0;
    		$GrandTotalBlance = 0;
    		$TotalCashIn=0;
    		$TotalCashOut=0;
    		$Totaldiscount=0;
    		$TotalBalance=0;
    		//while($row12 = $query->fetch_assoc()){
    		foreach($daiyCashes as $daiyCash){
    			$i++;
    			$type=$daiyCash->type;
    			$amount=$daiyCash->amount;
    			/*$voucherType=$daiyCash->voucherType;
    			$amount=$daiyCash->amount;*/
		    
    			if($type == 'Payment Received'){
                    $cashIn =  $amount;
                    $cashOut='';
                    $balance = floatval($balance) + floatval($amount);
                    $discount= '';
                }
                else if ($type == 'Discount'){
                    $discount = $amount;
                    $cashIn ='';
                    $cashOut= '';
                } 
                else{
                    $cashOut = $amount;
                    $cashIn='';
                    $balance = floatval($balance) - floatval($amount);
                    $discount = '';
                }
    		    
			$carringCost=$daiyCash->amount;
		    $GrandTotalBlance+=floatval($carringCost);
		    $TotalCashIn+=floatval($cashIn);
		    $TotalCashOut+=floatval($cashOut);
		    $Totaldiscount+=floatval($discount);
		    $Totaldiscount+=floatval($discount);
		    $TotalBalance=floatval($TotalCashIn)-floatval($TotalCashOut);
			
              $info .= "
                <tr>
					<td class='hidden'></td>
					<td>".$i."</td>
					<td>".$daiyCash->paymentDate." </td>
					<td style='text-align:left;'>".$daiyCash->partyName." , ".$daiyCash->tblCity."<br>Remarks: ".$daiyCash->remarks."</td>
					<td style='text-align:center;'>".$daiyCash->partyPhone."</td>
					<td style='text-align:left;'>".$daiyCash->type."</td>
					<td>".$daiyCash->sale_no."</td>
					<td style='text-align: right;'>".$discount."</td>
					<td style='text-align: right;'>".$cashIn."</td>
					<td style='text-align: right;'>".$cashOut."</td>
					<td style='text-align: right;'>".number_format($balance,2)."</td>
					
                </tr>
				";
            }
            $info .= "
            <tr><td></td><td></td><td></td><td></td><td></td><td>Total</td><td><b>".number_format($Totaldiscount,2)."</b></td><td><b>".number_format($TotalCashIn,2)."</b></td><td><b>".number_format($TotalCashOut,2)."</b></td><td><b>".number_format($TotalBalance,2)."</b></td></tr>
			<a href='dailyCashSalesPdfViewPrint.php?sDate=".$sDate."&eDate=".$eDate."&salesType=".$salesType."' target='_blank' title='Issue Details' data-toggle='tooltip' class='btn btn-primary btn-sm btn-flat' style='margin-left: 1%; background: white;color: blue;margin-bottom: 1%;'><i class='fa fa-print'> Day Wise Cash Sales Reports Print </i></a>
			";
		  
            $info .= '</tbody>
        </table>';
        return $info;
    }
	public function paymentMethodWiseReceivedBalance(Request $request){
	    $data['paymentMethods']=PaymentMethod::where('deleted','No')->orderBy('tbl_paymentmethod.id', 'DESC')->get();
	    return view('admin.inventory.report.methodWiseCashLedger',$data);
	}
    public function methodwisereceivedreport(Request $request){
	    $paymentMethod=strtolower($request->cName);
	    $sDate=$request->startDate;
	    $eDate=$request->endtDate;
	    $info = '<div class="table-responsive"> 
                    <table class="table table-bordered" id="customers">
                    <thead>
            		<tr style="background: #3f3e93;color: white;">
            			<th class="hidden"></th>
            			<th>SL#</th>
            			<th>Date</th>
            			<th>Particulars</th>
            			<th>VoucherNo</th>
            			<th>CashIn</th>
            			<th>CashOut</th>
            			<th>Balance</th>
            		</tr>
                    </thead>
                    <tbody>';
        if($paymentMethod == 'all'){
            $methodReceives = DB::table('payment_vouchers')
                            ->join('parties',function($join){
                                                        $join->on('payment_vouchers.party_id', '=', 'parties.id');
                                                        $join->on('parties.deleted', '=', DB::raw("'No'"));
                                                    })
                            ->leftjoin('tbl_bank_account_info',function($join){
                                                        $join->on('payment_vouchers.tbl_bankInfoId', '=', 'tbl_bank_account_info.id');
                                                        $join->on('tbl_bank_account_info.deleted', '=', DB::raw("'No'"));
                                                    })
                            ->where('payment_vouchers.deleted','No')
                            ->where('payment_vouchers.status','Active')
                            ->whereRaw("(CASE  WHEN payment_vouchers.payment_method = 'CHEQUE' THEN payment_vouchers.paymentDate 
                                                               ELSE payment_vouchers.paymentDate
                                                               END) BETWEEN '".$sDate."' AND '".$eDate."'")
                            ->where(function($query){
                                    $query->where('payment_vouchers.type', 'Payment Received')
                                          ->orWhere('payment_vouchers.type', 'Payment')
                                          ->orWhere('payment_vouchers.type', 'Payment Adjustment')
                                          ->orWhere('payment_vouchers.type', 'Adjustment');
                                })
                            ->select('payment_vouchers.id', 'payment_vouchers.amount', 'payment_vouchers.payment_method', 'payment_vouchers.paymentDate', 'payment_vouchers.type', 'payment_vouchers.voucherType', 
                                    'tbl_bank_account_info.bankName','tbl_bank_account_info.branchName','tbl_bank_account_info.accountNo','tbl_bank_account_info.accountName','payment_vouchers.remarks', 
                                    'payment_vouchers.voucherNo', 'payment_vouchers.customerType', 'parties.name as partyName',  
                                    'parties.address as tblCity','parties.contact as partyPhone')
                            ->orderByDesc('payment_vouchers.created_at')
                            ->get();
        }
        else{               
            $methodReceives = DB::table('payment_vouchers')
                            ->join('parties',function($join){
                                                        $join->on('payment_vouchers.party_id', '=', 'parties.id');
                                                        $join->on('parties.deleted', '=', DB::raw("'No'"));
                                                    })
                            ->leftjoin('tbl_bank_account_info',function($join){
                                                        $join->on('payment_vouchers.tbl_bankInfoId', '=', 'tbl_bank_account_info.id');
                                                        $join->on('tbl_bank_account_info.deleted', '=', DB::raw("'No'"));
                                                    })
                            ->where('payment_vouchers.deleted','No')
                            ->where('payment_vouchers.status','Active')
                            ->whereRaw("LOWER(payment_vouchers.payment_method) = '".$paymentMethod."'")
                            ->whereRaw("(CASE  WHEN LOWER(payment_vouchers.payment_method) = 'cheque' THEN payment_vouchers.paymentDate 
                                                               ELSE payment_vouchers.paymentDate
                                                               END) BETWEEN '".$sDate."' AND '".$eDate."'")
                            ->where(function($query){
                                    $query->where('payment_vouchers.type', 'Payment Received')
                                          ->orWhere('payment_vouchers.type', 'Payment')
                                          ->orWhere('payment_vouchers.type', 'Payment Adjustment')
                                          ->orWhere('payment_vouchers.type', 'Adjustment');
                                })
                            ->select('payment_vouchers.id', 'payment_vouchers.amount', 'payment_vouchers.payment_method', 'payment_vouchers.paymentDate', 'payment_vouchers.type', 'payment_vouchers.voucherType',
                                    'tbl_bank_account_info.bankName','tbl_bank_account_info.branchName','tbl_bank_account_info.accountNo','tbl_bank_account_info.accountName','payment_vouchers.remarks', 
                                    'payment_vouchers.voucherNo', 'payment_vouchers.customerType', 'parties.name as partyName',  
                                    'parties.address as tblCity','parties.contact as partyPhone')
                            ->orderByDesc('payment_vouchers.created_at')
                            ->get();
        }
		$i=0;
		$cashIn = 0;
		$cashOut = 0;
		$partyName = '';
		$partyPhone = '';
		$balance = 0;
		$carringCost = 0;
		$GrandTotalBlance = 0;
		foreach($methodReceives as $methodReceive){
		    
			$i++;
		    $partyName = $methodReceive->partyName.' - '.$methodReceive->partyPhone;
			$type=$methodReceive->type;
			$amount=$methodReceive->amount;
			if($type == 'Payment Received'){
                $cashIn =  $amount;
                $cashOut='';
                $balance = floatval($balance) + floatval($amount);
            }else{
                $cashOut = $amount;
                $cashIn='';
                $balance = floatval($balance) - floatval($amount);
            }
			$carringCost=$methodReceive->amount;
		    $GrandTotalBlance+=floatval($carringCost);
            $info .= "<tr>
                        <td class='hidden'></td>
    					<td>".$i."</td>
    					<td>".$methodReceive->paymentDate."</td>
    					<td style='text-align:left;'>".$methodReceive->remarks." - ".$methodReceive->type."<br>Party: ".$partyName."<br><b>".$methodReceive->bankName." - ".$methodReceive->branchName." ".$methodReceive->accountName." - ".$methodReceive->accountNo."</b></td>
    					<td>".$methodReceive->voucherNo."</td>
    					<td>".$cashIn."</td>
    					<td>".$cashOut."</td>
    					<td>".$balance."</td>
                    </tr>";
        }
		$info .= "<a href='dailyCashSalesLedgerPdfViewPrint.php?sDate=".$sDate."&eDate=".$eDate."&paymentMethod=".$paymentMethod."' target='_blank' title='Issue Details' data-toggle='tooltip' class='btn btn-primary btn-sm btn-flat' style='margin-left: 1%; background: white;color: blue;margin-bottom: 1%;'><i class='fa fa-print'> Day Wise Cash ledger Reports Print </i></a>
                    </tbody>
                </table>
                </div>";
        return $info;
    }
    public function tsLedgerwiseView(Request $request){
	    $data['tsParties']=DB::table('tbl_tsalesproducts')
	                        ->leftjoin('tbl_temporary_sale',function($join){
                                        $join->on('tbl_tsalesproducts.tbl_tSalesId', '=', 'tbl_temporary_sale.id');
                                        $join->on('tbl_temporary_sale.deleted', '=', DB::raw("'No'"));
                                    })
                            ->leftjoin('parties','tbl_temporary_sale.tbl_customerId','parties.id')
                            ->where('tbl_tsalesproducts.deleted','No')
                            ->where('tbl_tsalesproducts.status','Running')
                            ->select('parties.id','parties.name as partyName','parties.address as partyAddress')
                            ->orderByDesc('tbl_temporary_sale.tSalesDate')
                            ->distinct()
                            ->get();
	    return view('admin.inventory.report.tsLedgerView',$data);
	}
    public function tsledgerallpdf(Request $request){
	    $tsParties=DB::table('tbl_tsalesproducts')
	                        ->leftjoin('tbl_temporary_sale',function($join){
                                        $join->on('tbl_tsalesproducts.tbl_tSalesId', '=', 'tbl_temporary_sale.id');
                                        $join->on('tbl_temporary_sale.deleted', '=', DB::raw("'No'"));
                                    })
                            ->leftjoin('parties','tbl_temporary_sale.tbl_customerId','parties.id')
                            ->where('tbl_tsalesproducts.deleted','No')
                            ->where('tbl_tsalesproducts.status','Running')
                            ->select('parties.id','parties.name as partyName','parties.address as partyAddress','parties.contact_person','parties.contact')
                            ->orderByDesc('tbl_temporary_sale.tSalesDate')
                            ->distinct()
                            ->get();
        $info = '';
        foreach($tsParties as $tsParty){
            $tsPartyId = $tsParty->id;
            $info .= '<table border="1" cellspacing="0" cellpadding="3">
                			<tr>
                				<td width="75%" class="supAddress">Customer Name :<font color="gray" class="supAddressFont">'.$tsParty->partyName.'</font><br>Customer Address :<font color="gray" class="supAddressFont">'.$tsParty->partyAddress.'</font><br>Contact Person :<font color="gray" class="supAddressFont">'.$tsParty->contact_person.' / '.$tsParty->contact.'</font></td>
                				<td width="25%" class="supAddress" ><span class="citiestd11">Print Date: ' . date("Y-m-d") .'</span></td>
                			</tr>
                		</table>
                		<br>
                		<table border="1" cellspacing="0" cellpadding="3">
                			<tr>
                				<th class="citiestd11" width="5%">SL#</th>
                				<th class="citiestd11" width="9%">Date</th>
                				<th class="citiestd11" width="8%">TS#</th>
                				<th class="citiestd11" width="18%">Product Name</th>
                				<th class="citiestd11" width="9%">Quantity</th>
                				<th class="citiestd11" width="9%">Ret Qty</th>
                				<th class="citiestd11" width="9%">SoldQty</th>
                				<th class="citiestd11" width="9%">Rem. Qty</th>
                				<th class="citiestd11" width="10%">Price</th>
                			</tr>';
			/*SELECT   
                    
                    WHERE tbl_tsalesproducts.deleted = 'No' AND tbl_tsalesproducts.status = 'Running' AND tbl_temporary_sale.tbl_customerId='".$tsId."'
                    GROUP BY tbl_tsalesproducts.id
                    ORDER BY tbl_temporary_sale.tbl_customerId, tbl_temporary_sale.tSalesDate*/
            $tsRemainingDatas=DB::table('tbl_tsalesproducts')
	                        ->leftjoin('tbl_temporary_sale',function($join){
                                        $join->on('tbl_tsalesproducts.tbl_tSalesId', '=', 'tbl_temporary_sale.id');
                                        $join->on('tbl_temporary_sale.deleted', '=', DB::raw("'No'"));
                                    })
                            ->leftjoin('products','tbl_tsalesproducts.tbl_productsId','products.id')
                            ->leftjoin('units','products.unit_id','units.id')
                            ->leftjoin('brands','products.brand_id','brands.id')
                            ->leftjoin('parties','tbl_temporary_sale.tbl_customerId','parties.id')
                            ->where('tbl_tsalesproducts.deleted','No')
                            ->where('tbl_tsalesproducts.status','Running')
                            ->where('tbl_temporary_sale.tbl_customerId', $tsPartyId)
                            ->selectRaw('tbl_temporary_sale.tsNo,tbl_tsalesproducts.quantity, tbl_tsalesproducts.returnedQuantity,tbl_tsalesproducts.amount, tbl_tsalesproducts.soldQuantity, tbl_temporary_sale.tsNo, 
                                        tbl_temporary_sale.tSalesDate, products.name as productName, products.code as productCode, products.model_no as modelNo, brands.name as brandName, units.name as unitName, 
                                        ifnull(tbl_tsalesproducts.quantity,0)-ifnull(tbl_tsalesproducts.returnedQuantity,0)-ifnull(tbl_tsalesproducts.soldQuantity, 0) as remainingQuantity')
                            ->orderByDesc('tbl_temporary_sale.tSalesDate')
                            ->get();
            $i = 0;
            foreach($tsRemainingDatas as $tsRemainingData){
                $i++;
    			$info .= '<tr>
        						<td class="citiestd20">'.$i.'</td>
        						<td class="citiestd20">'.$tsRemainingData->tSalesDate.'</td>
        						<td class="citiestd20">'.$tsRemainingData->tsNo.'</td>
            					<td class="citiestd21">'.$tsRemainingData->productName.'<br>Brand: '.$tsRemainingData->brandName.'<br>Model: '.$tsRemainingData->modelNo.'</td>
            					<td class="citiestd20">'.$tsRemainingData->quantity.' '.$tsRemainingData->unitName.'</td>
            					<td class="citiestd20">'.$tsRemainingData->returnedQuantity.' '.$tsRemainingData->unitName.'</td>
            					<td class="citiestd20">'.$tsRemainingData->soldQuantity.' '.$tsRemainingData->unitName.'</td>
            					<td class="citiestd20">'.$tsRemainingData->remainingQuantity.' '.$tsRemainingData->unitName.'</td>
            					<td class="citiestd20">'.$tsRemainingData->amount.'</td>
        					</tr>';
            }
            $info .= '</table><br /><hr /><br />';
        }
        $pdf = PDF::loadView('admin.inventory.report.tsLedgerAllpdf',  ['info'=> $info]);
        return $pdf->stream('product-report-pdf.pdf', array("Attachment" => false)); 
	}




    
    public function tsledgerpartypdf(Request $request){
        $spId = $request->id;
	    $tsParties=DB::table('tbl_tsalesproducts')
	                        ->leftjoin('tbl_temporary_sale',function($join){
                                        $join->on('tbl_tsalesproducts.tbl_tSalesId', '=', 'tbl_temporary_sale.id');
                                        $join->on('tbl_temporary_sale.deleted', '=', DB::raw("'No'"));
                                    })
                            ->leftjoin('parties','tbl_temporary_sale.tbl_customerId','parties.id')
                            ->where('tbl_tsalesproducts.deleted','No')
                            ->where('tbl_tsalesproducts.status','Running')
                            ->where('tbl_temporary_sale.tbl_customerId',$spId)
                            ->select('parties.id','parties.name as partyName','parties.address as partyAddress','parties.contact_person','parties.contact')
                            ->orderByDesc('tbl_temporary_sale.tSalesDate')
                            ->distinct()
                            ->get();
        $info = '';
        foreach($tsParties as $tsParty){
            $tsPartyId = $tsParty->id;
            $info .= '<table border="1" cellspacing="0" cellpadding="3">
                			<tr>
                				<td width="75%" class="supAddress">Customer Name :<font color="gray" class="supAddressFont">'.$tsParty->partyName.'</font><br>Customer Address :<font color="gray" class="supAddressFont">'.$tsParty->partyAddress.'</font><br>Contact Person :<font color="gray" class="supAddressFont">'.$tsParty->contact_person.' / '.$tsParty->contact.'</font></td>
                				<td width="25%" class="supAddress" ><span class="citiestd11">Print Date: ' . date("Y-m-d") .'</span></td>
                			</tr>
                		</table>
                		<br>
                		<table border="1" cellspacing="0" cellpadding="3">
                			<tr>
                				<th class="citiestd11" width="5%">SL#</th>
                				<th class="citiestd11" width="9%">Date</th>
                				<th class="citiestd11" width="8%">TS#</th>
                				<th class="citiestd11" width="18%">Product Name</th>
                				<th class="citiestd11" width="9%">Quantity</th>
                				<th class="citiestd11" width="9%">Ret Qty</th>
                				<th class="citiestd11" width="9%">SoldQty</th>
                				<th class="citiestd11" width="9%">Rem. Qty</th>
                				<th class="citiestd11" width="10%">Price</th>
                			</tr>';
			/*SELECT   
                    
                    WHERE tbl_tsalesproducts.deleted = 'No' AND tbl_tsalesproducts.status = 'Running' AND tbl_temporary_sale.tbl_customerId='".$tsId."'
                    GROUP BY tbl_tsalesproducts.id
                    ORDER BY tbl_temporary_sale.tbl_customerId, tbl_temporary_sale.tSalesDate*/
            $tsRemainingDatas=DB::table('tbl_tsalesproducts')
	                        ->leftjoin('tbl_temporary_sale',function($join){
                                        $join->on('tbl_tsalesproducts.tbl_tSalesId', '=', 'tbl_temporary_sale.id');
                                        $join->on('tbl_temporary_sale.deleted', '=', DB::raw("'No'"));
                                    })
                            ->leftjoin('products','tbl_tsalesproducts.tbl_productsId','products.id')
                            ->leftjoin('units','products.unit_id','units.id')
                            ->leftjoin('brands','products.brand_id','brands.id')
                            ->leftjoin('parties','tbl_temporary_sale.tbl_customerId','parties.id')
                            ->where('tbl_tsalesproducts.deleted','No')
                            ->where('tbl_tsalesproducts.status','Running')
                            ->where('tbl_temporary_sale.tbl_customerId', $tsPartyId)
                            ->selectRaw('tbl_temporary_sale.tsNo,tbl_tsalesproducts.quantity, tbl_tsalesproducts.returnedQuantity,tbl_tsalesproducts.amount, tbl_tsalesproducts.soldQuantity, tbl_temporary_sale.tsNo, 
                                        tbl_temporary_sale.tSalesDate, products.name as productName, products.code as productCode, products.model_no as modelNo, brands.name as brandName, units.name as unitName, 
                                        ifnull(tbl_tsalesproducts.quantity,0)-ifnull(tbl_tsalesproducts.returnedQuantity,0)-ifnull(tbl_tsalesproducts.soldQuantity, 0) as remainingQuantity')
                            ->orderByDesc('tbl_temporary_sale.tSalesDate')
                            ->get();
            $i = 0;
            foreach($tsRemainingDatas as $tsRemainingData){
                $i++;
    			$info .= '<tr>
        						<td class="citiestd20">'.$i.'</td>
        						<td class="citiestd20">'.$tsRemainingData->tSalesDate.'</td>
        						<td class="citiestd20">'.$tsRemainingData->tsNo.'</td>
            					<td class="citiestd21">'.$tsRemainingData->productName.'<br>Brand: '.$tsRemainingData->brandName.'<br>Model: '.$tsRemainingData->modelNo.'</td>
            					<td class="citiestd20">'.$tsRemainingData->quantity.' '.$tsRemainingData->unitName.'</td>
            					<td class="citiestd20">'.$tsRemainingData->returnedQuantity.' '.$tsRemainingData->unitName.'</td>
            					<td class="citiestd20">'.$tsRemainingData->soldQuantity.' '.$tsRemainingData->unitName.'</td>
            					<td class="citiestd20">'.$tsRemainingData->remainingQuantity.' '.$tsRemainingData->unitName.'</td>
            					<td class="citiestd20">'.$tsRemainingData->amount.'</td>
        					</tr>';
            }
            $info .= '</table><br /><hr /><br />';
        }
        $pdf = PDF::loadView('admin.inventory.report.tsLedgerAllpdf',  ['info'=> $info]);
        return $pdf->stream('product-report-pdf.pdf', array("Attachment" => false)); 
	}



    public function tsledgerparty(Request $request){
        $spId = $request->id;
	    $tsParties=DB::table('tbl_tsalesproducts')
	                        ->leftjoin('tbl_temporary_sale',function($join){
                                        $join->on('tbl_tsalesproducts.tbl_tSalesId', '=', 'tbl_temporary_sale.id');
                                        $join->on('tbl_temporary_sale.deleted', '=', DB::raw("'No'"));
                                    })
                            ->leftjoin('parties','tbl_temporary_sale.tbl_customerId','parties.id')
                            ->where('tbl_tsalesproducts.deleted','No')
                            ->where('tbl_tsalesproducts.status','Running')
                            ->where('tbl_temporary_sale.tbl_customerId',$spId)
                            ->select('parties.id','parties.name as partyName','parties.address as partyAddress','parties.contact_person','parties.contact')
                            ->orderByDesc('tbl_temporary_sale.tSalesDate')
                            ->distinct()
                            ->get();
        $info = '';
        foreach($tsParties as $tsParty){
            $tsPartyId = $tsParty->id;
            $info .= '<a href="'.url('report/tsledgerpartypdf/'.$spId).'" target="_blank" title="Issue Details" data-toggle="tooltip" class="btn btn-primary btn-sm btn-flat" style="margin-left: 1%; background: white;color: blue;margin-bottom: 1%;"><i class="fa fa-print"> Partywise TS Remaining Ledger </i></a>
            <br><table border="1" cellspacing="0" cellpadding="3">
                			<tr>
                				<td width="75%" class="supAddress">Customer Name :<font color="gray" class="supAddressFont">'.$tsParty->partyName.'</font><br>Customer Address :<font color="gray" class="supAddressFont">'.$tsParty->partyAddress.'</font><br>Contact Person :<font color="gray" class="supAddressFont">'.$tsParty->contact_person.' / '.$tsParty->contact.'</font></td>
                				<td width="25%" class="supAddress" ><span class="citiestd11">Print Date: ' . date("Y-m-d") .'</span></td>
                			</tr>
                		</table>
                		<br>
                		<table border="1" cellspacing="0" cellpadding="3">
                			<tr>
                				<th class="citiestd11" width="5%">SL#</th>
                				<th class="citiestd11" width="9%">Date</th>
                				<th class="citiestd11" width="8%">TS#</th>
                				<th class="citiestd11" width="18%">Product Name</th>
                				<th class="citiestd11" width="9%">Quantity</th>
                				<th class="citiestd11" width="9%">Ret Qty</th>
                				<th class="citiestd11" width="9%">SoldQty</th>
                				<th class="citiestd11" width="9%">Rem. Qty</th>
                				<th class="citiestd11" width="10%">Price</th>
                			</tr>';
			/*SELECT   
                    
                    WHERE tbl_tsalesproducts.deleted = 'No' AND tbl_tsalesproducts.status = 'Running' AND tbl_temporary_sale.tbl_customerId='".$tsId."'
                    GROUP BY tbl_tsalesproducts.id
                    ORDER BY tbl_temporary_sale.tbl_customerId, tbl_temporary_sale.tSalesDate*/
            $tsRemainingDatas=DB::table('tbl_tsalesproducts')
	                        ->leftjoin('tbl_temporary_sale',function($join){
                                        $join->on('tbl_tsalesproducts.tbl_tSalesId', '=', 'tbl_temporary_sale.id');
                                        $join->on('tbl_temporary_sale.deleted', '=', DB::raw("'No'"));
                                    })
                            ->leftjoin('products','tbl_tsalesproducts.tbl_productsId','products.id')
                            ->leftjoin('units','products.unit_id','units.id')
                            ->leftjoin('brands','products.brand_id','brands.id')
                            ->leftjoin('parties','tbl_temporary_sale.tbl_customerId','parties.id')
                            ->where('tbl_tsalesproducts.deleted','No')
                            ->where('tbl_tsalesproducts.status','Running')
                            ->where('tbl_temporary_sale.tbl_customerId', $tsPartyId)
                            ->selectRaw('tbl_temporary_sale.tsNo,tbl_tsalesproducts.quantity, tbl_tsalesproducts.returnedQuantity,tbl_tsalesproducts.amount, tbl_tsalesproducts.soldQuantity, tbl_temporary_sale.tsNo, 
                                        tbl_temporary_sale.tSalesDate, products.name as productName, products.code as productCode, products.model_no as modelNo, brands.name as brandName, units.name as unitName, 
                                        ifnull(tbl_tsalesproducts.quantity,0)-ifnull(tbl_tsalesproducts.returnedQuantity,0)-ifnull(tbl_tsalesproducts.soldQuantity, 0) as remainingQuantity')
                            ->orderByDesc('tbl_temporary_sale.tSalesDate')
                            ->get();
            $i = 0;
            foreach($tsRemainingDatas as $tsRemainingData){
                $i++;
    			$info .= '<tr>
        						<td class="citiestd20">'.$i.'</td>
        						<td class="citiestd20">'.$tsRemainingData->tSalesDate.'</td>
        						<td class="citiestd20">'.$tsRemainingData->tsNo.'</td>
            					<td class="citiestd21">'.$tsRemainingData->productName.'<br>Brand: '.$tsRemainingData->brandName.'<br>Model: '.$tsRemainingData->modelNo.'</td>
            					<td class="citiestd20">'.$tsRemainingData->quantity.' '.$tsRemainingData->unitName.'</td>
            					<td class="citiestd20">'.$tsRemainingData->returnedQuantity.' '.$tsRemainingData->unitName.'</td>
            					<td class="citiestd20">'.$tsRemainingData->soldQuantity.' '.$tsRemainingData->unitName.'</td>
            					<td class="citiestd20">'.$tsRemainingData->remainingQuantity.' '.$tsRemainingData->unitName.'</td>
            					<td class="citiestd20">'.$tsRemainingData->amount.'</td>
        					</tr>';
            }
            $info .= '</table>';
        }
        
        return $info;
	}




	public function datewiseReceivedBalance(Request $request){
	    return view('admin.inventory.report.datewiseReceivedView');
	}




	public function datewiseReceivedView(Request $request){
	    /*
	    SELECT SUM(tbl_paymentVoucher.amount) AS amount, tbl_paymentVoucher.paymentDate, DAYNAME(tbl_paymentVoucher.paymentDate) AS dayName, tbl_paymentVoucher.customerType
                    FROM tbl_paymentVoucher 
                    LEFT OUTER JOIN tbl_purchase ON tbl_paymentVoucher.tbl_purchaseId = tbl_purchase.id AND tbl_purchase.deleted = 'No'
                    LEFT OUTER JOIN tbl_sales ON tbl_paymentVoucher.tbl_sales_id = tbl_sales.id AND tbl_sales.deleted = 'No'
                    LEFT OUTER JOIN tbl_purchase_return ON tbl_paymentVoucher.tbl_purchase_return_id = tbl_purchase_return.id AND tbl_purchase_return.deleted = 'No'
                    LEFT OUTER JOIN tbl_sales_return ON tbl_paymentVoucher.tbl_sales_return_id = tbl_sales_return.id AND tbl_sales_return.deleted = 'No'
                    WHERE tbl_paymentVoucher.deleted = 'No' AND tbl_paymentVoucher.status= 'Active' AND tbl_paymentVoucher.type<>'payable' AND tbl_paymentVoucher.type<>'discount' AND tbl_paymentVoucher.type <> 'partyPayable' AND tbl_paymentVoucher.type<>'adjustment' AND tbl_paymentVoucher.type<>'paymentAdjustment' AND tbl_paymentVoucher.paymentMethod = 'CASH' AND tbl_paymentVoucher.paymentDate BETWEEN '".$sDate."' AND '".$eDate."'
                    GROUP BY tbl_paymentVoucher.paymentDate, tbl_paymentVoucher.customerType
                    ORDER BY tbl_paymentVoucher.paymentDate, tbl_paymentVoucher.customerType
	    */
	    $from = $request->from;
	    $to = $request->to;
	    $receivedBalances = DB::table('payment_vouchers')
	                        ->leftjoin('purchases',function($join){
                                            $join->on('payment_vouchers.purchase_id', '=', 'purchases.id');
                                            $join->on('purchases.deleted', '=', DB::raw("'No'"));
                                        })
                            ->leftjoin('sales',function($join){
                                            $join->on('payment_vouchers.sales_id', '=', 'sales.id');
                                            $join->on('sales.deleted', '=', DB::raw("'No'"));
                                        })
                            ->leftjoin('purchase_returns',function($join){
                                            $join->on('payment_vouchers.purchase_return_id', '=', 'purchase_returns.id');
                                            $join->on('purchase_returns.deleted', '=', DB::raw("'No'"));
                                        })
                            ->leftjoin('sale_returns',function($join){
                                            $join->on('payment_vouchers.sales_return_id', '=', 'sale_returns.id');
                                            $join->on('sale_returns.deleted', '=', DB::raw("'No'"));
                                        })
                            ->selectRaw('SUM(payment_vouchers.amount) AS amount, payment_vouchers.paymentDate, DAYNAME(payment_vouchers.paymentDate) AS dayName, payment_vouchers.customerType')
	                        ->where('payment_vouchers.deleted', '=', 'No')
	                        ->where('payment_vouchers.status','Active')
	                        ->where('payment_vouchers.type','!=','Discount')
	                        ->where('payment_vouchers.type','!=','Payable')
                            ->where('payment_vouchers.type','!=','Party Payable')
                            ->where('payment_vouchers.type','!=','Adjustment')
                            ->where('payment_vouchers.type','!=','Payment Adjustment')
                            ->where('payment_vouchers.payment_method','Cash')
                            ->where('payment_vouchers.paymentDate', '>=', $from)
                            ->where('payment_vouchers.paymentDate', '<=', $to)
                            ->groupby('payment_vouchers.paymentDate')
                            ->groupby('payment_vouchers.customerType')
                            ->orderBy('payment_vouchers.paymentDate','ASC')
                            ->orderBy('payment_vouchers.customerType','ASC')
                            ->get();
        $info = '<div class="table-responsive"> 
                    <a href="'.url('report/datewiseReceivedViewPdf/'.$from.'/'.$to).'" target="_blank" title="Issue Details" data-toggle="tooltip" class="btn btn-primary btn-sm btn-flat" style="margin-left: 1%; background: white;color: blue;margin-bottom: 1%;"><i class="fa fa-print"> Date Wise Cash Sales Reports Print </i></a>
                    <table class="table table-bordered" id="customers">
                    <thead>
            		<tr style="background: #3f3e93;color: white;">
            			<th class="hidden"></th>
            			<th>SL#</th>
            			<th>Date</th>
            			<th>Day Name</th>
            			<th>Customer Type</th>
            			<th>Total Amounts</th>
            		</tr>
                    </thead>
                    <tbody>';
        $i = 0;
        foreach($receivedBalances as $receivedBalance){
            $i++;
            $info .= '<tr>
        					<td class="hidden"></td>
        					<td>'.$i.'</td>
        					<td>'.$receivedBalance->paymentDate.' </td>
        					<td style="text-align: left;">'.$receivedBalance->dayName.'</td>
        					<td style="text-align: left;">'.$receivedBalance->customerType.'</td>
        					<td style="text-align: right;">'.number_format($receivedBalance->amount,2).'</td>
        					
                        </tr>';
        }
        $info .= "</tbody></table></div>";
        return $info;
	}



    
	public function datewiseReceivedViewPdf(Request $request){
	    $from = $request->from;
	    $to = $request->to;
	    $receivedBalances = DB::table('payment_vouchers')
	                        ->leftjoin('purchases',function($join){
                                            $join->on('payment_vouchers.purchase_id', '=', 'purchases.id');
                                            $join->on('purchases.deleted', '=', DB::raw("'No'"));
                                        })
                            ->leftjoin('sales',function($join){
                                            $join->on('payment_vouchers.sales_id', '=', 'sales.id');
                                            $join->on('sales.deleted', '=', DB::raw("'No'"));
                                        })
                            ->leftjoin('purchase_returns',function($join){
                                            $join->on('payment_vouchers.purchase_return_id', '=', 'purchase_returns.id');
                                            $join->on('purchase_returns.deleted', '=', DB::raw("'No'"));
                                        })
                            ->leftjoin('sale_returns',function($join){
                                            $join->on('payment_vouchers.sales_return_id', '=', 'sale_returns.id');
                                            $join->on('sale_returns.deleted', '=', DB::raw("'No'"));
                                        })
                            ->selectRaw('SUM(payment_vouchers.amount) AS amount, payment_vouchers.paymentDate, DAYNAME(payment_vouchers.paymentDate) AS dayName, payment_vouchers.customerType')
	                        ->where('payment_vouchers.deleted', '=', 'No')
	                        ->where('payment_vouchers.status','Active')
	                        ->where('payment_vouchers.type','!=','Discount')
	                        ->where('payment_vouchers.type','!=','Payable')
                            ->where('payment_vouchers.type','!=','Party Payable')
                            ->where('payment_vouchers.type','!=','Adjustment')
                            ->where('payment_vouchers.type','!=','Payment Adjustment')
                            ->where('payment_vouchers.payment_method','Cash')
                            ->where('payment_vouchers.paymentDate', '>=', $from)
                            ->where('payment_vouchers.paymentDate', '<=', $to)
                            ->groupby('payment_vouchers.paymentDate')
                            ->groupby('payment_vouchers.customerType')
                            ->orderBy('payment_vouchers.paymentDate','ASC')
                            ->orderBy('payment_vouchers.customerType','ASC')
                            ->get();
        $info = '<div class="table-responsive"> 
                    <table class="table table-bordered" id="customers">
                    <thead>
            		<tr style="background: #3f3e93;color: white;">
            			<th class="hidden"></th>
            			<th>SL#</th>
            			<th>Date</th>
            			<th>Day Name</th>
            			<th>Customer Type</th>
            			<th>Total Amounts</th>
            		</tr>
                    </thead>
                    <tbody>';
        $i = 0;
        foreach($receivedBalances as $receivedBalance){
            $i++;
            $info .= '<tr>
        					<td class="hidden"></td>
        					<td>'.$i.'</td>
        					<td>'.$receivedBalance->paymentDate.' </td>
        					<td style="text-align: left;">'.$receivedBalance->dayName.'</td>
        					<td style="text-align: left;">'.$receivedBalance->customerType.'</td>
        					<td style="text-align: right;">'.number_format($receivedBalance->amount,2).'</td>
        					
                        </tr>';
        }
        $info .= "</tbody></table></div>";
        $pdf = PDF::loadView('admin.inventory.report.datewiseReceivedViewPdf',  ['info'=> $info]);
        return $pdf->stream('product-report-pdf.pdf', array("Attachment" => false)); 
	}








    


 













}
