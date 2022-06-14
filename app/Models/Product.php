<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory,SoftDeletes;

    protected $dates = ['deleted_at'];

    const AVAILABLE_PRODUCTS = 'available';
    const UNAVAILABLE_PRODUCTS = 'unavailable';


    protected $fillable = [
        'name',
        'description',
        'quantity',
        'status',
        'image',
        'selled_id',
        'color'
    ];

    protected $hidden = [

        'pivot'
    ];


    public function isAvailable(){

       return $this->status === Product::AVAILABLE_PRODUCTS;
    }


    public function seller(){

        return $this->belongsTo(Seller::class);
    }


    public function transactions(){

        return $this->hasMany(Transaction::class);

    }



    public function categories(){
        return $this->belongsToMany(Category::class);
    }



}
