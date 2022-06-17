<?php

namespace App\Http\Controllers\Product;
use App\Http\Controllers\ApiController;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;


class ProductCategoryController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Product $product)
    {
        $categories = $product->categories;

        return $this->showAll($categories);
    }


    /**
     * Update specified resource in storage
     *
     * @param Request $request
     * @param Product $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,Product $product,Category $category){


        /**
         * Many to many attach and sync,syncWithoutDetach
         * attach does not check duplicate
         * sync remove all the rest
         */
            $product->categories()->syncWithoutDetaching($category);

            return $this->showAll($product->categories);
    }


     /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product,Category $category)
    {

        if(!$product->categories()->find($category)){

              return $this->errorResponse('the Specified category is not a category of this product',404);
        }

        $product->categories()->detach($category);


        return $this->showAll($product->categories);


    }


}
