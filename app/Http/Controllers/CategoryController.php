<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Requests\category\UpdateCategoryRequest;
use App\Http\Requests\category\StoreCategoryRequest;
use App\Helpers\ResponseHelper;
use Illuminate\Support\Facades\Storage;
use DataTables;

class CategoryController extends Controller
{
    public function indexOld(Request $request){
        $categories = Category::paginate(10);

        $categories = Category::when($request->input('name') , function($query,$name) {
            $query->where('name','like','%' . $name . '%');
        })
        ->orderBy('id','asc')
        ->paginate(10);

        // dd($categories);

        return view('pages.category.index', compact('categories'));
    }

    public function index(Request $request){
        if($request->ajax()){
            $data = Category::query();
            return DataTables::of($data)
                ->addIndexColumn()
                // ->addColumn('created_at', function($row) {
                //     return $row->created_at != null ? date('Y-m-d H:i:s', strtotime($row->created_at)) : "";
                //     // return $row->created_at;
                // })
                // ->addColumn('updated_at', function($row) {
                //     return $row->updated_at != null ? date('Y-m-d H:i:s', strtotime($row->updated_at)) : "";
                // })
                ->addColumn('image', function ($category) {
                    if($category->image != ""){
                        $imageView = '<img src="' . asset('storage/' . $category->image) . '" width="100" height="100" class="gallery-item" />';
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

        return view('pages.category.index-datatables');
    }




    public function create(){
        return view('pages.category.create');
    }

    public function store(StoreCategoryRequest $request){
        try {
            $data = $request->all();
            if ($request->hasFile('image')) $data['image'] = $request->image->store('assets/category','public');
            Category::create($data);
            return  redirect()->route('category.index')->with('success', 'Category succesfully created');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to create category. Please try again.');
        }
    }

    public function edit($id){
        $category = Category::findOrFail($id);

        // dd($category->name);
        return view('pages.category.edit',compact('category'));
    }

    public function update(UpdateCategoryRequest $request , Category $category){
        try {
            $data = $request->all();
            // dd($data);
            if ($request->hasFile('image')) {
                // Hapus gambar lama jika ada
                Storage::delete('public/'.$category->image);

                $data['image'] = $request->image->store('assets/category','public');
            }
            $category->update($data);
            return redirect()->route('category.index')->with('success', 'Category successfully updated');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to update Category. Please try again.');
        }
    }

    public function destroy(Category $category){
        try {
            // Hapus gambar jika ada
            Storage::delete('public/'.$category->image);

            $category->delete();
            // return redirect()->route('category.index')->with('success', 'Category successfully deleted');
            return ResponseHelper::message('success', 'Category successfully deleted');
        } catch (\Exception $e) {
            // return redirect()->back()->with('error', 'Failed to delete category. Please try again.');
            return ResponseHelper::message('error', 'Failed to delete category. Please try again.');
        }
    }
}
