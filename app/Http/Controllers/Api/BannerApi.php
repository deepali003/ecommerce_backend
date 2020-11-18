<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Banner;
class BannerApi extends Controller
{
    public function index()
    {
        $bannerCount = Banner::where('status',1)->count();
        $fetchBanner = Banner::where('status',1)->get();
        return response()->json($fetchBanner);

    }
}
