<?php

namespace App\Models\Purchase;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Offer extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function supplier()
    {
        return $this->belongsTo(\App\Models\Contact::class);
    }

    public function offer_details()
    {
        return $this->hasMany(OfferDetail::class, 'offer_id');
    }

    public function group() 
    {
        return $this->belongsTo(Group::class,'group_id');
    }
}
