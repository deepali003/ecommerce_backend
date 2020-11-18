<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HelperController extends Controller
{
    public static function GETCompleteUrl($pimgName)
    {
        return "http://jatinwardhan.in/public/images/backend_images/category/large/$pimgName";
        // HelperController::GETUrl('ImageName');
    }
    
    public static function GETUrl($imgName)
    {
        return "public/images/backend_images/category/$imgName";
    }
    
     public static function GETCompleteProductUrl($pimgName)
    {
        return "http://jatinwardhan.in/public/images/backend_images/product/large/$pimgName";
        // HelperController::GETUrl('ImageName');
    }
    
    public static function GETProductUrl($imgName)
    {
        return "public/images/backend_images/product/$imgName";
    }
    
     public static function GETBannerUrl($bimgName)
    {
        return "public/images/backend_images/banner/$bimgName";
    }
     public static function GETCompleteBannerUrl($baimgName)
    {
        return "http://jatinwardhan.in/public/images/backend_images/banner/$baimgName";
    }
}
