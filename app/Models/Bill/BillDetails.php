<?php

namespace App\Models\Bill;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BillDetails extends Model
{
    use HasFactory;

    protected $table = 'tbl_acc_bill_details';

    public function bill()
    {
        return $this->belongsTo(\App\Models\Bill\Bill::class, 'tbl_acc_bill_id', 'id');
    }

    public function coa()
    {
        return $this->belongsTo(\App\Models\Accounts\ChartOfAccounts::class, 'tbl_acc_coa_id', 'id');
    }
}
