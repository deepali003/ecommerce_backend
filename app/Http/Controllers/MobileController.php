<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Mobile;
class MobileController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.mobile.add_mobile');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if($request->isMethod('post')){
            $fetchmobile = Mobile::get()->count();
            if($fetchmobile < 1){
                $data = $request->all();
                if(empty($data['status'])){
                    $status='0';
                }else{
                    $status='1';
                }
                if(empty($data['mobile_no'])){
                    $data['mobile_no'] = "";    
                }
    
                $mobile = new Mobile;
                $mobile->mobile_no = $data['mobile_no'];
                $mobile->status = $status;
                $mobile->save();
                return redirect()->back()->with('flash_message_success','Mobile no. has been added successfully');
            }
            else{
                return redirect()->back()->with('flash_message_error','You can add mobile no. only once.');
            }
           
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show()
    {

        $fetchmobile = Mobile::get();
        return view('admin.mobile.view_mobile')->with(compact('fetchmobile'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $fetchmobile = Mobile::where('id',$id)->get()->first();
        return view('admin.mobile.edit_mobile')->with(compact('fetchmobile'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if($request->isMethod('post')){
            $data = $request->all();
            /*echo "<pre>"; print_r($data); */

            if(empty($data['status'])){
                $status='0';
            }else{
                $status='1';
            }
            if(empty($data['mobile_no'])){
                $data['mobile_no'] = "";    
            }

            $updatemobile  = Mobile::where('id',$id)->update(['mobile_no'=>$data['mobile_no'],'status'=>$status]);
            return redirect()->back()->with('flash_message_success','Mobile no. has been updated successfully!');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // Mobile::where(['id'=>$id])->delete();
        // return redirect()->back()->with('flash_message_success', 'Mobile no. has been deleted successfully');
    }
}
