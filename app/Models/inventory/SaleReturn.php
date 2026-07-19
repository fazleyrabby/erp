<?php

namespace App\Models\inventory;

use App\Models\inventory\Party;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SaleReturn extends Model
{
    use HasFactory;
    protected $guarded = [];

    protected $table = 'sale_returns';

    public function customer()
    {
        return $this->belongsTo(Party::class, 'customer_id', 'id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    public function getNameAttribute() { return $this->customer->name ?? ''; }
    public function getContactAttribute() { return $this->customer->contact ?? ''; }
    public function getAlternateContactAttribute() { return $this->customer->alternate_contact ?? ''; }
    public function getAddressAttribute() { return $this->customer->address ?? ''; }
    public function getUserNameAttribute() { return $this->creator->name ?? ''; }
    public function getSaleStatusAttribute() { return $this->status; }
}
