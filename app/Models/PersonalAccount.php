<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PersonalAccount extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function member(){

      return $this->belongsTo(\App\Models\Member::class);
    }

    public function user() 
    {
        return $this->belongsTo(User::class,'author_id');
    }

    public function accounts() {
      return $this->hasMany('App\Models\Account','personal_account_id');
    }

    public function group() 
    {
        return $this->belongsTo(group::class,'group_id');
    }
}
