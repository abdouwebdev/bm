<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BuyDetail extends Model
{
    use HasFactory;

    protected $table = 'offer_details';
    protected $guarded = [];
}
