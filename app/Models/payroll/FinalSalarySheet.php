<?php

namespace App\Models\payroll;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FinalSalarySheet extends Model
{
    use HasFactory;
    protected $guarded = [];

    protected $primaryKey = 'saved_sheet_id';
}
