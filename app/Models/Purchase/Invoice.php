<?php

namespace App\Models\Purchase;

use App\Models\Group;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function invoice_details()
    {
        return $this->hasMany(InvoiceDetail::class, 'invoice_id');
    }

    public function supplier()
    {
        return $this->belongsTo(\App\Models\Contact::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function account()
    {
        return $this->belongsTo(\App\Models\Account::class);
    }

    public function group()
    {
        return $this->belongsTo(Group::class);
    }
}
