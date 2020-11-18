<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Category;

class CategoryApi extends Controller
{
   
    public function index()
    {
        $fetchCategory = Category::where('parent_id',0)->where('status',1)->get();
        return response()->json($fetchCategory);
    }


    public function horizontalCategory()
    {
        $horizontalCategory =  Category::where('parent_id',0)->where('status',1)->limit(6)->inRandomOrder()->get();
        return response()->json($horizontalCategory);
    }

    public function gridCategory()
    {
        $fetchCategories = Category::where('parent_id','!=',0)->where('status',1)->limit(4)->inRandomOrder()->get();
        return response()->json($fetchCategories);
    }
     public function allGridcategory()
    {
        $fetchCategories = Category::where('status',1)->inRandomOrder()->get();
        return response()->json($fetchCategories);
    }
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
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
