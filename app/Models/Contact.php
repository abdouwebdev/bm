<?php

namespace App\Models;

use App\Models\Purchase\Delivery;
use App\Models\Purchase\Offer;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    use HasFactory;

    protected $table = 'contacts';

    protected $guarded = [];

    public function categorie()
    {
        return $this->belongsTo(Categorie::class);
    }
    public function bkk()
    {
        return $this->hasMany(Bkk::class);
    }

    public function generaljournals()
    {
        return $this->hasMany(GeneralJournal::class);
    }
    public function Offerbuy()
    {
        return $this->hasMany(Offer::class);
    }
    public function Orderbuy()
    {
        return $this->hasMany(Order::class);
    }
    public function Deliverybuy()
    {
        return $this->hasMany(Delivery::class);
    }
    public function saves()
    {
        return $this->hasMany(Save::class);
    }

    public function group() 
    {
        return $this->belongsTo(Group::class);
    }
}
