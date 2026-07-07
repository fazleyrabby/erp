<?php

namespace App\Models\inventory;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class SaleSerializeProduct extends Model
{
    use HasFactory;

    protected $table = 'sale_serialize_products';

    public static function saleSerializeProducts($productId, $saleId)
    {
        $saleSerializeProducts = DB::table('sale_serialize_products')
            ->join('tbl_serialize_products', 'sale_serialize_products.tbl_serialize_products_id', '=', 'tbl_serialize_products.id')
            ->where('sale_serialize_products.sale_id', $saleId)
            ->where('sale_serialize_products.product_id', $productId)
            ->select('sale_serialize_products.id', 'sale_serialize_products.sale_quantity', 'tbl_serialize_products.serial_no')
            ->get();

        return $saleSerializeProducts;
    }
}
