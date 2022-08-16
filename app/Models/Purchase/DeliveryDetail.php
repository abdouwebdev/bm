<?php

namespace App\Models\Purchase;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeliveryDetail extends Model
{
    use HasFactory;

    protected $table = "delivery_details";
    protected $guarded = [];

    public function reception()
    {
        return $this->belongsTo(Delivery::class, 'delivery_id');
    }

    public function product()
    {
        return $this->belongsTo(\App\Models\Product::class);
    }
}
