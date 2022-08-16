<?php

namespace App\Models\Sale;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OfferSaleDetail extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $columns = [
        'id', 'offer_id', 'product_id', 'unit', 'price', 'amount',
        'total', 'created_at', 'updated_at'
    ];

    public function scopeExclude($query, $value = [])
    {
        return $query->select(array_diff($this->columns, (array) $value));
    }

    public function offer()
    {
        return $this->belongsTo(OfferSale::class, 'offer_id');
    }

    public function product()
    {
        return $this->belongsTo('\App\Models\Product', 'product_id');
    }
}
