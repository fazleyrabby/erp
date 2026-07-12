<?php

namespace App\Models\Accounts;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transactions extends Model
{
    use HasFactory;

    protected $table = 'tbl_acc_transactions';

    public function coaTo()
    {
        return $this->belongsTo(\App\Models\Accounts\ChartOfAccounts::class, 'tbl_coa_to_id', 'id');
    }

    public function coaFrom()
    {
        return $this->belongsTo(\App\Models\Accounts\ChartOfAccounts::class, 'tbl_coa_from_id', 'id');
    }
}
