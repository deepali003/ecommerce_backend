<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\WishList;
use Auth;
use Session;
use App\User;
use App\Product;
use App\ProductsAttribute;
class WishListControllerApi extends Controller
{
    public function inserttoWishlist(Request $request){
            $userid = $request->user_id;
            $product_id = $request->product_id;
            $userId =User::where('userid',$userid)->get()->first();
            $products = Product::where('id',$product_id)->first();
            $productAttribute = ProductsAttribute::where('product_id',$product_id)->first();
            $fetchwishlist = WishList::where('user_id',$userId->userid)->where('product_id',$products->id)->get()->count();
            if($productAttribute){
                $cutted_price = $productAttribute->price;
            }
            else{
                $cutted_price = 0.00;
            }
               $wishlist = new WishList;
                $wishlist->product_id = $product_id;
                $wishlist->user_id = $userId->userid;
                $wishlist->product_name = $products->product_name;
                $wishlist->product_image = $products->image;
                $wishlist->product_code = $products->product_code;
                $wishlist->product_color = $products->product_color;
                $wishlist->description = $products->description;
                $wishlist->care = $products->care;
                $wishlist->sleeve = $products->sleeve;
                $wishlist->pattern = $products->pattern;
                $wishlist->weight = $products->weight;
                $wishlist->size = $products->size;
                $wishlist->price = $products->price;
                $wishlist->cutted_price = $cutted_price;
                $wishlist->save();

                $reponse['product_id'] =  $wishlist->product_id;
                $reponse['user_id'] =  $wishlist->user_id;
                $reponse['product_name'] =  $wishlist->product_name;
                $reponse['product_image'] =  $wishlist->product_image;
                $reponse['price'] =  $wishlist->price;
                $reponse['success'] =  true;
                $reponse['message'] = "Added to wishlist successfully";
                
            // }
            // echo json_encode($response);die;
            return response()->json($reponse);
    }

    public function checkproductidtowishlist(Request $request){
        $userid = $request->user_id;
        $product_id = $request->product_id;
        $userId =User::where('userid',$userid)->get()->first();
        $products = Product::where('id',$product_id)->first();
        $fetchdatafromwishlist = WishList::where('user_id',$userId->userid)->where('product_id',$products->id)->get()->count();
        if($fetchdatafromwishlist) {
            $reponse['success'] = true;
            $reponse['message'] = "Fetched";
        }else{
            $reponse['success'] = false;  
            $reponse['message'] = "Not Fetched";  
        }
        return response()->json($reponse);
    }
         
    public function deletefromwishlist(Request $request){
        $userid = $request->user_id;
        $product_id = $request->product_id;
        $userId =User::where('userid',$userid)->get()->first();
        $products = Product::where('id',$product_id)->first();
        $deleteproduct = WishList::where('user_id',$userId->userid)->where('product_id',$products->id)->delete();
        if($deleteproduct){
            $reponse['success'] = true;
            $reponse['message'] = "Data removed from the list";
        }
        else{
            $reponse['success'] = false;
            $reponse['message'] = "Something went wrong!! please try again";
        }
        
        return response()->json($reponse);
    }
    public function fetchfromwishlist(Request $request){
        $userid = $request->user_id;
        $checkWishList = WishList::where('user_id',$userid)->get()->count();
        if($checkWishList)
        {
           $fetchfromwishlist = WishList::where('user_id',$userid)->get();
        }
        return response()->json($fetchfromwishlist);
    }
}
