<?php

namespace App\Models\Sale;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoiceSaleDetail extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function invoice()
    {
        return $this->belongsTo(InvoiceSale::class, 'invoice_id');
    }

    public function product()
    {
        return $this->belongsTo(\App\Models\Product::class);
    }
}
