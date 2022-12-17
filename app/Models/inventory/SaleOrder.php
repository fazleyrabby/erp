<?php

namespace App\Models\inventory;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SaleOrder extends Model
{
    use HasFactory;

    protected $table = 'sale_orders';

    public function saleOrderFeedbacks()
    {
        return $this->hasMany(SaleOrderFeedback::class, 'tbl_sale_orders_id');
    }

    
    public function saleOrderProducts()
    {
        return $this->hasMany(SaleOrderProduct::class, 'tbl_sale_orders_id');
    }

}
