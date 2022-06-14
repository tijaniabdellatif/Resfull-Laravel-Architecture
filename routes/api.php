<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Buyer\BuyerController;
use App\Http\Controllers\Category\CategoryController;
use App\Http\Controllers\Product\ProductController;
use App\Http\Controllers\Seller\SellerController;
use App\Http\Controllers\Transaction\TransactionController;
use App\Http\Controllers\User\UserController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

/**
 * Buyers
 * Allow just index and show
 */

 Route::resource('buyers',BuyerController::class,['only' => ['index','show']]);


/**
 * Categories
 * Allow all methods except create and edit
 */
Route::resource('categories',CategoryController::class,['except' => ['create','edit']]);


/**
 * Products
 */

Route::resource('products',ProductController::class,['only' => ['index','show']]);
Route::resource('sellers',SellerController::class,['only' => ['index','show']]);
Route::resource('transactions',TransactionController::class,['only' => ['index','show']]);
Route::resource('users',UserController::class,['except' => ['create','edit']]);
// Route::fallback(function() {

//     return response()->json(['error' => 'Not found','status' => 404],404);
// });
