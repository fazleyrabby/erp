<?php

namespace App\Models\inventory;

use App\Models\Party;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    use HasFactory;

    protected $table = 'purchases';

    public function supplier()
    {

        return $this->belongsTo(Party::class, 'party_id', 'id');
    }

    public function purchaseSerializeProducts()
    {
        // From tbl_serialize_products
        return $this->hasMany(SerializeProduct::class, 'purchase_id');
    }
}
