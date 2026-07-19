<?php

namespace App\Models\inventory;

use App\Models\inventory\Party;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TemporarySale extends Model
{
    use HasFactory;
    protected $guarded = [];

    protected $table = 'tbl_temporary_sale';

    public $timestamps = false;

    public function customer()
    {
        return $this->belongsTo(Party::class, 'tbl_customerId', 'id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'tbl_userId', 'id');
    }

    public function getNameAttribute() { return $this->customer->name ?? ''; }
    public function getContactAttribute() { return $this->customer->contact ?? ''; }
    public function getAlternateContactAttribute() { return $this->customer->alternate_contact ?? ''; }
    public function getAddressAttribute() { return $this->customer->address ?? ''; }
    public function getUserNameAttribute() { return $this->creator->name ?? ''; }
    public function getDateAttribute() { return $this->tSalesDate; }
}
