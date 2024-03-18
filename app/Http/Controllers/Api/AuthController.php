<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\Api\auth\LoginUserRequest;
use App\Http\Requests\Api\auth\RegisterUserRequest;
use App\Models\User;
use App\Helpers\ApiResponse;
use Illuminate\Database\QueryException;

class AuthController extends Controller
{

    public function getUser(Request $request)
    {
        try {
            $user = $request->user();
            return ApiResponse::success('Get User Successfully', ['user' => $user]);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function register(RegisterUserRequest $request){

        try {
            // Validate
            $validated = $request->all();

            // password encryption
            $validated['password'] = Hash::make($validated['password']);
            // dd($validated);
            $user = User::create($validated);
            $token = $user->createToken('auth_token')->plainTextToken;
            $user['access_token'] = $token;

            return ApiResponse::success('Register Succesfully',['user' => $user]);

        } catch (\Exception $e) {
            throw $e;
        }

    }

    public function logout(Request $request){
        try {
            $request->user()->currentAccessToken()->delete();
            return ApiResponse::success('Logout Success');
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function login(LoginUserRequest $request){
        try {
            // Validate
            $validated = $request->all();

            $user = User::where(['email' => $validated['email']])->first();
            if(!$user) return ApiResponse::error('Email Not Found');

            $checkPassword = Hash::check($validated['password'],$user->password);

            if(!$checkPassword) {
                return ApiResponse::error('Invalid Credentials , Please check again');
            }

            $token = $user->createToken('auth_token')->plainTextToken;
            $user['access_token'] = $token;

            return ApiResponse::success('Login Succesfully',['user' => $user]);
        } catch (\Exception $e) {
            throw $e;
        }
    }


}
