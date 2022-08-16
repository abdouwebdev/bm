<?php

namespace App\Models\Sale;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderSale extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function order_details()
    {
        return $this->hasMany(OrderSaleDetail::class, 'order_id');
    }

    public function offer()
    {
        return $this->belongsTo(OfferSale::class, 'offer_id');
    }

    public function delivery()
    {
        return $this->hasMany(DeliverySale::class, 'delivery_id');
    }

    public function group() 
    {
        return $this->belongsTo(Group::class);
    }
}
