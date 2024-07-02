<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Helpers\ApiResponse;
use App\Models\BannerSlide;
use App\Http\Resources\BannerSliderResource;
use Illuminate\Http\Request;

class BannerSliderController extends Controller
{
    public function index(Request $request){
        try {
            $banner = BannerSliderResource::collection(
                BannerSlide::where(['is_active' => 1])->get()
            );

            return ApiResponse::success('Data Found',['banner' => $banner]);

        } catch (\Exception $e) {
            throw $e;
        }
    }
}
