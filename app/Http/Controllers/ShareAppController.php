<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ShareApp;
class ShareAppController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.shareapp.add-app');
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
            $fetchmobile = ShareApp::get()->count();
            if($fetchmobile < 1){
                $data = $request->all();
                if(empty($data['status'])){
                    $status='0';
                }else{
                    $status='1';
                }
                if(empty($data['subject'])){
                    $data['subject'] = "";    
                }
                if(empty($data['body'])){
                    $data['body'] = "";    
                }
                $app = new ShareApp;
                $app->subject = $data['subject'];
                $app->body = $data['body'];
                $app->status = $status;
                $app->save();
                return redirect()->back()->with('flash_message_success','Data has been added successfully');
            }
            else{
                return redirect()->back()->with('flash_message_error','You can add data only once.');
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
        $fetchApps= ShareApp::get();
        return view('admin.shareapp.manageapp')->with(compact('fetchApps'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $fetchapp = ShareApp::where('id',$id)->get()->first();
        return view('admin.shareapp.editapp')->with(compact('fetchapp'));
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
            if(empty($data['subject'])){
                $data['subject'] = "";    
            }
            if(empty($data['body'])){
                $data['body'] = "";    
            }
            $updatemobile  = ShareApp::where('id',$id)->update(['subject'=>$data['subject'],'body'=>$data['body'],'status'=>$status]);
            return redirect()->back()->with('flash_message_success','Data has been updated successfully!');
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
        //
    }
}
