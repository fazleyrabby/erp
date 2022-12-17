<?php

namespace App\Models\inventory;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TemporarySale extends Model
{
    use HasFactory;

    protected $table = 'tbl_temporary_sale';
    public $timestamps = false;


}
