<?php

namespace App\Http\Controllers\Admin\Inventory;

use App\Http\Controllers\Controller;
use App\Models\Accounts\ChartOfAccounts;
use Illuminate\Http\Request;

use App\Models\inventory\Sale;
use App\Models\inventory\SaleReturn;
use App\Models\inventory\Party;
use App\Models\inventory\SaleProduct;
use App\Models\inventory\Product;
use App\Models\inventory\SaleProductReturn;
use Illuminate\Support\Facades\Auth;
use App\Models\inventory\PaymentVoucher;
use App\Models\inventory\TemporarySale;
use App\Models\inventory\TempSaleProduct;
use App\Models\inventory\Warehouse;
use App\Models\inventory\Currentstock;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use PDF;
use Carbon\Carbon;

class SaleReturnController extends Controller
{
	public function saleReturn($id)
	{
		$sale = Sale::where(['deleted' => 'No', 'id' => $id])->get()->first();
		$customer = Party::where('deleted', 'No')->where('status', 'Active')->where('id', $sale->customer_id)->first();
		$sale['customer_name'] = $customer->name;
		$sale['customer_id'] = $customer->id;
		$saleProducts = DB::table('sale_products')
			->join('products', 'products.id', '=', 'sale_products.product_id')
			->join('tbl_warehouse', 'sale_products.warehouse_id', 'tbl_warehouse.id')
			->select('sale_products.*', 'products.name', 'tbl_warehouse.wareHouseName')
			->where('sale_products.sale_id', $sale->id)
			->where('sale_products.deleted', 'No')
			->where('sale_products.status', 'Active')
			->get();

		$returnedQtyArray = [];
		$i = 0;
		foreach ($saleProducts as $saleProduct) {
			$returnedQtyArray[$i] = SaleProductReturn::where('sale_product_id', $saleProduct->id)
				->where('warehouse_id', $saleProduct->warehouse_id)
				->where('deleted', 'No')
				->sum('return_qty');
			$i++;
		}
		$warehouses = Warehouse::where('deleted', 'No')->where('status', 'Active')->get();
		return view('admin.inventory.sale.sale-return', ['sale' => $sale, 'saleProducts' => $saleProducts, 'returnedQtyArray' => $returnedQtyArray, 'warehouses' => $warehouses]);
	}

	public function saleReturnList($type)
	{
		$saleType = $type;
		return view('admin.inventory.sale.sale-returnList', compact('saleType'));
	}

	public function saveSaleReturn(Request $request)
	{
		try {
			$request->validate([
				'warehouse' => 'required',
			]);

			$saleReturnNo = SaleReturn::max('sale_return_no');
			$saleReturnNo++;
			$saleReturnNo = str_pad($saleReturnNo, 6, '0', STR_PAD_LEFT);

			$purchaseReturn_id = 0;
			$saleId = $request->saleId;
			$sales = Sale::find($saleId);
			$saleProductIds = $request->saleProductIds;
			$saleProductIdsArray = explode(",", $saleProductIds);
			$itemCodesArray = explode(",", $request->itemCodes);
			$QuantitiesArray = explode(",", $request->Quantities);
			$returnedQuantitiesArray = explode(",", $request->returnedQuantities);
			$returnQuantitiesArray = explode(",", $request->returnQuantities);
			$remainQuantitiesArray = explode(",", $request->remainQuantities);
			$unitPricesArray = explode(",", $request->unitPrices);
			$productIdsOfSaleArray = explode(",", $request->productIdsOfSale);
			$totalsArray = explode(",", $request->totals);
			$warehouse_id = $request->warehouse;
			// store in sale_returns //
			$saleReturn = new SaleReturn();
			$saleReturn->sale_return_no = $saleReturnNo;
			$saleReturn->sale_no = $request->saleNo;
			$saleReturn->coa_id = 44; // tbl_acc_coas [sales-ruturn]
			$saleReturn->sale_return_date = Carbon::now();
			$saleReturn->sale_date = $request->saleDate;
			$saleReturn->sale_id = $saleId;
			$saleReturn->customer_id = $request->customerId;
			$saleReturn->grand_total = $request->grandTotal;
			$saleReturn->status = "Active";
			$saleReturn->sales_type = $sales->sales_type;
			$saleReturn->created_by =  Auth::user()->id;
			$saleReturn->save();

			$saleReturnId = $saleReturn->id;
			for ($i = 0; $i < count($saleProductIdsArray); $i++) {

				if ($returnQuantitiesArray[$i] <= 0 || $returnQuantitiesArray[$i] == "NaN") {
					continue;
				}

				$saleProduct = SaleProduct::where([['id', '=', $saleProductIdsArray[$i]], ['deleted', '=', 'No'], ['status', '=', 'Active']])->first();

				$saleReturnProduct = new SaleProductReturn();
				$saleReturnProduct->sale_product_id = $saleProductIdsArray[$i];
				$saleReturnProduct->sale_return_id = $saleReturnId;
				$saleReturnProduct->warehouse_id = $warehouse_id;
				$saleReturnProduct->product_id = $saleProduct->product_id;
				$saleReturnProduct->return_qty = $returnQuantitiesArray[$i];
				$saleReturnProduct->remaining_qty = $remainQuantitiesArray[$i];
				$saleReturnProduct->unit_price =  $unitPricesArray[$i];
				$saleReturnProduct->total_price = $totalsArray[$i];
				$saleReturnProduct->created_by = Auth::user()->id;
				$saleReturnProduct->created_date = Carbon::now();
				$saleReturnProduct->deleted = "No";
				$saleReturnProduct->status = "Active";
				$saleReturnProduct->sales_type = $sales->sales_type;
				$saleReturnProduct->save();

				// update (current_stock)  in products table //
				$productId = intval($itemCodesArray[$i]);
				$quantity = intval($returnQuantitiesArray[$i]);
				$product = Product::find($productId);
				$product->increment('current_stock', $quantity);

				$Currentstock = Currentstock::where("tbl_productsId", $productId)
					->where("tbl_wareHouseId", $warehouse_id)
					->where("deleted", 'No');
				if ($Currentstock->first()) {
					$Currentstock->increment('currentStock', $quantity);
					$Currentstock->increment('salesReturnStock', $quantity);
				} else {
					$Currentstock_insert = new Currentstock();
					$Currentstock_insert->tbl_productsId = $productId;
					$Currentstock_insert->tbl_wareHouseId = $warehouse_id;
					$Currentstock_insert->currentStock = $quantity;
					$Currentstock_insert->salesReturnStock = $quantity;
					$Currentstock_insert->entryBy = auth()->user()->id;
					$Currentstock_insert->entryDate = date('Y-m-d H:i:s');
					$Currentstock_insert->save();
				}
			}

			$party = Party::find($request->customerId);
			$current_due = $party->current_due - $request->grandTotal;
			$party->current_due =  	$current_due;
			$party->save();

			// accounts part Start
			$cashId = ChartOfAccounts::where('slug', 'sales-ruturn')->first();
			$cash = ChartOfAccounts::find($cashId->id);
			$cash->increment('amount', $request->grandTotal);
			// accounts part End

			/* commemted- 22-05-2022
			$PaymentVoucherNo = PaymentVoucher::max('voucherNo');
			$PaymentVoucherNo++;
			$PaymentVoucherNo = str_pad($PaymentVoucherNo, 3, '0', STR_PAD_LEFT);

			$partyId = $party->id;
			$paymentVoucher = new PaymentVoucher();
			$paymentVoucher->party_id  = $partyId ;
			$paymentVoucher->amount =  $request->grandTotal;
			$paymentVoucher->entryBy = Auth::user()->id ;
			$paymentVoucher->paymentDate =  Carbon::now();
			$paymentVoucher->status = "Active";
			$paymentVoucher->payment_method = "Cash";
			$paymentVoucher->type = "Party Payable";
			$paymentVoucher->customerType = "Party";
			$paymentVoucher->voucherType = "SalesReturn";
			$paymentVoucher->sales_return_id =  $saleReturnId ;
			$paymentVoucher->voucherNo  = $PaymentVoucherNo;
			$paymentVoucher->remarks = "Voucher Entry for Sale Return";
			$paymentVoucher->save();
			*/

			return response()->json(['success' => 'Sale returned successfully', 'type' => $sales->sales_type]);
		} catch (Exception $e) {
			DB::rollBack();
			return response()->json(['error' => 'Sale delete rollBack ' . $e]);
		}
	}

	public function saleReturnView($type)
	{

		$output = array('data' => array());
		$i = 1;

		$saleReturns = DB::table('sale_returns')
			->join('parties', 'sale_returns.customer_id', '=', 'parties.id')
			->leftjoin('users', 'users.id', '=', 'sale_returns.created_by')
			->select(
				'sale_returns.sale_return_no',
				'sale_returns.sale_no',
				'sale_returns.sale_return_date',
				'sale_returns.grand_total',
				'sale_returns.discount',
				'sale_returns.id',
				'sale_returns.sale_id',
				'sale_returns.sale_date',
				'sale_returns.grand_total',
				'sale_returns.status as saleStatus',
				'parties.name',
				'parties.code',
				'parties.address',
				'parties.contact',
				'parties.alternate_contact',
				'users.name as userName'
			)
			->where('sale_returns.deleted', 'No')
			->where('sale_returns.sales_type', $type)
			->orderBy('sale_returns.id', 'DESC')
			->get();

		foreach ($saleReturns as $saleReturn) {
			// $button = '<button type="button" title="print sale" id="delete" class="btn btn-sm btn-success printPurchase" onclick="printPurchase('.$saleReturn->id.')" title="Print sale"><i class="fa fa-print"> </i></button> <button type="button" title="Delete" id="delete" class="btn btn-sm btn-danger btnDelete" onclick="confirmDelete('.$saleReturn->id.')" title="Delete Record"><i class="fa fa-trash"> </i></button>';
			$button = '<td style="width: 12%;">
			   <div class="btn-group">
				   <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
					   <i class="fas fa-cog"></i>  <span class="caret"></span></button>
					   <ul class="dropdown-menu dropdown-menu-right" style="border: 1px solid gray;" role="menu">
					   <li class="action" onclick="printPurchase(' . $saleReturn->id . ')" ><a  class="btn" ><i class="fas fa-print"></i> View Details  </a></li>
					   </li>
				   </li> 
					   <li class="action"><a   class="btn"  onclick="confirmDelete(' . $saleReturn->id . ')" ><i class="fas fa-trash-alt"></i> Delete </a></li>
					   </li>
					   </ul>
				   </div>
			   </td>';
			$badgeColor = '';
			if ($saleReturn->saleStatus == 'Active') {
				$badgeColor = 'success';
			} else {
				$badgeColor = 'danger';
			}
			$output['data'][] = array(
				$i++ . '<input type="hidden" name="id" id="id" value="' . $saleReturn->id . '" />',
				'<b>Return No: </b>' . $saleReturn->sale_return_no . ' <br><b>Return Date: </b>' . $saleReturn->sale_return_date,
				'<b>Sale No: </b>' . $saleReturn->sale_no . '<br> <b>Sale Date:</b>' . $saleReturn->sale_date,
				'<b>Party: </b>' . $saleReturn->name . '<br><b>Contact: </b>' . $saleReturn->contact . '<br><b>Alt. Contact: </b>' . $saleReturn->alternate_contact . '<br><b>Address: </b>' . substr(str_pad($saleReturn->address, 4), 0, 25),
				'<b>Grand Total : </b>' . $saleReturn->grand_total,
				$saleReturn->userName,
				'<span class="badge badge-pill badge-' . $badgeColor . ' text-center">' . $saleReturn->saleStatus . '</span>',
				$button
			);
		}
		return $output;
	}

	public function deleteSaleReturn(Request $request)
	{
		DB::beginTransaction();
		try {
			$saleReturn = SaleReturn::find($request->id);
			$saleReturn->deleted = 'Yes';
			$saleReturn->deleted_date = date('Y-m-d H:i:s');
			$saleReturn->created_by = Auth::user()->id;
			$saleReturn->save();

			$party = Party::find($saleReturn->customer_id);
			$party->current_due = ($party->current_due + $saleReturn->grand_total);
			$party->save();

			$sale_products = SaleProductReturn::where("sale_return_id", $request->id)->get();
			foreach ($sale_products as $sale_product) {
				$saleProduct = SaleProductReturn::find($sale_product->id);
				$saleProduct->deleted = 'Yes';
				$saleProduct->deleted_date = date('Y-m-d H:i:s');
				$saleProduct->created_by = Auth::user()->id;
				$saleProduct->save();

				$product = Product::find($saleProduct->product_id);
				$quantity = intval($saleProduct->return_qty);
				$unit_price = floatval($product->unit_price);
				$product->decrement('sale_quantity', $quantity);
				$product->decrement('current_stock', $quantity);
				$subtotal = floatval($unit_price * $quantity);
				$product->decrement('total_sale_price', $subtotal);

				$productId = $product->id;
				$warehouse_id = $saleProduct->warehouse_id;
				$Currentstock = Currentstock::where("tbl_productsId", $productId)
					->where("tbl_wareHouseId", $warehouse_id)
					->where("deleted", 'No');
				if ($Currentstock->first()) {
					$Currentstock->decrement('currentStock', $quantity);
					$Currentstock->increment('salesReturnDelete', $quantity);
				} else {
					$Currentstock_insert = new Currentstock();
					$Currentstock_insert->tbl_productsId = $productId;
					$Currentstock_insert->tbl_wareHouseId = $warehouse_id;
					$Currentstock_insert->currentStock = -$quantity;
					$Currentstock_insert->salesReturnDelete = $quantity;
					$Currentstock_insert->entryBy = auth()->user()->id;
					$Currentstock_insert->entryDate = date('Y-m-d H:i:s');
					$Currentstock_insert->save();
				}
			}
			DB::commit();
			return response()->json(['Success' => 'Sale Return deleted!']);
		} catch (Exception $e) {
			DB::rollBack();
			return response()->json(['error' => 'Sale Return delete rollBack ' . $e]);
		}
	}

	public function createPDF($id)
	{
		$invoice = DB::table('sale_returns')
			->join('sale_product_returns', 'sale_returns.id', '=', 'sale_product_returns.sale_return_id')
			->join('products', 'sale_product_returns.product_id', '=', 'products.id')
			->join('parties', 'sale_returns.customer_id', '=', 'parties.id')
			->join('users', 'sale_returns.created_by', '=', 'users.id')
			->where([['sale_returns.id', '=', $id], ['sale_returns.deleted', '=', 'No']])
			->selectRaw('sum(sale_product_returns.return_qty) as return_qty,products.name,products.code as productCode,parties.name as customer_name,parties.code,parties.contact,
					            parties.address,sale_returns.sale_return_date,sale_returns.sale_return_no,sale_returns.sale_no,sale_returns.sale_date,sale_returns.grand_total,sale_product_returns.unit_price, sum(sale_product_returns.total_price) as total_price,
					            sale_product_returns.sales_type, users.name as entryBy')
			->groupby(
				'products.name',
				'products.code',
				'parties.name',
				'parties.code',
				'parties.contact',
				'parties.address',
				'sale_returns.sale_return_date',
				'sale_returns.sale_return_no',
				'sale_returns.sale_date',
				'sale_returns.sale_no',
				'sale_returns.grand_total',
				'sale_product_returns.unit_price',
				'sale_product_returns.sales_type',
				'users.name'
			)
			->get();

		$userId  = auth()->user()->id;
		$userName = User::where('id', $userId)->pluck('name')->first();
		session(['userName' => $userName]);

		//return view('admin.inventory.sale.return-report', ['invoice'=> $invoice]);
		$pdf = PDF::loadView('admin.inventory.sale.return-report',  ['invoice' => $invoice]);
		return $pdf->stream('sale-return-report-pdf.pdf', array("Attachment" => false));
	}

	public function temporarySaleAdjustment()
	{
		//$customers = Party::where('deleted', 'No')->where('status', 'Active')->where('party_type', 'Customer')->get();
		$customers = Party::where('deleted', 'No')->where('status', 'Active')->whereIn('party_type', ['Customer', 'Both'])->get();
		$warehouses = Warehouse::where('deleted', 'No')->get();
		return view('admin.inventory.sale.temporarySaleAdjustment', compact('customers', 'warehouses'));
	}

	public function getTemporarySale(Request $request)
	{

		$id = $request->id;
		//$temporarySale = TemporarySale::where(['deleted' => 'No', 'tbl_customerId' => $request->id])->get();
		$temporarySaleProducts = DB::table('tbl_temporary_sale')
			->join('tbl_tsalesproducts', 'tbl_tsalesproducts.tbl_tSalesId', '=', 'tbl_temporary_sale.id')
			->join('products', 'products.id', '=', 'tbl_tsalesproducts.tbl_productsId')
			->select('tbl_tsalesproducts.*', 'products.name', 'products.code', 'products.sale_price', 'products.id as productId')
			->where('tbl_temporary_sale.tbl_customerId', $id)
			->where('tbl_tsalesproducts.deleted', 'No')
			->where('tbl_tsalesproducts.status', 'Running')
			->get();


		$temporarySaleTable = '';
		$sr = 0;
		foreach ($temporarySaleProducts as $tempSale) {
			$soldQuantity = $tempSale->soldQuantity;

			$quantity = $tempSale->quantity;
			$soldQuantity = $tempSale->soldQuantity;
			$returnedQuantity = $tempSale->returnedQuantity;
			if (($soldQuantity + $returnedQuantity) == $quantity) {
				continue;
			}

			if ($soldQuantity == "") {
				$soldQuantity = 0;
			}
			if ($returnedQuantity == "") {
				$returnedQuantity = 0;
			}
			$remainingQty = $tempSale->quantity - ($soldQuantity + $returnedQuantity);

			$temporarySaleTable .= '<tr>
				<input type="hidden" id="totalQty' . $tempSale->id . '" value="' . $tempSale->quantity . '">
				<input type="hidden" id="soldQty' . $tempSale->id . '" value="' . $soldQuantity . '">
				<input type="hidden" id="returnedQty' . $tempSale->id . '" value="' . $returnedQuantity . '">
				<th scope="row" id="">' . (++$sr) . '</th>
				<td>' . $tempSale->name . '-' . $tempSale->code . '</td>
				<td>' . $tempSale->quantity . '</td>
				<td>' . $soldQuantity . '</td>
				<td id="returnedQty_' . $tempSale->id . '">' . $returnedQuantity . '</td>
				<td>
					<input type="number" id="sale_' . $tempSale->id . '" oninput="sale(' . $tempSale->id . ')" min="0" placeholder="0" class="form-control" name="sale[]" aria-describedby="emailHelp">
				</td>
				<td>
				    <input type="text"  id="unitPrice' . $tempSale->id . '"  oninput="sale(' . $tempSale->id . ')" class="form-control" name="unitPrice[]" value="' . $tempSale->sale_price . '" />
			    </td>
				<td>
					<input type="number" id="temRetrun_' . $tempSale->id . '" oninput="temRetrun(' . $tempSale->id . ')" min="0" placeholder="0" class="form-control" name="temRetrun[]" aria-describedby="emailHelp">
				</td>
				<td id="remainingQty_' . $tempSale->id . '">' . $remainingQty . '</td>
				<input type="hidden" id="saleProductId' . $tempSale->id . '" value="' . $tempSale->id . '">
				<td id="total_' . $tempSale->id . '">00</td>
			  </tr>';
		}
		return $temporarySaleTable;
	}

	public function saveTSAdjustment(Request $request)
	{
		$date = $request->date;
		$customer = $request->customer;
		$warehouse = $request->warehouse;
		$requisitionNo = $request->requisitionNo;
		$remarks = $request->remarks;
		$totalPrice = $request->totalPrice;
		$discount = $request->discount;
		$grandTotal = $request->grandTotal;
		$paidAmount = $request->paidAmount;
		$saleQuantity = $request->saleQuantity;
		$saleQuantityArray = explode(",", $saleQuantity);
		$TSproductsId = $request->TSproductsId;
		$TSproductsIdArray = explode(",", $TSproductsId);
		/*$saleQuantity = $request->saleQuantity;
	    $saleQuantityArray = explode(",",$saleQuantity);*/
		$unitPrice = $request->unitPrice;
		$unitPriceArray = explode(",", $unitPrice);
		$productTotal = $request->productTotal;
		$productTotalArray = explode(",", $productTotal);
		$TSReturnproductsId = $request->TSReturnproductsId;
		$TSReturnproductsIdArray = explode(",", $TSReturnproductsId);
		$returnQutantity = $request->returnQutantity;
		$returnQutantityArray = explode(",", $returnQutantity);
		DB::beginTransaction();
		try {
			$customerInfo = Party::where('id', $customer)->where('party_type', 'Customer')->get()->first();
			$previousDue = $customerInfo->current_due;
			$currentBalance = floatval($previousDue) + floatval($grandTotal) - floatval($paidAmount);
			$saleType = 'FS';
			//Sales Data
			if ($TSproductsIdArray[0] != '') {
				$saleNo = Sale::where('sales_type', $saleType)->max('sale_no');
				$saleNo++;
				$saleNo = str_pad($saleNo, 6, '0', STR_PAD_LEFT);
				$sale = new Sale();
				$sale->customer_id = $customer;
				$sale->sale_no = $saleNo;
				$sale->date = $date;
				$sale->total_amount = floatval($totalPrice);
				$sale->discount = floatval($discount);
				$sale->carrying_cost = 0;
				$sale->grand_total = floatval($grandTotal);
				$sale->previous_due = floatval($previousDue);
				$sale->current_balance = $currentBalance;
				$sale->total_price = floatval($grandTotal);
				$sale->total_with_due = floatval($previousDue) + floatval($grandTotal);
				$sale->current_payment = floatval($paidAmount);
				$sale->dues_amount = $currentBalance;
				$sale->created_by = auth()->user()->id;
				$sale->created_date = Carbon::now();
				$sale->sales_type = $saleType;
				$sale->current_dues = $currentBalance;
				if (intval($request->noOfTenure) > 0) {
					$sale->emi_status = 'Yes';
				}
				$sale->save();

				$sale_id = $sale->id;
				for ($i = 0; $i < count($TSproductsIdArray); $i++) {
					$TSproductsIdEntry = $TSproductsIdArray[$i];
					$productQuantityEntry = $saleQuantityArray[$i];
					$productPriceEntry = $unitPriceArray[$i];
					$productDiscountEntry = 0;
					$productTotalEntry = $productTotalArray[$i];
					if ($TSproductsIdEntry != '') {
						$total = $productQuantityEntry * $productPriceEntry;
						if (substr($productDiscountEntry, -1) == '%') {
							$discountAmount = $total * (substr($productDiscountEntry, 0, -1) / 100);
						} else {
							$discountAmount = $productDiscountEntry;
						}

						$TSData = TempSaleProduct::find($TSproductsIdEntry);
						$product_id = $TSData->tbl_productsId;
						$warehouse_id = $warehouse;
						$product = Product::find($product_id);
						$unit_id = $product->unit_id;
						$unit_price = floatval($productPriceEntry);
						$discount_amount = floatval($productDiscountEntry);
						$quantity = $productQuantityEntry;
						$salePrice = floatval($unit_price - $discount_amount);
						$subtotal = floatval($salePrice * $quantity);
						$product->increment('total_sale_price', $subtotal);
						$lot_no = SaleProduct::where("product_id", $product_id)->max('lot_no');
						$lot_no++;
						$sale_products = new SaleProduct();
						$sale_products->sale_id = $sale_id;
						$sale_products->product_id = $product_id;
						$sale_products->warehouse_id = $warehouse_id;
						$sale_products->unit_id = $unit_id;
						$sale_products->unit_price = $unit_price;
						$sale_products->unit_discount = $discount_amount;
						$sale_products->sale_price = $salePrice;
						$sale_products->quantity = $quantity;
						$sale_products->lot_no = $lot_no;
						$sale_products->subtotal = $subtotal;
						$sale_products->created_by = auth()->user()->id;
						$sale_products->created_date = Carbon::now();
						$sale_products->save();

						//$TSData->increment('soldQuantity', $quantity);
						$totalSoldQuantity = $TSData->soldQuantity + $quantity;
						$TSData->soldQuantity = $totalSoldQuantity;
						$totalTempQuantity =  $totalSoldQuantity + $TSData->returnedQuantity;
						if ($totalTempQuantity == $TSData->quantity) {
							$TSData->status = 'Adjusted';
						}
						$TSData->save();
					}

					if (floatval($grandTotal) > 0) {
						$party = Party::find($customer);
						$party->current_due = $currentBalance;
						$party->save();
						$maxCode = PaymentVoucher::max(DB::raw('cast(voucherNo AS decimal(6))'));
						//$maxCode = PaymentVoucher::max('voucherNo');
						$maxCode++;
						$maxCode = str_pad($maxCode, 6, '000000', STR_PAD_LEFT);;
						$paymentVoucher = new PaymentVoucher();
						$paymentVoucher->party_id = $customer;
						$paymentVoucher->voucherNo = $maxCode;
						$paymentVoucher->sales_id = $sale_id;
						$paymentVoucher->amount = floatval($grandTotal);
						$paymentVoucher->payment_method = 'Cash';
						$paymentVoucher->paymentDate  = $date;
						$paymentVoucher->type  = 'Party Payable';
						$paymentVoucher->voucherType  = $saleType;
						$paymentVoucher->remarks  = 'Party Payable for FS code: ' . $saleNo . ' payment: ' . $grandTotal;
						$paymentVoucher->entryBy  = auth()->user()->id;
						$paymentVoucher->save();
						if (floatval($request->current_payment) > 0) {
							$maxCode = PaymentVoucher::max(DB::raw('cast(voucherNo AS decimal(6))'));
							$maxCode++;
							$maxCode = str_pad($maxCode, 6, '0', STR_PAD_LEFT);;
							$paymentVoucher = new PaymentVoucher();
							$paymentVoucher->party_id = $customer;
							$paymentVoucher->voucherNo = $maxCode;
							$paymentVoucher->sales_id = $sale_id;
							$paymentVoucher->amount = floatval($paidAmount);
							$paymentVoucher->payment_method = 'Cash';
							$paymentVoucher->paymentDate  = $date;
							$paymentVoucher->type  = 'Payment Received';
							$paymentVoucher->voucherType  = $saleType;
							$paymentVoucher->remarks  = 'Party payment for FS code: ' . $saleNo . ' payment: ' . $paidAmount;
							$paymentVoucher->entryBy  = auth()->user()->id;
							$paymentVoucher->save();
						}
					}
				}
			}

			//Return Data
			if ($TSReturnproductsIdArray[0] != '') {
				$saleType = 'TS';
				$saleReturnNo = SaleReturn::where('sales_type', $saleType)->max('sale_return_no');
				$saleReturnNo++;
				$saleReturnNo = str_pad($saleReturnNo, 6, '0', STR_PAD_LEFT);

				// store in sale_returns //
				$saleReturn = new SaleReturn();
				$saleReturn->sale_return_no = $saleReturnNo;
				$saleReturn->sale_id = 1;
				$saleReturn->sale_no = '';
				$saleReturn->sale_return_date = Carbon::now();
				$saleReturn->sale_date = $date;
				//$saleReturn->sale_id = $saleId;
				$saleReturn->customer_id = $customer;
				//$saleReturn->grand_total = $request->grandTotal;
				$saleReturn->status = "Active";
				$saleReturn->sales_type = $saleType;
				$saleReturn->created_by =  Auth::user()->id;
				$saleReturn->save();

				$saleReturnId = $saleReturn->id;
				for ($i = 0; $i < count($TSReturnproductsIdArray); $i++) {

					if ($returnQutantityArray[$i] > 0) {
						$TSReturnproductsIdEntry = $TSReturnproductsIdArray[$i];
						$returnQutantityEntry = $returnQutantityArray[$i];
						$TSReturnData = TempSaleProduct::find($TSReturnproductsIdEntry);
						$product_id = $TSReturnData->tbl_productsId;
						// store in sale_product_returns //
						$saleReturnProduct = new SaleProductReturn();
						$saleReturnProduct->sale_product_id = $TSReturnproductsIdEntry;
						$saleReturnProduct->sale_return_id = $saleReturnId;
						$saleReturnProduct->warehouse_id = $warehouse;
						$saleReturnProduct->product_id = $product_id;
						$saleReturnProduct->return_qty = $returnQutantityEntry;
						$saleReturnProduct->created_by = Auth::user()->id;
						$saleReturnProduct->created_date = Carbon::now();
						$saleReturnProduct->deleted = "No";
						$saleReturnProduct->status = "Active";
						$saleReturnProduct->sales_type = $saleType;
						$saleReturnProduct->save();


						// update (current_stock)  in products table //
						$quantity = intval($returnQutantityEntry);
						$product = Product::find($product_id);
						$product->increment('current_stock', $quantity);

						//$TSReturnData->increment('returnedQuantity', $quantity);
						$totalReturnedQuantity = $TSReturnData->returnedQuantity + $quantity;
						$totalTempQuantity = $totalReturnedQuantity + $TSReturnData->soldQuantity;
						$TSReturnData->returnedQuantity = $totalReturnedQuantity;
						if ($totalTempQuantity == $TSReturnData->quantity) {
							$TSReturnData->status = 'Adjusted';
						}
						$TSReturnData->save();

						$Currentstock = Currentstock::where("tbl_productsId", $product_id)
							->where("tbl_wareHouseId", $warehouse)
							->where("deleted", 'No');
						if ($Currentstock->first()) {
							$Currentstock->increment('currentStock', $quantity);
							$Currentstock->increment('salesReturnStock', $quantity);
						} else {
							$Currentstock_insert = new Currentstock();
							$Currentstock_insert->tbl_productsId = $product_id;
							$Currentstock_insert->tbl_wareHouseId = $warehouse;
							$Currentstock_insert->currentStock = $quantity;
							$Currentstock_insert->salesReturnStock = $quantity;
							$Currentstock_insert->entryBy = auth()->user()->id;
							$Currentstock_insert->entryDate = date('Y-m-d H:i:s');
							$Currentstock_insert->save();
						}
					}
				}
			}

			$TSZeroData = TempSaleProduct::where('quantity', ('soldQuantity + returnedQuantity'))->where('status', 'Running')->where('deleted', 'No')->get();
			foreach ($TSZeroData as $TSZero) {
				$tSale = TempSaleProduct::find($TSZero->id);
				$tSale->status = 'Adjusted';
				$tSale->save();
			}


			DB::commit();
			return response()->json(['Success' => 'Successfully Adjusted!']);
		} catch (Exception $e) {
			DB::rollBack();
			return response()->json(['error' => 'TS Adjustment Rolled back ' . $e]);
		}
	}
}
