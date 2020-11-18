<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use Session;
use App\Category;
use Image;
class CategoryController extends Controller
{
    public function addCategory(Request $request){
        if(Session::get('adminDetails')['categories_access']==0){
            return redirect('/admin/dashboard')->with('flash_message_error','You have no access for this module');
        }
    	if($request->isMethod('post')){
    		$data = $request->all();
    		//echo "<pre>"; print_r($data); die;

            if(empty($data['status'])){
                $status='0';
            }else{
                $status='1';
            }
            if(empty($data['meta_title'])){
                $data['meta_title'] = "";    
            }
            if(empty($data['meta_description'])){
                $data['meta_description'] = "";    
            }
            if(empty($data['meta_keywords'])){
                $data['meta_keywords'] = "";    
            }
    		$category = new Category;
    		$category->name = $data['category_name'];
            $category->parent_id = $data['parent_id'];
            $category->description = $data['description'];
           
    		// $category->url = $data['url'];
            // $category->meta_title = $data['meta_title'];
            // $category->meta_description = $data['meta_description'];
            // $category->meta_keywords = $data['meta_keywords'];

            // Upload Image
            if($request->hasFile('category_image')){
            	$image_tmp = $request->file('category_image');
                if ($image_tmp->isValid()) {
                    // Upload Images after Resize
                    $extension = $image_tmp->getClientOriginalExtension();
	                $fileName = rand(111,99999).'.'.$extension;
                    $large_image_path = 'images/backend_images/category/large'.'/'.$fileName;
                    $medium_image_path = 'images/backend_images/category/medium'.'/'.$fileName;  
                    $small_image_path = 'images/backend_images/category/small'.'/'.$fileName;  

	                Image::make($image_tmp)->save($large_image_path);
 					Image::make($image_tmp)->resize(600, 600)->save($medium_image_path);
     				Image::make($image_tmp)->resize(300, 300)->save($small_image_path);
                    $images = HelperController::GETUrl('ImageName');
     				$category->category_image = $images. '/category/large'.'/'.$fileName; 

                }
            }
            // $category->url = '/images/backend_images/category/large'.'/'.$fileName;
            $category->status = $status;
    		$category->save();
    		return redirect()->back()->with('flash_message_success', 'Category has been added successfully');
    	}

        $levels = Category::where(['parent_id'=>0])->get();
    	return view('admin.categories.add_category')->with(compact('levels'));
    }

    public function editCategory(Request $request,$id=null){
        if(Session::get('adminDetails')['categories_access']==0){
            return redirect('/admin/dashboard')->with('flash_message_error','You have no access for this module');
        }
        if($request->isMethod('post')){
            $data = $request->all();
            /*echo "<pre>"; print_r($data); */

            if(empty($data['status'])){
                $status='0';
            }else{
                $status='1';
            }
            // if(empty($data['meta_title'])){
            //     $data['meta_title'] = "";    
            // }
            // if(empty($data['meta_description'])){
            //     $data['meta_description'] = "";    
            // }
            // if(empty($data['meta_keywords'])){
            //     $data['meta_keywords'] = "";    
            // }
            	// Upload Image
                if($request->hasFile('category_image')){
                    $image_tmp = $request->file('category_image');
                    if ($image_tmp->isValid()) {
                        // Upload Images after Resize
                        $extension = $image_tmp->getClientOriginalExtension();
                        $filename = rand(111,99999).'.'.$extension; 
                        $images = HelperController::GETUrl('ImageName');
                        $fileName = $images.'/category/large'.'/'.$filename;
                        $large_image_path = 'images/backend_images/category/large'.'/'.$filename;
                        $medium_image_path = 'images/backend_images/category/medium'.'/'.$filename;  
                        $small_image_path = 'images/backend_images/category/small'.'/'.$filename;  
    
                        Image::make($image_tmp)->save($large_image_path);
                        Image::make($image_tmp)->resize(600, 600)->save($medium_image_path);
                        Image::make($image_tmp)->resize(300, 300)->save($small_image_path);
                            
                    }
                }else if(!empty($data['current_image'])){
                    $fileName = $data['current_image'];
                }else{
                    $fileName = '';
                }
            // Category::where(['id'=>$id])->update(['status'=>$status,'name'=>$data['category_name'],'parent_id'=>$data['parent_id'],'description'=>$data['description'],'url'=>$data['url'],'meta_title'=>$data['meta_title'],'meta_description'=>$data['meta_description'],'meta_keywords'=>$data['meta_keywords']]);
            Category::where(['id'=>$id])->update(['status'=>$status,'name'=>$data['category_name'],'parent_id'=>$data['parent_id'],'description'=>$data['description'],'category_image'=>$fileName]);
            return redirect()->back()->with('flash_message_success', 'Category has been updated successfully');
        }

        $categoryDetails = Category::where(['id'=>$id])->first();
        $levels = Category::where(['parent_id'=>0])->get();
        return view('admin.categories.edit_category')->with(compact('categoryDetails','levels'));
    }

    public function deleteCategory($id = null){
        if(Session::get('adminDetails')['categories_access']==0){
            return redirect('/admin/dashboard')->with('flash_message_error','You have no access for this module');
        }
        Category::where(['id'=>$id])->delete();
        return redirect()->back()->with('flash_message_success', 'Category has been deleted successfully');
    }

    public function viewCategories(){ 
        if(Session::get('adminDetails')['categories_view_access']==0){
            return redirect('/admin/dashboard')->with('flash_message_error','You have no access for this module');
        }
        $categories = category::get();
        return view('admin.categories.view_categories')->with(compact('categories'));
    }
}
