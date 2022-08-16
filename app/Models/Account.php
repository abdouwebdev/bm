<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Account extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function scopeActive($query)
    {
        return $query->where('status', '=', '1');
    }

    public function scopeFindByCode($query, $code)
    {
        return $query->where('code', $code)->first();
    }

    public static function getPossibleLevels()
    {
        $level = DB::select(DB::raw('SHOW COLUMNS FROM accounts WHERE Field = "level"'))[0]->Type;
        preg_match('/^enum\((.*)\)$/', $level, $matches);
        $values = [];
        foreach (explode(',', $matches[1]) as $value) {
            $values[] = trim($value, "'");
        }

        return $values;
    }


    public function invoice_sales()
    {
        return $this->hasMany(\App\Models\Sale\InvoiceSale::class, 'account_id');
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'account_id');
    }
    public function asset_treasure()
    {
        return $this->hasMany(Asset::class, 'asset_treasure');
    }
    public function accumulated_depreciation()
    {
        return $this->hasMany(Asset::class, 'accumulated_depreciation');
    }
    public function depreciation()
    {
        return $this->hasMany(Asset::class, 'depreciation');
    }

    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id');
    }

    public function group()
    {
        return $this->belongsTo(group::class, 'group_id');
    }

    public function sector()
    {
        return $this->belongsTo(Sector::class, 'sector_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function member()
    {
        return $this->belongsTo(Member::class, 'member_id');
    }

    public function personalaccount()
    {

        return $this->belongsTo(PersonalAccount::class, 'personal_account_id');
    }
}