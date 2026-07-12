<?php

namespace App\Models\Expense;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    use HasFactory;

    protected $table = 'tbl_acc_expenses';

    public function vendor()
    {
        return $this->belongsTo(\App\Models\payroll\OurTeam::class, 'tbl_crm_vendor_id', 'id');
    }

    public function details()
    {
        return $this->hasMany(\App\Models\Expense\ExpenseDetails::class, 'tbl_acc_expense_id', 'id');
    }
}
