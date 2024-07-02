<?php

namespace App\Http\Controllers\Api;

use App\Models\Address;
use App\Models\BannerSlide;
use App\Helpers\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Resources\BannerSliderResource;
use App\Http\Requests\Api\address\StoreAddressRequest;
use App\Http\Requests\Api\address\UpdateAddressRequest;

class AddressController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $address = Address::where(['user_id' => $request->user()->id])->get();
            // dd($address);
            return ApiResponse::success('Data Found',['address' => $address]);
        } catch (\Exception $e) {
            // dd($e);
            throw $e;
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAddressRequest $request)
    {
        try {
            // Validate
            $validated = $request->all();
            $validated['user_id'] = auth()->user()->id;
            $address = Address::create($validated);
            return ApiResponse::success('Add Address Succesfully', ['address' => $address]);
        } catch (\Exception $e) {
            // dd($e);
            throw $e;
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAddressRequest $request, string $addressId)
    {
        try {
            $data = $request->all();
            $userId = auth()->id();

            $address = Address::where('user_id', $userId)->find($addressId);
            if(!$address){
                return ApiResponse::error('Address Not Found , Please check again');
            }

            $address->update($data);
            return ApiResponse::success('Update Address Succesfully', ['address' => $address]);

        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function setDefaultAddress(Request $request , String $addressId){
        try {
            $data = $request->all();
            $userId = auth()->id();

            $address = Address::where('user_id', $userId)->find($addressId);
            if(!$address){
                return ApiResponse::error('Address Not Found , Please check again');
            }



            //clear default address
            Address::where('user_id', $userId)->update(['is_default' => 0]);

            // dd($address);

            // set default address
            Address::where('user_id', $userId)
            ->where('id' , $addressId)
            ->update(['is_default' => 1]);


            return ApiResponse::success('Set Default Address Succesfully', ['address' => $address]);


        } catch (\Exception $th) {
            //throw $th;
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request , string $id)
    {

        try {
            $userId = auth()->id();


            // $address = Address::where(['user_id' => $userId , 'id' => $id])->find();
            $address = Address::where('user_id', $userId)->find($id);
            if(!$address){
                return ApiResponse::error('Address Not Found , Please check again');
            }

            $address->delete();
            return ApiResponse::success('Delete Address Succesfully', ['address' => $address]);

        } catch (\Exception $e) {
            dd($e);
            throw $e;
        }
    }


}
