<?php

namespace App\Models\payroll;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PayrollAttendence extends Model
{
    use HasFactory;

    protected $table = 'tbl_payroll_attendences';

    public function employee()
    {
        return $this->belongsTo(OurTeam::class, 'employee_id', 'id');
    }

    public function scopeForEmployee($query, $employeeId)
    {
        return $query->where('employee_id', $employeeId)
            ->where('deleted', 'No');
    }

    public function scopeForMonth($query, $year, $month)
    {
        return $query->whereYear('date', $year)
            ->whereMonth('date', $month);
    }

    public function scopeForDateRange($query, $from, $to)
    {
        return $query->where('date', '>=', $from)
            ->where('date', '<=', $to);
    }
}
