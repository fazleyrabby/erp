<?php

namespace App\Models\inventory;

use App\Models\inventory\Party;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Purchase_Return extends Model
{
    use HasFactory;

    protected $table = 'purchase_returns';

    public function supplier()
    {
        return $this->belongsTo(Party::class, 'supplier_id', 'id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    public function getNameAttribute() { return $this->supplier->name ?? ''; }
    public function getContactAttribute() { return $this->supplier->contact ?? ''; }
    public function getAlternateContactAttribute() { return $this->supplier->alternate_contact ?? ''; }
    public function getAddressAttribute() { return $this->supplier->address ?? ''; }
    public function getUserNameAttribute() { return $this->creator->name ?? ''; }
}
