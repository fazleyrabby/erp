<?php

namespace App\Models\inventory;

use App\Models\Product;
use App\Models\Purchase;
use App\Models\Unit;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseProduct extends Model
{
    use HasFactory;
    protected $guarded = [];

    protected $table = 'purchase_products';

    public function purchases()
    {
        return $this->belongsTo(Purchase::class, 'purchase_id', 'id');
    }

    public function products()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }

    public function units()
    {
        return $this->belongsTo(Unit::class, 'unit_id', 'id');
    }
}
