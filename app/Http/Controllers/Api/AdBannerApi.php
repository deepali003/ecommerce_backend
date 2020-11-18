<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\AdBanner;

class AdBannerApi extends Controller
{
    public function index()
    {
        $fetchAdd = AdBanner::get();
        return response()->json($fetchAdd);
    }
}
