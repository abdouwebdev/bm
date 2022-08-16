<?php

namespace App\Models\Purchase;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OfferDetail extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $table = 'offer_details';

    public function product()
    {
        return $this->belongsTo(\App\Models\Product::class, 'product_id');
    }
}
