<?php

namespace App\Models\payroll;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SavedSalarySheet extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function salarySheet()
    {
        return $this->belongsTo(SalarySheet::class, 'sheet_id', 'id');
    }

    public function getSheetNameAttribute() { return $this->salarySheet->sheet_name ?? ''; }
    public function getSheetIDAttribute() { return $this->attributes['sheet_id'] ?? null; }
}
