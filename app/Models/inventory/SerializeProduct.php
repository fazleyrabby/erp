<?php

namespace App\Models\inventory;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class SerializeProduct extends Model
{
    use HasFactory;
    protected $guarded = [];

    protected $table = 'tbl_serialize_products';

    public function product()
    {
        return $this->belongsTo(Product::class, 'id');
    }

    public static function SerializeProducts($productId, $purchaseId)
    {
        $SerializeProducts = DB::table('tbl_serialize_products')
            ->where('purchase_id', $purchaseId)
            ->where('tbl_productsId', $productId)
            ->select('serial_no', 'quantity')
            ->get();

        return $SerializeProducts;
    }
}
