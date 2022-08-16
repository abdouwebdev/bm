<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function accounts() 
    {
        return $this->hasMany('App\Models\Account','group_id');
    }

    public function member()
    {
        return $this->hasMany('App\Models\Member','group_id');
    }

    public function user() 
    {
        return $this->belongsTo(User::class,'author_id');
    }

    public function department() 
    {
        return $this->hasMany('App\Models\Department','group_id');
    }

    public function sector() 
    {
        return $this->hasMany('App\Models\Sector','group_id');
    }

    public function contact() 
    {
        return $this->hasMany('App\Models\Contact','group_id');
    }

    public function offerSale() 
    {
        return $this->hasMany('App\Models\OfferSale','group_id');
    }

    public function orderSale() 
    {
        return $this->hasMany('App\Models\OrderSale','group_id');
    }

    public function invoiceSale() 
    {
        return $this->hasMany('App\Models\invoiceSale','group_id');
    }

    public function jackpotdetail()
    {
        return $this->hasMany(JackpotDetails::class);
    }

    
}
