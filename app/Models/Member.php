<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Member extends Model
{

    
  use HasFactory;

  protected $dates = ['created_at','dob','age'];
  protected $table = 'members';
  protected $fillable = [
    'idNo',
    'session',
    'department_id',
    'sector_id',
    'group_id',
    'author_id',
    'firstName',
    'lastName',
    'mobileNo',
    'gender',
    'bloodgroup',
    'nationality',
    'dob',
    'age',
    'photo',
    'fatherName',
    'fatherMobileNo',
    'motherName',
    'motherMobileNo',
    'localGuardian',
    'localGuardianMobileNo',
    'presentAddress',
    'isActive'
  ];
 
  function setDobAttribute($value)
  {
    $this->attributes['dob'] = Carbon::createFromFormat('d-m-Y', $value);
  }

  function setAgeAttribute($value)
  {
    $this->attributes['age'] = Carbon::parse($value)->age;
  }

  public function department() {
    return $this->belongsTo('App\Models\Department');
  }

  public function sector() {
    return $this->belongsTo('App\Models\Sector');
  }

  public function personalaccount(){
    
      return $this->hasMany(PersonalAccount::class);
  }

  public function user() {
    return $this->belongsTo('App\Models\User');
  }
  
  public function registered() {
    return $this->hasMany('App\Models\Registration','membres_id');
  }
 
  public function accounts() {
    return $this->hasMany('App\Models\Account','membre_id');
  }

  public function group() 
  {
      return $this->belongsTo(Group::class);
  }
  
}
