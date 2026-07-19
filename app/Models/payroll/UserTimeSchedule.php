<?php

namespace App\Models\payroll;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserTimeSchedule extends Model
{
    use HasFactory;
    protected $guarded = [];

    protected $table = 'tbl_payroll_user_time_schedules';
}
