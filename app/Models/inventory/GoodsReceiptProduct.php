<?php

namespace App\Models\inventory;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GoodsReceiptProduct extends Model
{
    use HasFactory;

    protected $table = 'goods_receipt_products';
    protected $guarded = ['id'];

    public function goodsReceipt()
    {
        return $this->belongsTo(GoodsReceipt::class, 'goods_receipt_id', 'id');
    }

    public function purchaseOrderProduct()
    {
        return $this->belongsTo(PurchaseOrderProduct::class, 'purchase_order_product_id', 'id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }
}
