<?php

namespace App\Models\Journal;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JournalDetails extends Model
{
    use HasFactory;
    protected $table="tbl_acc_jornal_details";
    protected $fillable = ['tbl_acc_coa_id','particular','debit','credit'];
}
