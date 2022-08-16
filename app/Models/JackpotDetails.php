<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JackpotDetails extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function member(){

      return $this->belongsTo(\App\Models\Member::class);
    }

    public function jackpots() {
      return $this->hasMany('App\Models\Jackpot','jackpot_detail_id');
    }

    public function group() 
    {
        return $this->belongsTo(group::class,'group_id');
    }
}
