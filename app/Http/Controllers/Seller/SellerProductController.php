<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\ApiController;

use App\Models\Product;
use App\Models\Seller;
use App\Models\User;
use Exception;
use GuzzleHttp\Handler\Proxy;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;

class SellerProductController extends ApiController
{

    private $product;

    public function __construct()
    {

        $this->product = new Product();

    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Seller $seller)
    {
        $products = $seller->products;
        return $this->showAll($products);
    }



    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request,User $seller)
    {

        $rules = [

             'name'=>'required',
             'description' => 'required',
             'quantity' => 'required|integer|min:1',
             'image' => 'required|image',

        ];


        $this->validate($request,$rules);


        $data = $request->all();



        $data['status'] = Product::UNAVAILABLE_PRODUCTS;
        $data['image'] = '1.jpg';
        $data['seller_id'] = $seller->id;
        $pro = $this->product->create($data);

        return $this->showOne($pro,201);


    }




    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Seller  $seller
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Seller $seller,Product $product)
    {

        $rules = [

            'quantity' => 'integer|min:1',
            'status' => 'in:'. Product::AVAILABLE_PRODUCTS . ','. Product::UNAVAILABLE_PRODUCTS,
            'image' => "image"
        ];

        $this->validate($request,$rules);

          $this->checkSeller($seller,$product);

          $product->fill($request->only([

            'name',
            'description',
            'quantity'
          ]));

          if($request->has('status')){
                $product->status = $request->status;

                if($product->isAvailable() && $product->categories()->count() === 0){

                    return $this->errorResponse('An active product must have at least one Category',409);
                }
          }

          if($product->isClean()){

               return $this->errorResponse('You need to specify a different value to update',422);
          }

          $product->save();

          return $this->showOne($product);

    }

    protected function checkSeller(Seller $seller,Product $product){

         if($seller->id !== $product->seller_id){

            throw new HttpException(422,'the specified seller is not the actual seller of the product');
         }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Seller  $seller
     * @return \Illuminate\Http\Response
     */
    public function destroy(Seller $seller, Product $product)
    {

        $this->checkSeller($seller,$product);

        $product->delete();

        return $this->showOne($product);

    }
}
