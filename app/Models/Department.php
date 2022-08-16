<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function member()
    {
        return $this->hasMany('App\Models\Member','department_id');
    }

    public function accounts() 
    {
        return $this->hasMany('App\Models\Account','department_id');
    }

    public function registered() 
    {
        return $this->hasMany('App\Models\Registration','department_id');
    }

    public function group() 
    {
        return $this->belongsTo(Group::class);
    }
}
