<?php

namespace App\Http\Controllers\Buyer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Buyer;

class BuyerController extends Controller
{


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $buyer = Buyer::has('transactions')->get();


        return response()->json(
            [
                'data' => $buyer
            ],200);
    }



    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $buyers = Buyer::has('transactions')->findOrFail($id);


        return response()->json(
            [
                'data' => $buyers
            ],200);
    }


}
