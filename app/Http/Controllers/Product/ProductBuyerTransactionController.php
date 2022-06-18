<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\Buyer;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Support\Facades\DB;

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

        $rules = [

            'quantity' => 'required|integer|min:1',
            'info' => 'required|string'
        ];

        $this->validate($request,$rules);
        if($buyer->id == $product->seller_id){

             return $this->errorResponse('You are not a seller you are a buyer',409);
        }


        if(!$buyer->isVerified()){

            return $this->errorResponse('the buyer must be verified',409);
        }


        if(!$product->seller->isVerified()){

            return $this->errorResponse('the seller must be verified',409);
        }


        if(!$product->isAvailable()){

             return $this->errorResponse('the product is not available',409);
        }


        if($product->quantity < $request->quantity){

              return $this->errorResponse('The product does not have enough units for the transaction',409);
        }


        return DB::transaction(function() use ($request,$product,$buyer){


            $product->quantity -= $request->quantity;
            $product->save();


            $transaction = Transaction::create([

                    'quantity' => $request->quantity,
                    'buyer_id' => $buyer->id,
                    'product_id' => $product->id,
                    'info' => $request->info


            ]);


            return $this->showOne($transaction,201);

        });


    }


}
