<?php

namespace App\Http\Controllers;

use App\Models\BannerSlide;
use Illuminate\Http\Request;
use App\Helpers\ResponseHelper;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;
use App\Http\Requests\banner\StoreBannerSliderRequest;
use App\Http\Requests\banner\UpdateBannerSliderRequest;

class BannerSliderController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = BannerSlide::query();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('image', function ($banner) {
                    if ($banner->image != "") {
                        $imageView = '<img src="' . asset('storage/' . $banner->image) . '" width="100" height="100" class="gallery-item" />';
                    } else {
                        $imageView = '';
                    }

                    return $imageView;
                })
                ->addColumn('action', function ($row) {
                    $actionBtn = '<a href="javascript:void(0)" data-id="' . $row->id . '" class="edit btn btn-info btn-icon btn-sm m-1"><i class="fas fa-edit"></i> Edit</a> <a href="javascript:void(0)" data-id="' . $row->id . '" class="delete btn btn-danger btn-icon btn-sm m-1"><i class="fas fa-times"></i> Delete</a>';
                    return $actionBtn;
                })
                ->rawColumns(['image', 'action'])
                ->toJson();
        }

        return view('pages.banner-slide.index-datatables');
    }

    public function create()
    {
        return view('pages.banner-slide.create');
    }

    public function store(StoreBannerSliderRequest $request)
    {
        try {
            $data = $request->all();

            // dd($data);
            if ($request->hasFile('image')) $data['image'] = $request->image->store('assets/banner', 'public');
            BannerSlide::create($data);
            return  redirect()->route('banner-slider.index')->with('success', 'Banner Slider succesfully created');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to create category. Please try again.');
        }
    }

    public function edit($id)
    {
        $banner = BannerSlide::findOrFail($id);

        // dd($category->name);
        return view('pages.banner-slide.edit', compact('banner'));
    }

    public function update(UpdateBannerSliderRequest $request, BannerSlide $banner_slider)
    {
        try {
            $data = $request->all();
            // dd($data);
            if ($request->hasFile('image')) {
                // Hapus gambar lama jika ada
                Storage::delete('public/' . $banner_slider->image);

                $data['image'] = $request->image->store('assets/banner', 'public');
            }
            $banner_slider->update($data);
            return redirect()->route('banner-slider.index')->with('success', 'Banner Slider successfully updated');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to update Banner Slider. Please try again.');
        }
    }

    public function destroy(BannerSlide $banner_slider)
    {
        try {
            // dd($banner_slider);
            // Hapus gambar jika ada
            Storage::delete('public/' . $banner_slider->image);

            $banner_slider->delete();
            // return redirect()->route('banner-slider.index')->with('success', 'Category successfully deleted');
            return ResponseHelper::message('success', 'Banner successfully deleted');
        } catch (\Exception $e) {
            // return redirect()->back()->with('error', 'Failed to delete category. Please try again.');
            return ResponseHelper::message('error', 'Failed to delete banner. Please try again.');
        }
    }
}
