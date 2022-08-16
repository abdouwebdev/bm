<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jackpot extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function group() 
    {
        return $this->belongsTo(group::class,'group_id');
    }

    public function member() 
    {
        return $this->belongsTo(Member::class,'member_id');
    }

    public function jackpotdetail(){

       return $this->hasMany(JackpotDetails::class);
    }
}
