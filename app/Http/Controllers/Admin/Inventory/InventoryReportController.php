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
use App\Models\User;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use App\Models\inventory\DailyReport;
use App\Models\Report\DailyReport as ReportDailyReport;
use PDF;
use Carbon\Carbon;


class InventoryReportController extends Controller
{

   public function productReport()
   {
      $data['categories'] = Category::where('deleted', 'No')->where('status', 'Active')->get();
      $data['brands'] = Brand::where('deleted', 'No')->where('status', 'Active')->get();
      $data['brands'] = Brand::where('deleted', 'No')->where('status', 'Active')->get();
      $data['products'] = Product::where('type','!=','service')->where('deleted', 'No')->where('status', 'Active')->get();

      return view('admin.inventory.report.product-ledger', $data);
   }


   public function generateProductReport($id, $from, $to)
   {
      $startAndEndDate = array($from, $to);
      $productId = $id;
      //$from = $from . ' 00:00:01';
      //$to = $to . ' 23:59:59';

      $purchaseProducts = DB::table("purchase_products")
         ->join('purchases', 'purchase_products.purchase_id', '=', 'purchases.id')
         ->leftjoin('parties', 'purchases.supplier_id', '=', 'parties.id')
         ->where('purchases.date', '>=', $from)
         ->where('purchases.date', '<=', $to)
         ->where('purchase_products.product_id', $productId)
         ->where('purchase_products.deleted', 'No')
         ->select("purchase_products.id","purchases.supplier_id", "parties.name","parties.address","parties.contact as mobile","purchase_products.product_id", "purchase_products.created_date", "purchase_products.quantity", "purchase_products.unit_price as price","purchases.purchase_no as invoice",'purchases.date', DB::Raw("'Purchase' as report_type"));

      $saleProducts = DB::table("sale_products")
         ->join('sales', 'sale_products.sale_id', '=', 'sales.id')
         ->leftjoin('parties', 'sales.customer_id', '=', 'parties.id')
         ->where('sales.date', '>=', $from)
         ->where('sales.date', '<=', $to)
         ->where('sale_products.product_id', $productId)
         ->where('sale_products.deleted', 'No')
         ->select("sale_products.id","sales.customer_id", "parties.name","parties.address","parties.contact as mobile", "sale_products.product_id", "sale_products.created_date", "sale_products.quantity", "sale_products.unit_price as price", "sales.sale_no as invoice",'sales.date', DB::Raw("'Sale' as report_type"));
      $saleReturnProducts = DB::table("sale_product_returns")
         ->join('sale_returns', 'sale_product_returns.sale_return_id', '=', 'sale_returns.id')
         ->leftjoin('parties', 'sale_returns.customer_id', '=', 'parties.id')
         ->where('sale_returns.sale_return_date', '>=', $from)
         ->where('sale_returns.sale_return_date', '<=', $to)
         ->where('sale_product_returns.product_id', $productId)
         ->where('sale_product_returns.deleted', 'No')
         ->select("sale_product_returns.id","sale_returns.customer_id", "parties.name","parties.address","parties.contact as mobile", "sale_product_returns.sale_product_id as product_id", "sale_product_returns.created_date", "sale_product_returns.return_qty as quantity", "sale_product_returns.unit_price as price","sale_returns.sale_return_no as invoice",'sale_returns.sale_return_date as date', DB::Raw("'Sale Return' as report_type"));
      $damageProducts = DB::table("damage_products")
         ->join('products', 'damage_products.products_id', '=', 'products.id')
         ->leftjoin('users', 'damage_products.created_by', '=', 'users.id')
         ->where('damage_products.damage_date', '>=', $from)
         ->where('damage_products.damage_date', '<=', $to)
         ->where('damage_products.products_id', $productId)
         ->where('damage_products.deleted', 'No')
         ->select("damage_products.id","damage_products.created_by", "users.name","users.address","users.mobile_no as mobile", "damage_products.products_id as product_id", "damage_products.created_date", "damage_products.damage_quantity as quantity", DB::Raw("'0' as price"),"damage_products.damage_order_no as invoice",'damage_products.damage_date as date', DB::Raw("'damage' as report_type"));

      $purchaseReturn = DB::table("purchase_product_returns")
         ->join('purchase_returns', 'purchase_product_returns.purchase_return_id', '=', 'purchase_returns.id')
         ->leftjoin('parties', 'purchase_returns.supplier_id', '=', 'parties.id')
         ->where('purchase_returns.purchase_return_date', '>=', $from)
         ->where('purchase_returns.purchase_return_date', '<=', $to)
         ->where('purchase_product_returns.product_id', $productId)
         ->where('purchase_product_returns.deleted', 'No')
         ->select("purchase_product_returns.id","purchase_returns.supplier_id", "parties.name","parties.address","parties.contact as mobile", "purchase_product_returns.purchase_product_id as product_id", "purchase_product_returns.created_date", "purchase_product_returns.return_qty as quantity", "purchase_product_returns.unit_price as price","purchase_returns.purchase_return_no as invoice",'purchase_returns.purchase_return_date as date', DB::Raw("'Purchase Return' as report_type"));
      $productLedger = $purchaseReturn
         ->union($purchaseProducts)
         ->union($saleProducts)
         ->union($saleReturnProducts)
         ->union($damageProducts)
         ->orderBy('created_date', 'ASC')
         ->get();
    
      $product = DB::table('products')
         ->join('categories', 'products.category_id', '=', 'categories.id')
         ->join('brands', 'products.brand_id', '=', 'brands.id')
         ->join('units', 'products.unit_id', '=', 'units.id')
         ->select('products.*', 'brands.name as brand_name', 'units.name as unit_name', 'categories.name as category_name')
         ->where('products.deleted', 'No')
         ->where('products.status', 'Active')
         ->where('products.id', $productId)
         ->first();

      $openingStockQuery = DB::table("purchase_products")
         ->join('purchases', 'purchase_products.purchase_id', '=', 'purchases.id')
         ->where('purchases.date', '<', $from)
         ->where('purchase_products.product_id', $productId)
         ->where('purchase_products.deleted', 'No')
         ->select(DB::raw("SUM(purchase_products.quantity) as stockInQty, 0 as stockOutQty"))
         ->union(
            DB::table("sale_products")
               ->join('sales', 'sale_products.sale_id', '=', 'sales.id')
               ->where('sales.date', '<', $from)
               ->where('sale_products.product_id', $productId)
               ->where('sale_products.deleted', 'No')
               ->select(DB::raw("0 as stockInQty,SUM(sale_products.quantity) as stockOutQty"))
         )
         ->union(
            DB::table("sale_product_returns")
               ->join('sale_returns', 'sale_product_returns.sale_return_id', '=', 'sale_returns.id')
               ->where('sale_returns.sale_return_date', '<', $from)
               ->where('sale_product_returns.product_id', $productId)
               ->where('sale_product_returns.deleted', 'No')
               ->select(DB::raw("SUM(sale_product_returns.return_qty) as stockInQty, 0 as stockOutQty"))
         )
         ->union(
            DB::table("purchase_product_returns")
               ->join('purchase_returns', 'purchase_product_returns.purchase_return_id', '=', 'purchase_returns.id')
               ->where('purchase_returns.purchase_return_date', '<', $from)
               ->where('purchase_product_returns.product_id', $productId)
               ->where('purchase_product_returns.deleted', 'No')
               ->select(DB::raw("0 as stockInQty, SUM(purchase_product_returns.return_qty) as stockOutQty"))
         )
         ->union(
            DB::table("damage_products")
             ->where('damage_products.damage_date', '<', $from)
             ->where('damage_products.products_id', $productId)
             ->where('damage_products.deleted', 'No')
             ->select(DB::raw("0 as stockInQty, SUM(damage_products.damage_quantity) as stockOutQty"))
         );
        
      $openingStock = DB::table($openingStockQuery)
         ->select(DB::raw("SUM(stockInQty)-SUM(stockOutQty) as openingStock"))
         ->first();

      $finalOpeningStock = $product->opening_stock + $openingStock->openingStock;

      $userId  = auth()->user()->id;
      $userName = User::where('id', $userId)->pluck('name')->first();

      session(['userName' => $userName]);


      $pdf = PDF::loadView('admin.inventory.report.product-report',  ['productLedgers' => $productLedger, 'product' => $product, 'startAndEndDate' => $startAndEndDate, 'finalOpeningStock' => $finalOpeningStock]);
      return $pdf->stream('product-report-pdf.pdf', array("Attachment" => false));
   }










   public function partyReport()
   {
      $data['customers'] = Party::where('deleted', 'No')->where('party_type', 'Customer')->where('status', 'Active')->get();
      $data['suppliers'] = Party::where('deleted', 'No')->where('party_type', 'Supplier')->where('status', 'Active')->get();

      return view('admin.inventory.report.party-ledger', $data);
   }











   /*public function partyLedgerView(Request $request)
   {
      $balance = 0;
      $i = 0;
      $info = '';
      $drTotal = 0;
      $crTotal = 0;
      $from = $request->dateFrom . ' 00:00:01';
      $to = $request->dateTo . ' 23:59:59';
      // erase from session
      Session::forget('from');
      Session::forget('to');
      // store in session
      session(['from' =>  $from, 'to' =>   $to]);
      $openingBalance = DB::table('payment_vouchers')
         ->select(DB::raw("Sum(CASE payment_vouchers.type 
        WHEN 'partyPayable' THEN payment_vouchers.amount
        WHEN 'paymentReceived' THEN -payment_vouchers.amount 
        WHEN 'adjustment' THEN  -payment_vouchers.amount
        WHEN 'payable' THEN -payment_vouchers.amount 
        WHEN 'payment' THEN payment_vouchers.amount
        WHEN 'paymentAdjustment' THEN payment_vouchers.amount 
        WHEN 'discount' THEN -payment_vouchers.amount
        END) AS total"), 'payment_vouchers.party_id')
         ->where('payment_vouchers.created_at', '<', $from)
         ->where('payment_vouchers.party_id', $request->partyId)
         ->where('payment_vouchers.deleted', 'No')
         ->groupby('payment_vouchers.party_id')
         ->first();
      //   return response()->json(['success'=> $openingBalance]);
      if ($openingBalance == '') {
         $crAmount = '';
         $drAmount = '';
         $balance += 0;
      } else if (floatval($openingBalance->total) > 0) {
         $crAmount = $openingBalance->total;
         $drAmount = '';
         $balance += $openingBalance->total;
      } else {
         $drAmount = $openingBalance->total;
         $crAmount = '';
         $balance -= $openingBalance->total;
      }
      $info .= '<tr><td>' . ($i + 1) . '</td>' .
         '<td>Before ' . $from . '</td>' .
         '<td></td>' .
         '<td>' . $drAmount . '</td>' .
         '<td>' . $crAmount . '</td>' .
         '<td>' . $balance . '</td>' .
         '</tr>';
      $party = DB::table('payment_vouchers')
         ->join('parties', 'payment_vouchers.party_id', '=', 'parties.id')
         ->leftJoin('purchases', 'payment_vouchers.purchase_id', '=', 'purchases.id')
         ->leftJoin('sales', 'payment_vouchers.sales_id', '=', 'sales.id')
         ->select('payment_vouchers.*', 'parties.name as userName', 'purchases.purchase_no', 'sales.sale_no')
         ->where('payment_vouchers.created_at', '>=', $from)
         ->where('payment_vouchers.created_at', '<=', $to)
         ->where('payment_vouchers.party_id', $request->partyId)
         ->get();
      foreach ($party as $report) {
         $drAmount = '';  // minus amount 
         $crAmount = '';  // plus amouont
         if ($report->type == 'Payable' || $report->type == 'Payment') {
            $drAmount = $report->amount;
            $balance += $report->amount;
            $drTotal += $report->amount;
         } else {
            $crAmount = $report->amount;
            $balance -= $report->amount;
            $crTotal += $report->amount;
         }
         $info .= '<tr><td>' . ((++$i) + 1) . '</td>' .
            '<td>' . $report->paymentDate . '</td>' .
            '<td>' . $report->type . '</td>' .
            '<td>' . $drAmount . '</td>' .
            '<td>' . $crAmount . '</td>' .
            '<td>' . $balance . '</td>' .
            '</tr>';
      }

      $info .= '<tr><td colspan="2" class="text-center font-weight-bold" ></td><td class="text-center font-weight-bold">Total = </td><td class="text-center font-weight-bold">' . $drTotal . '</td><td class="text-center font-weight-bold">' . $crTotal . '</td><td class="text-center font-weight-bold">' . $balance . '</td></tr>';
      $data = array(
         'info' => $info,
         'totalAmount' => 'text here'
      );
      return $data;
   }*/

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
      $party = DB::table('parties')
         ->where('deleted', 'No')
         ->where('status', 'Active')
         ->where('id', $id)
         ->first();
      $partyType = $party->party_type;

      //-------------opening balance-------------//
      $openingBalance = DB::table('payment_vouchers')
         ->select(DB::raw("CASE payment_vouchers.type 
      WHEN 'Party Payable' THEN payment_vouchers.amount
      WHEN 'Payment Received' THEN -payment_vouchers.amount 
      WHEN 'Adjustment' THEN  -payment_vouchers.amount
      WHEN 'Payable' THEN -payment_vouchers.amount 
      WHEN 'Payment' THEN payment_vouchers.amount
      WHEN 'Payment Adjustment' THEN payment_vouchers.amount 
      WHEN 'Discount' THEN -payment_vouchers.amount
      END AS total"), 'payment_vouchers.party_id')
         ->where('payment_vouchers.paymentDate', '<', $from)
         ->where('payment_vouchers.party_id', $id)
         ->where('payment_vouchers.deleted', 'No')
         //->groupby('payment_vouchers.party_id')
         ->get();
      //dd($openingBalance);
      if ($openingBalance == '') {
         $crAmount = '';
         $drAmount = '';
         $balance += 0;
      } else if (floatval($openingBalance->total) > 0) {
         $crAmount = $openingBalance->total;
         $drAmount = '';

         $balance += $openingBalance->total;
      } else {
         $drAmount = $openingBalance->total;
         $crAmount = '';

         $balance -= $openingBalance->total;
      }
      //dd($balance);
      $openingBalance = $balance;
      //-------------end opening balance test-------------//

      $partyLedger = DB::table('payment_vouchers')
         ->join('parties', 'payment_vouchers.party_id', '=', 'parties.id')
         ->leftJoin('purchases', 'payment_vouchers.purchase_id', '=', 'purchases.id')
         ->leftJoin('sales', 'payment_vouchers.sales_id', '=', 'sales.id')
         ->select('payment_vouchers.*', 'parties.name as userName', 'purchases.purchase_no', 'sales.sale_no')
         ->where('payment_vouchers.paymentDate', '>=', $from)
         ->where('payment_vouchers.paymentDate', '<=', $to)
         ->where('payment_vouchers.deleted', 'No')
         ->where('payment_vouchers.party_id', $id)
         ->get();


      //-----opening balance test-------------//
      //      $partyType = $party->party_type;
      //      $typeOfPayment = [];
      //      if ($partyType == 'Customer') {
      //         $typeOfPayment = ['Payable', 'Payment Received'];
      //      } else{
      //         // ($partyType == 'Supplier')
      //         $typeOfPayment = ['Party Payable', 'Payment'];
      //      }
      //
      //      $payableSum  = DB::table('payment_vouchers')
      //      ->leftjoin('sales', 'payment_vouchers.sales_id', '=', 'sales.id')
      //      ->where('payment_vouchers.party_id', '=', $id)
      //      ->where('payment_vouchers.paymentDate', '<', $from)
      //      ->where('payment_vouchers.type', '=', $typeOfPayment[0])
      //      ->sum('payment_vouchers.amount');
      //
      //      $paymentReceivedSum  = DB::table('payment_vouchers')
      //      ->leftjoin('sales', 'payment_vouchers.sales_id', '=', 'sales.id')
      //      ->where('payment_vouchers.party_id', '=', $id)
      //      ->where('payment_vouchers.paymentDate', '<', $from)
      //      ->where('payment_vouchers.type', '=', $typeOfPayment[1])
      //      ->sum('payment_vouchers.amount');
      //      $discountSum  = DB::table('payment_vouchers')
      //      ->leftjoin('sales', 'payment_vouchers.sales_id', '=', 'sales.id')
      //      ->where('payment_vouchers.party_id', '=', $id)
      //      ->where('payment_vouchers.paymentDate', '<', $from)
      //      ->where('payment_vouchers.type', '=', 'Discount')
      //      ->sum('payment_vouchers.amount');
      //
      //      if ($partyType == 'Customer') {
      //         $opening = (($payableSum+$discountSum)-$paymentReceivedSum) ;
      //      } else{
      //         $opening = ($discountSum-($paymentReceivedSum+$payableSum)) ;
      //      }
      //
      //       $opening = $payableSum-($paymentReceivedSum) ;

      //return $opening;
      //-----end opening balance test-------------//


      $userId  = auth()->user()->id;
      $userName = User::where('id', $userId)->pluck('name')->first();
      session(['userName' => $userName]);

      //return view('admin.inventory.report.party-report', ['partyLedger'=> $partyLedger, 'startAndEndDate'=>$startAndEndDate ]);

      $pdf = PDF::loadView('admin.inventory.report.party-report',  ['partyLedger' => $partyLedger, 'party' => $party,  'startAndEndDate' => $startAndEndDate, 'openingBalance' => $openingBalance]);
      return $pdf->stream('party-report-pdf.pdf', array("Attachment" => false));
   }

   public function partyWithDueView()
   {
      $parties = DB::table('parties')->distinct()->pluck('party_type');
      return view('admin.inventory.report.party-with-due', ['parties' => $parties]);
   }

   public function getPartyWithDue(Request $request)
   {
      $from = $request->dateFrom;
      $to = $request->dateTo;
      $partyType = $request->partyType;

      $partiesWithDue = DB::table('parties')
         ->join('payment_vouchers', 'parties.id', '=', 'payment_vouchers.party_id')
         ->select('parties.id', 'parties.name', 'parties.code', 'parties.contact', 'parties.address', 'parties.alternate_contact', 'parties.current_due')
         ->where('parties.deleted', 'No')
         ->where('parties.party_type', $partyType)
         ->whereBetween('parties.created_date', [$from, $to])
         ->where('parties.current_due', '>', 0)
         ->orderBy('parties.current_due', 'DESC')
         ->distinct()
         ->get();

      $info = '';
      $i = 1;
      $totalDuesAmount = 0;
      foreach ($partiesWithDue as $party) {
         $totalDuesAmount += $party->current_due;
         $info .= '<tr><td>' . ($i++) . '</td>' .
            '<td>' . $party->name . ' - ' . $party->code . '</td>' .
            '<td><span class="pr-2">' . $party->contact . ',</span>' . $party->alternate_contact . '</td>' .
            '<td>' . $party->address . '</td>' .
            '<td class="text-center">' . $party->current_due . '</td>' .
            '</tr>';
      }
      $info .= '<tr><td colspan="2" class="text-center font-weight-bold" ></td><td class="text-center font-weight-bold"></td><td class="text-center font-weight-bold">Total Dues = </td><td class="text-center font-weight-bold">TK.' . number_format($totalDuesAmount) . '</td></tr>';

      return response()->json(['success', 'data' => $info]);
   }

   public function generatePartyWithDueReport($from = null, $to = null, $partyType = null)
   {
      $startAndEndDate = array($from, $to, $partyType);

      $partiesWithDue = DB::table('parties')
         ->join('payment_vouchers', 'parties.id', '=', 'payment_vouchers.party_id')
         ->select('parties.id', 'parties.name', 'parties.code', 'parties.contact', 'parties.address', 'parties.alternate_contact', 'parties.current_due')
         ->where('parties.deleted', 'No')
         ->where('parties.party_type', $partyType)
         ->whereBetween('parties.created_date', [$from, $to])
         ->where('parties.current_due', '>', 0)
         ->orderBy('parties.current_due', 'DESC')
         ->distinct()
         ->get();

      $userId  = auth()->user()->id;
      $userName = User::where('id', $userId)->pluck('name')->first();
      session(['userName' => $userName]);

      $pdf = PDF::loadView('admin.inventory.report.party-with-due-report',  ['partiesWithDue' => $partiesWithDue, 'startAndEndDate' => $startAndEndDate]);
      return $pdf->stream('product-report-pdf.pdf', array("Attachment" => false));
   }

   public function productCurrentStock()
   {
      $data['categories'] = Category::where('deleted', 'No')->where('status', 'Active')->get();
      $data['brands'] = Brand::where('deleted', 'No')->where('status', 'Active')->get();
      $data['products'] = Product::where('deleted', 'No')->where('status', 'Active')->get();
      $productStocks = Product::where('deleted', 'No')
         ->where('status', 'Active')
         ->orderBy('created_at', 'DESC')
         ->get();

      return view('admin.inventory.report.product-current-stock', $data, ['productStocks' => $productStocks]);
   }

   /*public function currentStock(Request $request)
   {
      $currentStock = '';
      $arrayIds = array();
      if (!empty($request->categoryId) && !empty($request->brandId) && !empty($request->id)) {
         $currentStock = Product::where('deleted', 'No')
            ->where('status', 'Active')
            ->where('deleted', 'No')
            ->where('id', $request->id)
            ->orderBy('created_at', 'DESC')
            ->get();
      } elseif (!empty($request->categoryId) && !empty($request->brandId)) {
         $currentStock = DB::table('products')
         ->join('brands', 'products.brand_id', '=', 'brands.id')
         ->join('categories', 'products.category_id', '=', 'categories.id')
         ->join('units', 'products.unit_id', '=', 'units.id')
         ->select('products.*', 'brands.name as brand_name', 'categories.name as category_name', 'units.name as unit_name')
         ->where('products.deleted', 'No')
         ->where('products.status', 'Active')
         ->where('products.category_id', $request->categoryId)
         ->where('products.brand_id', $request->brandId)
         ->get();
      } elseif (!empty($request->categoryId)) {
         $currentStock = DB::table('products')
         ->join('brands', 'products.brand_id', '=', 'brands.id')
         ->join('categories', 'products.category_id', '=', 'categories.id')
         ->join('units', 'products.unit_id', '=', 'units.id')
         ->select('products.*', 'brands.name as brand_name', 'categories.name as category_name', 'units.name as unit_name')
         ->where('products.deleted', 'No')
         ->where('products.status', 'Active')
         ->where('products.category_id', $request->categoryId)
         ->get();
      }

      $info = '';
      $createReport = '<button type="button" class="btn btn-dark btn-lg btn-block" onclick="currentStockReport()"><i
      class="fas fa-print">  Current Stock Report   </i></button>';
      $createReport .= '<input type="hidden" id="categoryId"  value="' . $request->categoryId . '"><input type="hidden" id="brandId"  value="' . $request->brandId . '"><input type="hidden" id="productId"  value="' . $request->id . '">';
      $i = 1;
      $totalStock = 0;
      foreach ($currentStock as $report) {
         $totalStock += $report->current_stock;
         $info .= '<tr><td>' . $i++ . '</td>' .
            '<td>' . $report->name . '</td>' .
            '<td>' . $report->category_name . '</td>' .
            '<td>' . $report->brand_name . '</td>' .
            '<td class="text-center">' . $report->current_stock . '</td>' .
            '<td class="text-center">' . $report->purchase_price . '</td>' .
            '<td class="text-center">' . $report->sale_price . '</td>' .
            '</tr>';
      }
      $info .= '<tr> <td></td><td></td><td colspan="2" class="text-center font-weight-bold">Total Stock =</td><td class="text-center font-weight-bold">'.$totalStock.'</td><td></td></tr>';

      $data = array(
         'info' => $info,
         'createReport' => $createReport
      );
      return $data;
   }*/

   public function currentStockReport($ids = null)
   {
      $ids = explode(",", $ids);
      $currentStocks = '';
      if ($ids[0] == -1) {
         // [-1] for All Category Products
         $currentStocks = DB::table('products')
            ->join('brands', 'products.brand_id', '=', 'brands.id')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->join('units', 'products.unit_id', '=', 'units.id')
            ->select('products.*', 'brands.name as brand_name', 'categories.name as category_name', 'units.name as unit_name')
            ->where('products.deleted', 'No')
            ->where('products.status', 'Active')
            ->get();
      } elseif (!empty($ids[0]) && !empty($ids[1] && !empty($ids[2]))) {
         //[categoryId & brandId & productId] for Specific Product
         $currentStocks = DB::table('products')
            ->join('brands', 'products.brand_id', '=', 'brands.id')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->join('units', 'products.unit_id', '=', 'units.id')
            ->select('products.*', 'brands.name as brand_name', 'categories.name as category_name', 'units.name as unit_name')
            ->where('products.deleted', 'No')
            ->where('products.status', 'Active')
            ->where('products.id', $ids[2])
            ->get();
      } elseif (!empty($ids[0]) && !empty($ids[1])) {
         // [categoryId & brandId] for Specific Category & Brand Productss
         $currentStocks = DB::table('products')
            ->join('brands', 'products.brand_id', '=', 'brands.id')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->join('units', 'products.unit_id', '=', 'units.id')
            ->select('products.*', 'brands.name as brand_name', 'categories.name as category_name', 'units.name as unit_name')
            ->where('products.deleted', 'No')
            ->where('products.status', 'Active')
            ->where('products.category_id', $ids[0])
            ->where('products.brand_id', $ids[1])
            ->get();
      } elseif (!empty($ids[0])) {
         // [categoryId] for Specific Category Products
         $currentStocks = DB::table('products')
            ->join('brands', 'products.brand_id', '=', 'brands.id')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->join('units', 'products.unit_id', '=', 'units.id')
            ->select('products.*', 'brands.name as brand_name', 'categories.name as category_name', 'units.name as unit_name')
            ->where('products.deleted', 'No')
            ->where('products.status', 'Active')
            ->where('products.category_id', $ids[0])
            ->get();
      }

      $userId  = auth()->user()->id;
      $userName = User::where('id', $userId)->pluck('name')->first();
      session(['userName' => $userName]);

      //return view('admin.inventory.report.product-current-stock-report', ['currentStocks'=> $currentStocks ]);
      $pdf = PDF::loadView('admin.inventory.report.product-current-stock-report',  ['currentStocks' => $currentStocks]);
      return $pdf->stream('product-report-pdf.pdf', array("Attachment" => false));
   }

  
   public function assetView()
   {
      $products = Product::where('deleted', 'No')->pluck('current_stock', 'purchase_price');
      $totalValueOfproduct = 0;
      foreach ($products as $purchasePrice => $currentStock) {
         $totalValueOfproduct += ($purchasePrice * $currentStock);
      }
      $partyPayable = Party::where('deleted', 'No')->where('current_due', '>', 0)->sum('current_due');
      $companyPayable = Party::where('deleted', 'No')->where('current_due', '<', 0)->sum('current_due');

      $netWorth = ($totalValueOfproduct + $partyPayable) + $companyPayable; // $companyPayable is a nagetive number 
      $companyAsset = [number_format($totalValueOfproduct, 2), number_format($partyPayable, 2), number_format($companyPayable, 2), number_format($netWorth, 2)];

      return view('admin.inventory.report.asset-ledger', ['companyAsset' => $companyAsset]);
   }






   public function warehouseWiseStock(Request $request){
        $productLedgers = DB::table("products")
                    ->join('tbl_currentstock', 'tbl_currentstock.tbl_productsId','=','products.id')
                    ->join('tbl_warehouse', 'tbl_currentstock.tbl_wareHouseId','=','tbl_warehouse.id')
                    ->where('tbl_currentstock.deleted','No')
                    ->where('tbl_currentstock.tbl_productsId',$request->ids)
                    ->select('products.name','products.code','tbl_currentstock.currentStock','tbl_warehouse.wareHouseName')
                    ->get();
        $info = '';
        $i = 1;
        $totalStock = 0;
        foreach ($productLedgers as $productLedger) {
            $info .= '<tr><td>' . ($i++) . '</td>' .
            '<td>' . $productLedger->name . ' - ' . $productLedger->code . '</td>' .
            '<td>' . $productLedger->wareHouseName . '</td>' .
            '<td>' . $productLedger->currentStock . '</td>' .
            '</tr>';
            $totalStock = intval($totalStock)+intval($productLedger->currentStock);
        }  
         $info .= '<tr><td></td>' .
            '<td colspan="2" class="text-right">Total: </td>' .
            '<td>' . $totalStock . '</td>' .
            '</tr>';
        return $info;
                    
   }
}
