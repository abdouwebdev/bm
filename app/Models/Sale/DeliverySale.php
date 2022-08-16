<?php

namespace App\Models\Sale;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeliverySale extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function delivery_details()
    {
        return $this->hasMany(DeliverySaleDetail::class, 'delivery_id');
    }

    public function customer()
    {
        return $this->belongsTo(\App\Models\Contact::class);
    }

    public function order()
    {
        return $this->belongsTo(OrderSale::class, 'order_id');
    }
}
