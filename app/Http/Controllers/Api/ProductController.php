<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Helpers\ApiResponse;
use App\Http\Resources\ProductResource;

class ProductController extends Controller
{
    public function index(Request $request){
        try {
            $products = Product::when($request->category_id,function($query) use ($request) {
                return $query->where('category_id',$request->category_id);
            })->paginate(10);

            $products = ProductResource::collection($products);

            return ApiResponse::successPagination('Data Found',$products , 'product');
        } catch (\Exception $e) {
            throw $e;
        }
    }
}
