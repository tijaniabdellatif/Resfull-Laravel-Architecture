<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

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


    public function isAvailable(){

       return $this->status === Product::AVAILABLE_PRODUCTS;
    }

}
