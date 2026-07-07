<?php

namespace App\Models\inventory;

use App\Models\Party;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    use HasFactory;

    protected $table = 'sales';

    public function supplier()
    {

        return $this->belongsTo(Party::class, 'party_id', 'id');
    }

    public function saleSerializeProducts()
    {
        return $this->hasMany(SaleSerializeProduct::class, 'sale_id');
    }
}
