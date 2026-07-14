<?php

namespace App\Models\inventory;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GoodsReceipt extends Model
{
    use HasFactory;

    protected $table = 'goods_receipts';
    protected $guarded = ['id'];

    public function purchaseOrder()
    {
        return $this->belongsTo(PurchaseOrder::class, 'purchase_order_id', 'id');
    }

    public function supplier()
    {
        return $this->belongsTo(Party::class, 'supplier_id', 'id');
    }

    public function products()
    {
        return $this->hasMany(GoodsReceiptProduct::class, 'goods_receipt_id', 'id');
    }

    public function creator()
    {
        return $this->belongsTo(\App\Models\User::class, 'created_by', 'id');
    }
}
