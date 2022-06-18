<?php

namespace App\Http\Controllers\Buyer;

use App\Http\Controllers\ApiController;
use App\Models\Buyer;


class BuyerController extends ApiController
{


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $buyers = Buyer::has('transactions')->get();


        // return response()->json(['data' => $buyers] ,200);

        // $buyers = User::where('verified',"=",'1')->get();


        return $this->showAll($buyers);
    }



    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Buyer $buyer)

    {
        dd($buyer);
        return $this->showOne($buyer);
    }


}
