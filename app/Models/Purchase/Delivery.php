<?php

namespace App\Models\Purchase;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Delivery extends Model
{
    use HasFactory;

    protected $guarded = [];
    
    protected $table = "deliverys";

    public function delivery_details()
    {
        return $this->hasMany(DeliveryDetail::class, 'delivery_id');
    }

    public function supplier()
    {
        return $this->belongsTo(\App\Models\Contact::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }
}
