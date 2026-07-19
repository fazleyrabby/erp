<?php

namespace App\Models\Accounts;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VoucherDetails extends Model
{
    use HasFactory;
    protected $guarded = [];

    protected $table = 'tbl_acc_voucher_details';
}
