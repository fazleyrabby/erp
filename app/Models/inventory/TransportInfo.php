<?php

namespace App\Models\inventory;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransportInfo extends Model
{
    use HasFactory;

    protected $table = 'tbl_transportinfo';

    public $timestamps = false;
}
