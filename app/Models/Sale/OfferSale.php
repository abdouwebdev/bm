<?php

namespace App\Models\Sale;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OfferSale extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function offer_details()
    {
        return $this->hasMany(OfferSaleDetail::class, 'offer_id');
    }


    public function orders()
    {
        return $this->hasMany(OrderSale::class, 'order_id');
    }

    public function group() 
    {
        return $this->belongsTo(Group::class);
    }
}
