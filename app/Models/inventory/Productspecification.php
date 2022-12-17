<?php

namespace App\Models\inventory;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Productspecification extends Model
{
    use HasFactory;
    protected $table = 'tbl_productspecification';

    public $timestamps = false;
}
