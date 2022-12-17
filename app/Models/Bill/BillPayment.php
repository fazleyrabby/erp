<?php

namespace App\Models\Bill;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BillPayment extends Model
{
    use HasFactory;
    protected $table="tbl_acc_bill_payments";
}
