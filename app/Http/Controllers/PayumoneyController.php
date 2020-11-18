<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Softon\Indipay\Facades\Indipay;
use Illuminate\Support\Facades\Mail;
use Session;
use App\Order;
use App\User;
use DB;

class PayumoneyController extends Controller
{
    public function payumoneyPayment(){
        $order_id = Session::get('order_id');
        $grand_total = Session::get('grand_total');
        $orderDetails = Order::getOrderDetails($order_id);
        $orderDetails = json_decode(json_encode($orderDetails));
        /*echo "<pre>"; print_r($orderDetails); die;*/
        $nameArr = explode(' ',$orderDetails->name);
    	$parameters = [
        'txnid' => $order_id,
        'order_id' => $order_id,
        'amount' => $grand_total, 
        'firstname' => $nameArr[0],
        'lastname' => $nameArr[1],
        'email' => $orderDetails->user_email,
        'phone' => $orderDetails->mobile,
        'productinfo' => $order_id,
        'service_provider' => '',
        'zipcode' => $orderDetails->pincode,
        'city' => $orderDetails->city,
        'state' => $orderDetails->state,
        'country' => $orderDetails->country,
        'address1' => $orderDetails->address,
        'address2' => '',
        'curl' => url('payumoney/response'),      
      ];
      
      $order = Indipay::prepare($parameters);
      return Indipay::process($order);

    }

    public function payumoneyResponse(Request $request){
        $response = Indipay::response($request);
        /*echo "<pre>"; print_r($response); die;*/
        if($response['status']=="success" && $response['unmappedstatus']=="captured"){
            //echo "success";

            // Get Order ID
            $order_id = Session::get('order_id');

            // Update Order
            Order::where('id',$order_id)->update(['order_status'=>'Payment Captured']);

            $productDetails = Order::with('orders')->where('id',$order_id)->first();
            $productDetails = json_decode(json_encode($productDetails),true);
            /*echo "<pre>"; print_r($productDetails);*/ /*die;*/

            $user_id = $productDetails['user_id'];
            $user_email = $productDetails['user_email'];
            $name = $productDetails['name'];

            $userDetails = User::where('id',$user_id)->first();
            $userDetails = json_decode(json_encode($userDetails),true);
            /*echo "<pre>"; print_r($userDetails); die;*/
            /* Code for Order Email Start */
            $email = $user_email;
            $messageData = [
                'email' => $email,
                'name' => $name,
                'order_id' => $order_id,
                'productDetails' => $productDetails,
                'userDetails' => $userDetails
            ];
            Mail::send('emails.order',$messageData,function($message) use($email){
                $message->to($email)->subject('Order Placed - E-com Website');    
            });
            /* Code for Order Email Ends */

            // Empty Cart
            DB::table('cart')->where('user_email',$user_email)->delete();

            // Redirect user to thanks page after saving order
            return redirect('/payumoney/thanks');

        }else{
            //echo "fail";

            // Get Order ID
            $order_id = Session::get('order_id');

            // Update Order
            Order::where('id',$order_id)->update(['order_status'=>'Payment Failed']);

            // Redirect user to fail page
            return redirect('/payumoney/fail');

        }
    }

    public function payumoneyThanks(){
        return view('orders.thanks_payumoney');
    }

    public function payumoneyFail(){
        return view('orders.fail_payumoney');
    }

    public function payumoneyVerification($order_id){
        $key = 'gtKFFx';
        $salt = 'eCwWELxi';
        
        $command = "verify_payment";
        $var1 = $order_id;
        $hash_str = $key  . '|' . $command . '|' . $var1 . '|' . $salt ;
        $hash = strtolower(hash('sha512', $hash_str));
        $r = array('key' => $key , 'hash' =>$hash , 'var1' => $var1, 'command' => $command);

        $qs= http_build_query($r);
        $wsUrl = "https://test.payu.in/merchant/postservice?form=2";
        $c = curl_init();
        curl_setopt($c, CURLOPT_URL, $wsUrl);
        curl_setopt($c, CURLOPT_POST, 1);
        curl_setopt($c, CURLOPT_POSTFIELDS, $qs);
        curl_setopt($c, CURLOPT_CONNECTTIMEOUT, 30);
        curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($c, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($c, CURLOPT_SSL_VERIFYPEER, 0);
        $o = curl_exec($c);
        if (curl_errno($c)) {
          $sad = curl_error($c);
          throw new Exception($sad);
        }
        curl_close($c);

        $valueSerialized = @unserialize($o);
        if($o === 'b:0;' || $valueSerialized !== false) {
          print_r($valueSerialized);
        } 
        $o = json_decode($o);
        echo "<pre>"; print_r($o); die;
    }

    public function payumoneyVerify(){
        // Get Last 5 Payumoney Orders
        $orders = Order::where('payment_method','Payumoney')->take(5)->orderBy('id','Desc')->get();
        $orders = json_decode(json_encode($orders));
        /*echo "<pre>"; print_r($orders); die;*/

        foreach($orders as $order){
            $key = 'gtKFFx';
            $salt = 'eCwWELxi';
            $command = "verify_payment";
            $var1 = $order->id;
            $hash_str = $key  . '|' . $command . '|' . $var1 . '|' . $salt ;
            $hash = strtolower(hash('sha512', $hash_str));
            $r = array('key' => $key , 'hash' =>$hash , 'var1' => $var1, 'command' => $command);
    
            $qs= http_build_query($r);
            $wsUrl = "https://test.payu.in/merchant/postservice?form=2";
            $c = curl_init();
            curl_setopt($c, CURLOPT_URL, $wsUrl);
            curl_setopt($c, CURLOPT_POST, 1);
            curl_setopt($c, CURLOPT_POSTFIELDS, $qs);
            curl_setopt($c, CURLOPT_CONNECTTIMEOUT, 30);
            curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($c, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($c, CURLOPT_SSL_VERIFYPEER, 0);
            $o = curl_exec($c);
            if (curl_errno($c)) {
              $sad = curl_error($c);
              throw new Exception($sad);
            }
            curl_close($c);

            $valueSerialized = @unserialize($o);
            if($o === 'b:0;' || $valueSerialized !== false) {
              print_r($valueSerialized);
            } 
            $o = json_decode($o);
            foreach($o->transaction_details as $key => $val){
            /*echo "<pre>"; print_r($val->status); die;*/
                if(($val->status=="success")&&($val->unmappedstatus=="captured")){
                    if($order->order_status == "Payment Failed" || $order->order_status == "New"){
                        Order::where(['id' => $order->id])->update(['order_status' => 'Payment Captured']);
                    }                   
                }else{
                    if($order->order_status == "Payment Captured" || $order->order_status == "New"){
                        Order::where(['id' => $order->id])->update(['order_status' => 'Payment Failed']);
                    }
                }
            }
        }
        
        echo "cron job run successfully"; die;

    }    
}
