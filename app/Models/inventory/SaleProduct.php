<?php

namespace App\Models\inventory;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Unit;
use App\Models\Purchase;
use App\Models\Product;
class SaleProduct extends Model
{
    use HasFactory;
    protected $table = 'sale_products';
   
}
