<?php

namespace App\Models\Expense;

use App\Models\Accounts\ChartOfAccounts;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExpenseDetails extends Model
{
    use HasFactory;

    protected $table = 'tbl_acc_expense_details';

    public function expense()
    {
        return $this->belongsTo(Expense::class, 'tbl_acc_expense_id', 'id');
    }

    public function coa()
    {
        return $this->belongsTo(ChartOfAccounts::class, 'tbl_acc_coa_id', 'id');
    }
}
