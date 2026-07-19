<?php

namespace App\Models\payroll;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalaryInstruction extends Model
{
    use HasFactory;
    protected $guarded = [];

    protected $table = 'salary_instructions';
}
