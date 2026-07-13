<?php

namespace App\Models\inventory;

use App\Models\User;
use App\Models\inventory\Party;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected $table = 'invoices';
    protected $guarded = ['id'];

    public function party()
    {
        return $this->belongsTo(Party::class, 'party_id', 'id');
    }

    public function items()
    {
        return $this->hasMany(InvoiceItem::class, 'invoice_id', 'id');
    }

    public function payments()
    {
        return $this->hasMany(InvoicePayment::class, 'invoice_id', 'id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by', 'id');
    }

    public function getPartyNameAttribute()
    {
        return $this->party->name ?? 'N/A';
    }

    public function getPartyContactAttribute()
    {
        return $this->party->contact ?? 'N/A';
    }

    public function getPartyAddressAttribute()
    {
        return $this->party->address ?? 'N/A';
    }

    public function getDueAmountAttribute()
    {
        return $this->grand_total - $this->paid_amount;
    }

    public function getStatusBadgeAttribute()
    {
        $map = [
            'Draft' => 'bg-secondary',
            'Sent' => 'bg-info',
            'Paid' => 'bg-success',
            'Partial' => 'bg-warning',
            'Overdue' => 'bg-danger',
            'Cancelled' => 'bg-dark',
        ];
        return $map[$this->status] ?? 'bg-secondary';
    }
}
