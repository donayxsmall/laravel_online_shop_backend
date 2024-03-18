<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Helpers\ApiResponse;

class ProductController extends Controller
{
    public function index(Request $request){
        try {
            $products = Product::when($request->category_id,function($query) use ($request) {
                return $query->where('category_id',$request->category_id);
            })->paginate(10);

            // $response = [
            //     'status' => 'success',
            //     'message' => 'Data Found',
            //     'current_page' => $products->currentPage(),
            //     'product' => $products->items(),
            //     'first_page_url' => $products->url(1),
            //     'from' => $products->firstItem(),
            //     'last_page' => $products->lastPage(),
            //     'last_page_url' => $products->url($products->lastPage()),
            //     'links' => $products->links(),
            //     'next_page_url' => $products->nextPageUrl(),
            //     'path' => $products->url($products->currentPage()),
            //     'per_page' => $products->perPage(),
            //     'prev_page_url' => $products->previousPageUrl(),
            //     'to' => $products->lastItem(),
            //     'total' => $products->total(),
            // ];

            // return response()->json($response);

            return ApiResponse::successPagination('Data Found',$products , 'product');
        } catch (\Exception $e) {
            throw $e;
        }
    }
}
