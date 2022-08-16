<?php

namespace App\Models\Sale;

use App\Models\Account;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoiceSale extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function invoice_details()
    {
        return $this->hasMany(InvoiceSaleDetail::class, 'invoice_id');
    }

    public function customer()
    {
        return $this->belongsTo(\App\Models\Contact::class);
    }

    public function order()
    {
        return $this->belongsTo(OrderSale::class, 'order_id');
    }

    public function account()
    {
        return $this->belongsTo(Account::class, 'account_id');
    }

    public function group() 
    {
        return $this->belongsTo(Group::class,'group_id');
    }
}
