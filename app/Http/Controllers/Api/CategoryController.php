<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Helpers\ApiResponse;
use App\Http\Resources\CategoryResource;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class CategoryController extends Controller
{
    public function index(Request $request){
        try {
            // $categories = Category::all();

            // $categories = DB::table('categories')
            // ->select('name', 'description', 'image')
            // ->get();

            // dd($categories);

            $categories = CategoryResource::collection(Category::all());


            return ApiResponse::success('Data Found',['categories' => $categories]);

        } catch (\Exception $e) {
            throw $e;
        }
    }
}
