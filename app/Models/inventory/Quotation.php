<?php

namespace App\Models\inventory;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quotation extends Model
{
    use HasFactory;

    protected $table = 'quotations';
    protected $guarded = ['id'];

    public function customer()
    {
        return $this->belongsTo(Party::class, 'customer_id', 'id');
    }

    public function products()
    {
        return $this->hasMany(QuotationProduct::class, 'quotation_id', 'id');
    }

    public function creator()
    {
        return $this->belongsTo(\App\Models\User::class, 'created_by', 'id');
    }
}
