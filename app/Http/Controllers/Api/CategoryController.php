<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Helpers\ApiResponse;

class CategoryController extends Controller
{
    public function index(Request $request){
        try {
            $categories = Category::all();
            return ApiResponse::success('Data Found',['categories' => $categories]);

        } catch (\Exception $e) {
            throw $e;
        }
    }
}
