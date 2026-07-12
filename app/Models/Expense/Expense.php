<?php

namespace App\Models\Expense;

use App\Models\payroll\OurTeam;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    use HasFactory;

    protected $table = 'tbl_acc_expenses';

    public function vendor()
    {
        return $this->belongsTo(OurTeam::class, 'tbl_crm_vendor_id', 'id');
    }

    public function details()
    {
        return $this->hasMany(ExpenseDetails::class, 'tbl_acc_expense_id', 'id');
    }
}
