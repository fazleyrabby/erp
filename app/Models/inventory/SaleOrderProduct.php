<?php

namespace App\Models\inventory;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SaleOrderProduct extends Model
{
    use HasFactory;
    protected $guarded = [];

    protected $table = 'sale_order_products';
}
