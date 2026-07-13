<?php

namespace App\Models\payroll;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeaveBalance extends Model
{
    use HasFactory;

    protected $table = 'employee_leave_balances';
    protected $guarded = ['id'];

    public function employee()
    {
        return $this->belongsTo(OurTeam::class, 'employee_id', 'id');
    }

    public function getRemainingDaysAttribute()
    {
        return $this->total_days - $this->used_days;
    }
}
