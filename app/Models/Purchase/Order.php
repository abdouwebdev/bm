<?php

namespace App\Models\Purchase;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function supplier()
    {
        return $this->belongsTo(\App\Models\Contact::class);
    }

    public function offer()
    {
        return $this->belongsTo(Offer::class, 'offer_id');
    }

    public function order_details()
    {
        return $this->hasMany(OrderDetail::class, 'order_id');
    }
    public function group() 
    {
        return $this->belongsTo(Group::class);
    }
}
