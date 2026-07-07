<?php

namespace App\Http\Controllers\Admin\Inventory;

use App\Http\Controllers\Controller;
use App\Models\inventory\Brand;
use App\Models\inventory\Category;
use App\Models\inventory\Product;
use App\Models\User;
use Carbon\Carbon;
use DNS1D;
use DNS2D;
use Illuminate\Http\Request;
use PDF;

class MakeBarcodeController extends Controller
{
    public function barcodeView()
    {
        $data['categories'] = Category::where('deleted', 'No')->where('status', 'Active')->get();
        $data['brands'] = Brand::where('deleted', 'No')->where('status', 'Active')->get();
        $data['products'] = Product::where('deleted', 'No')->where('status', 'Active')->get();

        return view('admin.inventory.products.barcode', $data);
    }

    public function getProduct(Request $request)
    {
        $product = Product::where('deleted', 'No')
            ->where('status', 'Active')
            ->where('id', $request->id)
            ->orderBy('created_at', 'DESC')
            ->get();

        $code = $product[0]['code'];
        $barcode = DNS1D::getBarcodeHTML($code, 'CODABAR', 2, 50, 'black', true);
        $product[0]['barcode'] = $barcode;
        $product[0]['code2'] = 'A'.$code.'A';

        return response()->json($product);
    }

    public function GenerateBarcode($ids = null)
    {
        // [categoryId & brandId] for Specific Category & Brand Product
        $ids = explode(',', $ids);
        $barcodeNo = $ids[0].''.$ids[1].''.$ids[2];
        $salePrice = $ids[3];
        $quantity = intval($ids[4]);

        $userId = auth()->user()->id;
        $userName = User::where('id', $userId)->pluck('name')->first();
        session(['userName' => $userName]);

        $product = Product::find($ids[2]);
        $productCode = $product->code;

        // $barcode = DNS1D::getBarcodeHTML($barcodeNo, 'PHARMA', 2, 40);
        // $barcode2 = DNS1D::getBarcodeHTML($code, 'UPCA');
        // $QRcode = DNS2D::getBarcodeHTML($barcodeNo, 'QRCODE');
        // return $QRcode;
        $barcode = DNS1D::getBarcodeHTML($productCode, 'CODABAR', 2, 40, true);
        // return $barcode;
        $barcodeNo = 'A'.$productCode.'A';

        $date = Carbon::now();
        $formatedDate = $date->format('Y-m-d');
        // $product = Product::find($ids[2]);
        /*$productQty = $quantity + $product->current_stock;
        $product->sale_price = $salePrice;
        $product->current_stock =  $productQty;
        $product->barcode_no = $barcodeNo;
        $product->barcode = $barcode;
        $product->updated_at = Carbon::now();
        $product->updated_date = $formatedDate;
        $product->updated_by = auth()->user()->id;
        $result = $product->save(); */

        // return view('admin.inventory.products.barcode-pdf-view', ['barcode'=>  $barcode, 'barcodeNo'=>$barcodeNo, 'quantity'=>$quantity]);
        // $pdf = PDF::loadView('admin.inventory.products.barcode-pdf-view',  ['barcode'=>  $barcode, 'barcodeNo'=>$barcodeNo, 'QRcode'=>$QRcode]);
        $pdf = PDF::loadView('admin.inventory.products.barcode-pdf-view', ['barcode' => $barcode, 'barcodeNo' => $barcodeNo, 'quantity' => $quantity]);

        return $pdf->stream('product-barcode-pdf.pdf', ['Attachment' => false]);

    }
}
