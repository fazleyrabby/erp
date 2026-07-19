<?php

namespace App\Models\Bill;

use App\Models\Accounts\ChartOfAccounts;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BillDetails extends Model
{
    use HasFactory;
    protected $guarded = [];

    protected $table = 'tbl_acc_bill_details';

    public function bill()
    {
        return $this->belongsTo(Bill::class, 'tbl_acc_bill_id', 'id');
    }

    public function coa()
    {
        return $this->belongsTo(ChartOfAccounts::class, 'tbl_acc_coa_id', 'id');
    }
}
