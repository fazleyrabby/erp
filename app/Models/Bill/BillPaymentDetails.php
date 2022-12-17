<?php

namespace App\Models\Bill;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BillPaymentDetails extends Model
{
    use HasFactory;
    protected $table="tbl_acc_bill_payment_details";
}
