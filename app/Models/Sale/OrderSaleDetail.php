<?php

namespace App\Models\Sale;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderSaleDetail extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function order()
    {
        $this->belongsTo(OrderSale::class, 'order_id');
    }

    public function product()
    {
        return $this->belongsTo('\App\Models\Product', 'product_id');
    }
}
