<?php

namespace App\Models\Bill;

use App\Models\Crm\Party;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bill extends Model
{
    use HasFactory;
    protected $guarded = [];

    protected $table = 'tbl_acc_bills';

    public function vendor()
    {
        return $this->belongsTo(Party::class, 'tbl_crm_vendor_id', 'id');
    }

    public function details()
    {
        return $this->hasMany(BillDetails::class, 'tbl_acc_bill_id', 'id');
    }
}
