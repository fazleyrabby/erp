<?php

namespace App\Models\Expense;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExpenseDetails extends Model
{
    use HasFactory;
    protected $table="tbl_acc_expense_details";
}
