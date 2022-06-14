<?php

namespace App\Models;

use App\Scopes\SellerScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Seller extends User
{
    use HasFactory;

    use HasFactory,SoftDeletes;

    protected $dates = ['deleted_at'];

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new SellerScope);
    }
    public function products(){

        return $this->hasMany(Product::class);
    }
}
