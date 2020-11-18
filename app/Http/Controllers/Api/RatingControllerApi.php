<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Rating;
use App\User;
use App\Product;

class RatingControllerApi extends Controller
{
    public function checkproductidofRating(Request $request){
        $userid = $request->user_id;
        $product_id = $request->product_id;
        $userId =User::where('userid',$userid)->get()->first();
        $products = Product::where('id',$product_id)->first();
        $fetchdatafromrating = Rating::where('user_id',$userId->userid)->where('product_id',$products->id)->get()->first();
        if($fetchdatafromrating) {
            $reponse['success'] = true;
            $reponse['total_ratings'] = $fetchdatafromrating->total_ratings;
            $reponse['given_rating'] = $fetchdatafromrating->given_rating;
        }else{
            $reponse['success'] = false;  
            $reponse['total_ratings'] = 5;
            $reponse['given_rating'] = 0;  
        }
        return response()->json($reponse);
    }

    public function inserttoratings(Request $request){
        $userid = $request->user_id;
        $product_id = $request->product_id;
        $userId =User::where('userid',$userid)->get()->first();
        $products = Product::where('id',$product_id)->first();
        if($userid != ''){
            $fetchrating = Rating::where('user_id',$userId->userid)->where('product_id',$products->id)->get()->count();
            if($fetchrating < 1){
                $ratings = new Rating;
                $ratings->product_id = $product_id;
                $ratings->user_id = $userid;
                $ratings->given_rating = $request->given_rating;
                $ratings->save();
                
                $fetchratings = Rating::where('product_id',$product_id)->get();
                $totalratings = $fetchratings->count(); 
                $total = 5;
                $get = [];
                foreach($fetchratings as $key => $value){
                   $get[] = $value->given_rating;
                }
                $avg = array_sum($get) / count($get);
                $a = number_format((float)$avg, 1, '.', '');
                $reponse['success']= true;
                $reponse['given_rating'] = $totalratings;
                $reponse['averagerating'] = $a;
                $reponse['message'] =  "Thank you for rating";
            }  
            else{
                $fetchratinginfo = Rating::where('user_id',$userId->userid)->where('product_id',$products->id)->get()->first();
                $ratings = $fetchratinginfo->given_rating;  
                $updateRating = Rating::where('user_id',$userId->userid)
                                      ->where('product_id',$products->id)
                                      ->update(['given_rating'=>$request->given_rating]);
                                      
                $fetchratings = Rating::where('product_id',$product_id)->get();
                $totalratings = $fetchratings->count(); 
                $total = 5;
                $get = [];
                foreach($fetchratings as $key => $value){
                   $get[] = $value->given_rating;
                }
                $avg = array_sum($get) / count($get);
                $a = number_format((float)$avg, 1, '.', '');
                $reponse['success']= false;
                $reponse['given_rating'] = $totalratings;
                $reponse['averagerating'] = $a;
                $reponse['message'] =  "Thank you for rating";
            }
        }
        else{
            $reponse['success'] =  false;
            $reponse['message'] =  "Login to rate this product";
  
        }
       
        return response()->json($reponse);
}

    public function fetchfromratings(Request $request){
        $product_id = $request->product_id;
        $userId = $request->user_id;
         $fetchratings = Rating::where('product_id',$product_id)->count();
        if($fetchratings > 0){
            $fetchrating = Rating::where('product_id',$product_id)->get();
            $totalratings = $fetchrating->count(); 
            $total = 5;
            $get = [];
            foreach($fetchrating as $key => $value){
               $get[] = $value->given_rating;
            }
            $avg = array_sum($get) / count($get);
            $a = number_format((float)$avg, 1, '.', '');
            $reponse['success']= true;
            $reponse['given_rating'] = $totalratings;
            $reponse['averagerating'] = $a;
            
        }
        else{
              $reponse['success']= false;
              $reponse['message']= "No data found";
        }
       
        return response()->json($reponse);
    
    }
}
