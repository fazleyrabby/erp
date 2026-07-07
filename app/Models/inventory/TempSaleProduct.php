<?php

namespace App\Models\inventory;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TempSaleProduct extends Model
{
    use HasFactory;

    protected $table = 'tbl_tsalesproducts';

    public $timestamps = false;
}
