<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Requests\product\StoreProductRequest;
use App\Http\Requests\product\UpdateProductRequest;
use Illuminate\Support\Facades\Storage;
use App\Helpers\ResponseHelper;
use DataTables;

class ProductController extends Controller
{

    public function indexOld(Request $request){
        $products = Product::paginate(10);

        $products = Product::when($request->input('name') , function($query,$name) {
            $query->where('name','like','%' . $name . '%');
        })
        ->orderBy('id','asc')
        ->paginate(10);

        // dd($categories);

        return view('pages.product.index', compact('products'));
    }

    public function index(Request $request){
        if($request->ajax()){
            // $data = Product::query();
            // $data->select('products.*', 'categories.name as category_name')
            // ->leftJoin('categories', 'products.category_id', '=', 'categories.id');
            // $products = $data->get();

            $products = Product::with('category')->get();

            return DataTables::of($products)
                ->addIndexColumn()
                ->addColumn('category_name', function($product) {
                    return $product->category->name;
                })
                ->addColumn('image', function ($product) {
                    if($product->image != ""){
                        $imageView = '<img src="' . asset('storage/' . $product->image) . '" width="100" height="100" class="gallery-item" />';
                    }else{
                        $imageView = '';
                    }

                    return $imageView;
                })
                ->addColumn('action', function($row) {
                    $actionBtn = '<a href="javascript:void(0)" data-id="'.$row->id.'" class="edit btn btn-info btn-icon btn-sm m-1"><i class="fas fa-edit"></i> Edit</a> <a href="javascript:void(0)" data-id="'.$row->id.'" class="delete btn btn-danger btn-icon btn-sm m-1"><i class="fas fa-times"></i> Delete</a>'
                    ;
                    return $actionBtn;
                })
                ->rawColumns(['image','action'])
                ->toJson();
        }

        return view('pages.product.index-datatables');
    }


    public function create(){
        $categories = Category::all();
        return view('pages.product.create' , compact('categories'));
    }

    public function store(StoreProductRequest $request){
        try {
            $data = $request->all();
            // $data['is_available'] = '1';
            if ($request->hasFile('image')) $data['image'] = $request->image->store('assets/product','public');
            Product::create($data);
            return  redirect()->route('product.index')->with('success', 'Product succesfully created');
        } catch (\Exception $e) {
            // dd($e);
            return redirect()->back()->with('error', 'Failed to create user. Please try again.');
        }
    }

    public function edit($id){
        $product = Product::findOrFail($id);
        $categories = Category::all();
        return view('pages.product.edit',compact('product','categories'));
    }

    public function update(UpdateProductRequest $request , Product $product){

        try {
            $data = $request->all();
            if ($request->hasFile('image')) {
                // Hapus gambar lama jika ada
                Storage::delete('public/'.$product->image);

                $data['image'] = $request->image->store('assets/product','public');
            }

            $product->update($data);
            return  redirect()->route('product.index')->with('success', 'Product succesfully updated');
        } catch (\Exception $e) {
            // dd($e);
            return redirect()->back()->with('error', 'Failed to update product. Please try again.');
        }
    }

    public function destroy(Product $product){

        try {
            // Hapus gambar jika ada
            Storage::delete('public/'.$product->image);

            $product->delete();
            // return redirect()->route('product.index')->with('success', 'Product successfully deleted');
            return ResponseHelper::message('success', 'Product successfully deleted');
        } catch (\Exception $e) {
            // return redirect()->back()->with('error', 'Failed to delete Product. Please try again.');
            return ResponseHelper::message('error', 'Failed to delete Product. Please try again.');
        }
    }
}
