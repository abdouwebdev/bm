<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Buy extends Model
{
    use HasFactory;

    protected $table = 'offers';
    protected $guarded = [];

    public function buydetails()
    {
        return $this->hasMany(BuyDetail::class);
    }
    public function supplier()
    {
        return $this->belongsTo(Contact::class,'supplier_id','id');
    }
}
