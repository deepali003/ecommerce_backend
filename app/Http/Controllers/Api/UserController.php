<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use Twilio\Jwt\ClientToken;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;
use Auth;

class UserController extends Controller
{
    protected $code, $smsVerifcation;
    public $successStatus = 200;

    public function __construct()
    {
        $this->smsVerifcation = new \App\User();
    }

    public function register(Request $request)
    {
        $mobile = $request->mobile;
        $amobile = User::where('mobile', $mobile)->count();

        if ($amobile > 0) {
            $reponse['success'] =  false;
            $reponse['message'] =  "Mobile no. already in use";
        } else {
            $user = new User;
            $user->name = $request->name;
            $user->mobile = $request->mobile;
            $user->password = bcrypt($request->password);
            $user->remember_token = sha1(time());
            $user->save();

            $fetchuserinfo = User::where('mobile', $user->mobile)->get()->first();
            $reponse['remember_token'] =  $user->createToken('Ecommerce')->accessToken;
            $reponse['userid'] =  $fetchuserinfo->userid;
            $reponse['name'] =  $fetchuserinfo->name;
            $reponse['mobile'] =  $fetchuserinfo->mobile;
            $reponse['email'] =  $fetchuserinfo->email;
            $reponse['success'] =  true;
            $reponse['message'] =  "Registration successfull";
        }

        return response()->json($reponse);
    }

    public function login()
    {
        if (Auth::attempt(['mobile' => request('mobile'), 'password' => request('password')])) {
            $user = Auth::user();
            $updateUserStatus = User::where('userid', $user->userid)->update(['status' => 1]);
            $reponse['token'] =  $user->remember_token;
            $reponse['userid'] = $user->userid;
            $reponse['name'] = $user->name;
            $reponse['email'] = $user->email;
            $reponse['mobile'] = $user->mobile;
            $reponse['address'] = $user->address;
            $reponse['city'] = $user->city;
            $reponse['state'] = $user->state;
            $reponse['country'] = $user->country;
            $reponse['pincode'] = $user->pincode;
            $reponse['status'] = $user->status;
            $reponse['success'] =  true;
            $reponse['message'] =  "Login Successfull";
            // return response()->json(['success' => $success], $this-> successStatus); 
        } else {
            $reponse['success'] =  false;
            $reponse['message'] =  "Wrong mobile or password!! Please try again";
            // return response()->json(['error'=>'Wrong email or password!! Please try again'], 401); 
        }
        return response()->json($reponse);
    }

    public function splashlogin($userid)
    {
        $fetchloginstatus = User::where('userid', $userid)->get()->first();
        if ($fetchloginstatus->status == 1) {
            $reponse['token'] =  $fetchloginstatus->remember_token;
            $reponse['userid'] = $fetchloginstatus->userid;
            $reponse['name'] = $fetchloginstatus->name;
            $reponse['email'] = $fetchloginstatus->email;
            $reponse['mobile'] = $fetchloginstatus->mobile;
            $reponse['address'] = $fetchloginstatus->address;
            $reponse['city'] = $fetchloginstatus->city;
            $reponse['state'] = $fetchloginstatus->state;
            $reponse['country'] = $fetchloginstatus->country;
            $reponse['pincode'] = $fetchloginstatus->pincode;
            $reponse['status'] = $fetchloginstatus->status;
            $reponse['success'] =  true;
            $reponse['message'] =  "Login Successfull";
        } else {
            $reponse['success'] =  true;
            $reponse['message'] =  "Something went wrong!!";
        }
        return response()->json($reponse);
    }

    public function details()
    {
        $user = Auth::user();
        $reponse['name'] = $user->name;
        $reponse['email'] = $user->email;
        $reponse['mobile'] = $user->mobile;
        $reponse['address'] = $user->address;
        $reponse['city'] = $user->city;
        $reponse['state'] = $user->state;
        $reponse['country'] = $user->country;
        $reponse['pincode'] = $user->pincode;
        $reponse['success'] = true;
        $reponse['message'] =  "Login Successfull";
        // return response()->json(['success' => $user], $this-> successStatus); 
        return response()->json($reponse);
    }

    public function updateuserinfo(Request $request)
    {
        dd($request);
    }


    //     public function sendSms($request)
    //     {

    //         $accountSid = config('app.twilio')['TWILIO_ACCOUNT_SID'];
    //         $authToken = config('app.twilio')['TWILIO_AUTH_TOKEN']; 
    //         try
    //         {
    //             $client = new Client(['auth' => [$accountSid, $authToken]]);
    //             $result = $client->post('https://api.twilio.com/2010-04-01/Accounts/'.$accountSid.'/Messages.json',
    //             ['form_params' => [
    //             'Body' => 'CODE: '. $request->code, //set message body
    //             'To' => $request->mobile,
    //             'From' => '+14158140513' //we get this number from twilio
    //         ]]);dd($result);
    //              return $result;
    //         }
    //         catch (Exception $e)
    //         {
    //             echo "Error: " . $e->getMessage();
    //         }
    //     }
    //     public function verifyContact(Request $request)
    //     {
    //         $smsVerifcation = 
    //         $this->smsVerifcation::where('mobile','=', $request->mobile)->latest()->first(); //show the latest if there are multiple
    //         if($request->code == $smsVerifcation->code)
    //         {
    //             $request["status"] = 'verified';
    //             return $smsVerifcation->updateModel($request);
    //             $msg["message"] = "verified";
    //             return $msg;
    //         }
    //         else
    //         {
    //         $msg["message"] = "not verified";
    //         return $msg;
    //         }
    //    }

}
