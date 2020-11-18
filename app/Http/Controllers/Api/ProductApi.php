<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Product;
use App\Category;
use App\ProductAtrribute;
use DB;
use App\ProductsImage;
use App\ProductsAttribute;
use App\Cart;
use App\User;
use App\Order;
use App\OrdersProduct;
use App\Mobile;
use App\ShareApp;
class ProductApi extends Controller
{
    public function hproducts($id)
    {
            $fetchProduct = Product::where('category_id',$id)
                                   ->where('status',1)
                                   ->inRandomOrder()->get();
            return response()->json($fetchProduct);
    }

    public function hfeaturedproduct($id)
    {
            $hfeaturedproduct = Product::where('category_id',$id)
                                       ->where('feature_item','!=',0)
                                       ->where('status',1)->inRandomOrder()
                                       ->get();
            return response()->json($hfeaturedproduct);
    }

    public function productimage($id)
    {
            $productimage = ProductsImage::where('product_id',$id)->get();
            return response()->json($productimage);
    }

    public function productdetails($id)
    {
            $fetchProductCount = ProductsAttribute::where('product_id',$id)->get()->count();
        //     dd($fetchProductDetails);    
            if($fetchProductCount > 0)
            {
                $fetchProductDetails = ProductsAttribute::where('product_id',$id)->select('sku','size','price','stock')->get()->first();
                $reponse['success'] = true;
                $reponse['sku']= $fetchProductDetails->sku;
                $reponse['size']= $fetchProductDetails->size;
                $reponse['price']= $fetchProductDetails->price;
                $reponse['stock']= $fetchProductDetails->stock;
            }
            else{
                $reponse['success'] = false;
                $reponse['fetchProductDetail'] = "No data Available";
            }
        //     $title = [];
        //     $values   = []; 

        //     if($fetchSpec){
        //         foreach($fetchSpec as $key => $data){ 
        //                 foreach($data as $key1 => $newVal){
        //                         $title[] = $key1;
        //                         $values[] = $newVal;
        //                 }
                        
        //         }
        //     }
        //    echo json_encode(['title' => $title , 'values' => $values]);
           return response()->json($reponse);
            
    }

    public function addtocart(Request $request){
        $userid = $request->user_id;
        $product_id = $request->product_id;
        $quantity = $request->quantity;
        $userId =User::where('userid',$userid)->get()->first();
        $products = Product::where('id',$product_id)->first();
        $productPrices = ProductsAttribute::where('product_id',$products->id)->get()->count();
        if($productPrices > 0){
            $productPrice = ProductsAttribute::where('product_id',$products->id)->pluck('price')->first();
        }
        else{
            $productPrice = 0.00;
        }
        $checkCart = Cart::where('product_id',$products->id)->where('user_id',$userId->userid)->get()->count();
        if(!$checkCart)
        {
            $addToCart = new Cart;
            $addToCart->product_id = $product_id;
            $addToCart->product_name = $products->product_name;
            $addToCart->product_image = $products->image;
            $addToCart->product_code = $products->product_code;
            $addToCart->product_color = $products->product_color;
            $addToCart->price = $products->price;
            // if($productPrice)
            // {
                $addToCart->cutted_price = $productPrice;
            // }
            // else{
            //     $addToCart->cutted_price = 0.00;
            // }
            $addToCart->description = $products->description;
            $addToCart->care = $products->care;
            $addToCart->sleeve = $products->sleeve;
            $addToCart->pattern = $products->pattern;
            $addToCart->weight = $products->weight;
            if($quantity == 0){
                $addToCart->quantity =1;
            }else{
                $addToCart->quantity = $quantity;
            }
            
            $addToCart->user_id = $userid;
            $addToCart->save();
            $reponse['success'] = true; 
            $reponse['message'] = "Added to Cart"; 
           
        }
        else{
             $checkCartqty = Cart::where('product_id',$products->id)->where('user_id',$userId->userid)->pluck('quantity')->first();
             if($quantity > $checkCartqty){
                  $updateqty = Cart::where('product_id',$products->id)->where('user_id',$userId->userid)
                             ->update(['quantity'=>$checkCartqty+1]);
             }
             elseif($quantity == $checkCartqty){
                  $updateqty = Cart::where('product_id',$products->id)->where('user_id',$userId->userid)
                             ->update(['quantity'=>$checkCartqty+1]);
             }
             else{
                 $updateqty = Cart::where('product_id',$products->id)->where('user_id',$userId->userid)
                             ->update(['quantity'=>$checkCartqty-1]);
             }
            
            $reponse['success'] = false; 
            $reponse['message'] = "Added to Cart"; 
        }
        return response()->json($reponse);
    }

    public function fetchfromcart(Request $request){
        $userid = $request->user_id;
        $checkCart = Cart::where('user_id',$userid)->get()->count();
        if($checkCart)
        {
           $fetchfromcart = Cart::where('user_id',$userid)->get();
        }
        // else{
        //     $fetchfromcart = "No data found";
        // }
        return response()->json($fetchfromcart);
    }
    
    
      
    public function deletefromcart(Request $request){
        $userid = $request->user_id;
        $product_id = $request->product_id;
        $userId =User::where('userid',$userid)->get()->first();
        $products = Product::where('id',$product_id)->first();
        $checkCart = Cart::where('user_id',$userId->userid)->where('product_id',$products->id)->delete();
        if($checkCart){
            $reponse['success'] = true;
            $reponse['message'] = "Data removed from the list";
        }
        else{
            $reponse['success'] = false;
            $reponse['message'] = "Something went wrong!! please try again";
        }
        
        return response()->json($reponse);
    }
    
    public function cartcount(Request $request){
        $userid = $request->user_id;
        $userId =User::where('userid',$userid)->get()->count();
        if($userId){
            // dd("if");
            $checkCart = Cart::where('user_id',$userid)->get()->count();
            if($checkCart)
            {
              $reponse['success'] = true;
              $reponse['message'] = $checkCart;
            }
            else{
                $checkCart =0;
                $reponse['success'] = false;
                $reponse['message'] = $checkCart;
            }
        }else{
             $checkCart = 0;
             $reponse['success'] = false;
             $reponse['message'] = $checkCart;
        }
        
        return response()->json($reponse);
    }
    
    public function submitOrder(Request $request){
        $user_id = $request->user_id;
        $order_status = "Pending";
        $payment_method = $request->payment_method;
        $userCount = User::where('userid',$user_id)->get()->count();
        if($userCount > 0){
            $userInfo = User::where('userid',$user_id)->get()->first();
            $getOrderCount = Order::where('user_id',$userInfo->userid)->get()->count();
            $checkCart = Cart::where('user_id',$userInfo->userid)->get()->count();
             $fetchcartptotal = Cart::where('user_id',$userInfo->userid)->get();
             $Subtotal = 0;
             foreach($fetchcartptotal as $fetchcartptotals){
                 $Subtotal = $Subtotal + ($fetchcartptotals->price * $fetchcartptotals->quantity);
             }
             
            //  $Subtotal = $Subtotal + ($pro->product_price * $pro->product_qty); 
            if($checkCart > 0){
                    $fetchfromcart = Cart::where('user_id',$userInfo->userid)->get();
                    $insertOrder = new Order;
                    $insertOrder->user_id = $userInfo->userid;
                    $insertOrder->name = $userInfo->name;
                    $insertOrder->user_email = $userInfo->email;
                    $insertOrder->address = $userInfo->address;
                    $insertOrder->city = $userInfo->city;
                    $insertOrder->state = $userInfo->state;
                    $insertOrder->country = $userInfo->country;
                    $insertOrder->pincode = $userInfo->pincode;
                    $insertOrder->mobile = $userInfo->mobile;
                    $insertOrder->order_status = $order_status;
                    $insertOrder->payment_method = $payment_method;
                    $insertOrder->grand_total = $Subtotal;
                    $insertOrder->save();
                    $fetchOrderId = Order::where('user_id',$insertOrder->user_id)->pluck('id')->last();
                    foreach($fetchfromcart as $key => $value){
                        
                        $orderProducts = new OrdersProduct;
                        $orderProducts->order_id = $fetchOrderId;
                        $orderProducts->user_id = $userInfo->userid;
                        $orderProducts->cart_id = $value->id;
                        $orderProducts->product_id = $value->product_id;
                        $orderProducts->product_code = $value->product_code;
                        $orderProducts->product_name = $value->product_name;
                        $orderProducts->product_image = $value->product_image;
                        $orderProducts->product_size = $value->size;
                        $orderProducts->product_color = $value->product_color;
                        $orderProducts->product_price = $value->price;
                        $orderProducts->pcutted_price = $value->cutted_price;
                        $orderProducts->product_description = $value->description;
                        $orderProducts->product_care = $value->care;
                        $orderProducts->product_sleeve = $value->sleeve;
                        $orderProducts->product_pattern = $value->pattern;
                        $orderProducts->weight = $value->weight;
                        $orderProducts->product_qty = $value->quantity;
                        $orderProducts->total = $value->total;
                        $orderProducts->save();
                        $deleteCart = Cart::where('user_id',$userInfo->userid)->where('id',$value->id)->delete();
                    }
                  
                   
            }   
            else{
                    $fetchpro = Product::where('id',$request->product_id)->pluck('price')->first();
                    $insertOrder = new Order;
                    $insertOrder->user_id = $userInfo->userid;
                    $insertOrder->name = $userInfo->name;
                    $insertOrder->user_email = $userInfo->email;
                    $insertOrder->address = $userInfo->address;
                    $insertOrder->city = $userInfo->city;
                    $insertOrder->state = $userInfo->state;
                    $insertOrder->country = $userInfo->country;
                    $insertOrder->pincode = $userInfo->pincode;
                    $insertOrder->mobile = $userInfo->mobile;
                    $insertOrder->order_status = $order_status;
                    $insertOrder->payment_method = $payment_method;
                    $insertOrder->grand_total = $fetchpro;
                    $insertOrder->save();
                    
                    $orderProducts = new OrdersProduct;
                    $orderProducts->order_id = $fetchOrderId;
                    $orderProducts->user_id = $userInfo->userid;
                    $orderProducts->cart_id = $value;
                    $orderProducts->save();
                    
                     $deleteCart = Cart::where('user_id',$userInfo->userid)->where('product_id',$request->product_id)->delete();
                  
            }
            
                $reponse['success']=true;
                $reponse['message']="Order placed successfully";
        }
        else{
                    $reponse['success'] = false;
                    $reponse['message']=  "Something went wrong!! please try again";
        }
        $payment = PaytmWallet::with('receive');
        $payment->prepare([
          'order' => $input['order_id'],
          'user' => 'your paytm user',
          'mobile_number' => 'your paytm number',
          'email' => 'your paytm email',
          'amount' => $input['fee'],
          'callback_url' => url('api/payment/status')
        ]);
            // return->response()->json($response);
            return response()->json($reponse);
    }
    
    public function orderProduct()
    {           
            $hfeaturedproductcount = Product::where('feature_item','!=',0)->where('status',1)->count();
            if($hfeaturedproductcount > 0){
                 $hfeaturedproduct = Product::where('feature_item','!=',0)->where('status',1)->limit(6)->inRandomOrder()->get();
            }
            else{
                $hfeaturedproduct = 0;
            }
           
              return response()->json($hfeaturedproduct);
         
    }
    
    public function allOrderProduct()
    {           
            $hfeaturedproductcount = Product::where('feature_item','!=',0)->where('status',1)->count();
            if($hfeaturedproductcount > 0){
                 $hfeaturedproduct = Product::where('feature_item','!=',0)->where('status',1)->inRandomOrder()->get();
            }
            else{
                $hfeaturedproduct = 0;
            }
           
              return response()->json($hfeaturedproduct);
         
    }
    
    public function quantitycount(Request $request,$quantity){
        $userid = $request->user_id;
        $product_id = $request->product_id;
        $userId =User::where('userid',$userid)->get()->first(); 
        $products = Product::where('id',$product_id)->first(); 
        $fetchcart = Cart::where('product_id',$products->id)->where('user_id',$userId->userid)->pluck('quantity')->first();
        $fetchcartotal = Cart::where('product_id',$products->id)->where('user_id',$userId->userid)->first();
        $getTotal = $fetchcartotal->price * $request->quantity;    
        // dd($request->quantity); 
        $updatequantity = Cart::where('product_id',$products->id)
                              ->where('user_id',$userId->userid)
                              ->update(['quantity'=>$request->quantity,'total'=>$getTotal]);
                         
        if($updatequantity){
            $fetchcartqut = Cart::where('product_id',$products->id)->where('user_id',$userId->userid)->first();
            $fetchcartptotal = Cart::where('user_id',$userId->userid)->pluck('total')->sum();
            $fetchcartqtytotal = Cart::where('user_id',$userId->userid)->pluck('quantity')->sum();
            $reponse['success']=true;
            $reponse['quantity']=$fetchcartqtytotal;
            $reponse['total']=$fetchcartptotal;
             if($fetchcartqut->quantity > $fetchcart){
                $reponse['message']="1";
            }
            else{
                $reponse['message']="2";
            }
           
        }   else{
             $reponse['success']=false;
             $reponse['message']='Something went wrong!! Please try again..';
        }                   
    
        return response()->json($reponse);
    }
    
    public function orderHistory(Request $request){
         $user_id = $request->user_id;
         $fetchOrders = Order::where('user_id',$user_id)->orderBy('id','DESC')->get();
         return response()->json($fetchOrders);
    }
    
    public function orderHistoryDetails(Request $request){
         $order_id = $request->order_id;
         $user_id = $request->user_id;
         $fetchOrders = OrdersProduct::where('order_id',$order_id)->where('user_id',$user_id)->orderBy('id','DESC')->get();
         return response()->json($fetchOrders);
    }
    
    public function orderHistoryUserDetails(Request $request){
         $order_id = $request->order_id;
         $fetchOrders = Order::where('id',$order_id)->get()->first();
         $reponse['success'] = true;
         $reponse['name'] = $fetchOrders->name;
         $reponse['address'] = $fetchOrders->address;
         $reponse['city'] = $fetchOrders->city;
         $reponse['state'] = $fetchOrders->state;
         $reponse['country'] = $fetchOrders->country;
         $reponse['pincode'] = $fetchOrders->pincode;
         return response()->json($reponse);
    }
    
    public function fetchMobile(){
        $fetch = Mobile::get()->first();
        if($fetch->status == 1){
            $reponse['success'] = true;
            $reponse['mobile_no'] = $fetch->mobile_no;
        }
        else{
            $reponse['success'] = false;
            $reponse['message'] = "No mobile no. found";
        }
        return response()->json($reponse);
    }
    
      public function fetchApp(){
        $fetch = ShareApp::get()->first();
        if($fetch->status == 1){
            $reponse['success'] = true;
            $reponse['subject'] = $fetch->subject;
            $reponse['body'] = $fetch->body;
        }
        else{
            $reponse['success'] = false;
            $reponse['message'] = "No details found";
        }
        return response()->json($reponse);
    }

    public function paymentCallback(){
        $transaction = PaytmWallet::with('receive');
        $response = $transaction->response();
        $order_id = $transaction->getOrderId();

        if($transaction->isSuccessful()){
          Order::where('order_id',$order_id)->update(['status'=>2, 'transaction_id'=>$transaction->getTransactionId()]);

          dd('Payment Successfully Paid.');
        }else if($transaction->isFailed()){
          EventRegistration::where('order_id',$order_id)->update(['status'=>1, 'transaction_id'=>$transaction->getTransactionId()]);
          dd('Payment Failed.');
        }
    }


    
    //  public function orderHistoryCountDetails(Request $request){
    //     $order_id = $request->order_id;
    //     $user_id = $request->user_id;
    //         $fetchcartptotal   = OrdersProduct::where('order_id',$order_id)->where('user_id',$user_id)->pluck('product_price')->all();dd($fetchcartptotal);
    //         $fetchcartqtytotal = OrdersProduct::where('order_id',$order_id)->where('user_id',$user_id)->pluck('quantity')->sum();
    //         $reponse['success']=true;
    //         $reponse['quantity']=$fetchcartqtytotal;
    //         $reponse['total']=$fetchcartptotal;
    //     return response()->json($reponse);
    // }
    
}

