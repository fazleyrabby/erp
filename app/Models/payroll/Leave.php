<?php

namespace App\Models\PayRoll;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Leave extends Model
{
    use HasFactory;
    protected $table="tbl_payroll_leaves";
}
