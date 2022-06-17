<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\Buyer;
use App\Models\User;

class ProductBuyerTransactionController extends ApiController
{


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request,Product $product,User $buyer)
    {
        if($buyer->id == $product->seller_id){

             return $this->errorResponse('You are not a seller you are a buyer',409);
        }


        if(!$buyer->isVerified()){

            return $this->errorResponse('the buyer must be verified',409);
        }


        if(!$product->seller->isVerified()){

            return $this->errorResponse('the seller must be verified',409);
        }


    }


}
