<?php

namespace App\Models\inventory;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Currentstock extends Model
{
    use HasFactory;
    protected $table = 'tbl_currentstock';
    public $timestamps = false;
}
