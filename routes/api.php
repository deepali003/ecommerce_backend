<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Api Routes
|--------------------------------------------------------------------------
|
| Here is where you can register Api routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your Api!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
Route::post('register', 'Api\UserController@register');
Route::post('verify-user', 'Api\UserController@verifyContact');
Route::post('updateuserinfo', 'Api\UserController@updateuserinfo');
Route::post('/login', 'Api\UserController@login');
Route::post('/password/email', 'Api\ForgotPasswordController@sendResetLinkEmail');
Route::post('password/reset', 'Api\ResetPasswordController@reset');
Route::get('/splashlogin/{userid}','Api\UserController@splashlogin');


Route::get('category', 'Api\CategoryApi@index');
Route::get('horizontalCategory', 'Api\CategoryApi@horizontalCategory');
Route::get('gridcategory', 'Api\CategoryApi@gridCategory');
Route::get('allGridcategory', 'Api\CategoryApi@allGridcategory');


Route::get('banner', 'Api\BannerApi@index');
Route::get('adbanner', 'Api\AdBannerApi@index');
Route::get('hproduct/{id}', 'Api\ProductApi@hproducts');
Route::get('hfeaturedproduct/{id}', 'Api\ProductApi@hfeaturedproduct');
Route::get('productdetails/{id}', 'Api\ProductApi@productdetails'); 
Route::get('productimage/{id}', 'Api\ProductApi@productimage');
Route::get('orderProduct', 'Api\ProductApi@orderProduct');
Route::get('allOrderProduct', 'Api\ProductApi@allOrderProduct');
Route::post('orderHistory', 'Api\ProductApi@orderHistory');
Route::post('orderHistoryDetails', 'Api\ProductApi@orderHistoryDetails');

Route::post('cartcount', 'Api\ProductApi@cartcount');
Route::post('addtocart', 'Api\ProductApi@addtocart');
Route::post('fetchfromcart', 'Api\ProductApi@fetchfromcart');
Route::post('deletefromcart', 'Api\ProductApi@deletefromcart');
Route::post('quantitycount/{quantity}', 'Api\ProductApi@quantitycount');

//wishlist
Route::post('checkproductidtowishlist', 'Api\WishListControllerApi@checkproductidtowishlist');
Route::post('addtowishlist', 'Api\WishListControllerApi@inserttoWishlist');
Route::post('deletefromwishlist', 'Api\WishListControllerApi@deletefromwishlist');
Route::post('fetchfromwishlist','Api\WishListControllerApi@fetchfromwishlist');

//Ratings
Route::post('checkproductidofRating', 'Api\RatingControllerApi@checkproductidofRating');
Route::post('addtoratings', 'Api\RatingControllerApi@inserttoratings');
Route::post('fetchfromratings', 'Api\RatingControllerApi@fetchfromratings');

Route::post('submitOrder', 'Api\ProductApi@submitOrder');

Route::get('fetchMobile', 'Api\ProductApi@fetchMobile');
Route::get('fetchApp', 'Api\ProductApi@fetchApp');

Route::get('addtowishlist/{id}/{product_id}', 'Api\WishListControllerApi@store');
Route::post('payment/status', 'Api\ProductApi@paymentCallback');
Route::group(['middleware' => 'auth:api'], function () {
    Route::post('details', 'Api\UserController@details');
});
