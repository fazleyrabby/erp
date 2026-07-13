<?php

namespace App\Models\payroll;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Leave extends Model
{
    use HasFactory;

    protected $table = 'tbl_payroll_leaves';
    protected $guarded = ['id'];

    public function employee()
    {
        return $this->belongsTo(OurTeam::class, 'employee_id', 'id');
    }

    public function getDaysCountAttribute()
    {
        if ($this->leave_start_date && $this->leave_end_date) {
            $start = \Carbon\Carbon::parse($this->leave_start_date);
            $end = \Carbon\Carbon::parse($this->leave_end_date);
            return $start->diffInDays($end) + 1;
        }
        return 1;
    }

    public function getStatusBadgeAttribute()
    {
        return match ($this->leave_status) {
            'Approved' => 'bg-success',
            'Reject' => 'bg-danger',
            default => 'bg-warning',
        };
    }
}
