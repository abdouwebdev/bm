<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sector extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function member()
    {
        return $this->hasMany('App\Models\Member','sector_id');
    }

    public function accounts() 
    {
        return $this->hasMany('App\Models\Account','sector_id');
    }

    public function group() 
    {
        return $this->belongsTo(Group::class);
    }
}
