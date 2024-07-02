<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Mail\MyTestEmail;
use Illuminate\Support\Str;
use App\Helpers\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Intervention\Image\Facades\Image;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Storage;
use App\Http\Resources\UpdateProfileResource;
use App\Http\Requests\Api\auth\LoginUserRequest;
use App\Http\Requests\Api\auth\RegisterUserRequest;

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

    // public function getUser(Request $request)
    // {
    //     try {
    //         $user = $request->user();
    //         return ApiResponse::success('Get User Successfully',['user' => $user]);
    //     } catch (\Exception $e) {
    //         throw $e;
    //     }
    // }

    public function register(RegisterUserRequest $request)
    {
        try {
            // Validate
            $validated = $request->all();

            // password encryption
            $validated['password'] = Hash::make($validated['password']);
            // dd($validated);
            $user = User::create($validated);
            // $token = $user->createToken('auth_token')->plainTextToken;
            // $user['access_token'] = $token;

            // send email verification
            $this->sendVerificationEmail($user);

            return ApiResponse::success('Register Succesfully , Please Check email for verfication', ['user' => $user]);
        } catch (\Exception $e) {
            dd($e);
            throw $e;
        }
    }

    public function login(LoginUserRequest $request)
    {
        try {
            // Validate
            $validated = $request->all();

            $user = User::where(['email' => $validated['email'] , 'google_id' => null])->first();
            if (!$user) return ApiResponse::error('Email Not Found');

            // Email Validation
            if($user->email_verified_at == null) return ApiResponse::error('Email Not Validation, Please Validation Email');

            $checkPassword = Hash::check($validated['password'], $user->password);

            if (!$checkPassword) {
                return ApiResponse::error('Password Wrong , Please check again');
            }

            $token = $user->createToken('auth_token')->plainTextToken;
            $user['access_token'] = $token;

            return ApiResponse::success('Login Succesfully', ['user' => $user]);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function googleLogin(Request $request){
        try {
            $googleId = $request->google_id;
            $user = User::where('google_id',$googleId)->first();

            if(!$user){
                $user = User::create([
                    'name' => $request->name,
                    'email' => $request->email,
                    'google_id' => $googleId,
                    'password' => "google"
                ]);
            }

            $token = $user->createToken('auth_token')->plainTextToken;
            $user['access_token'] = $token;

            return ApiResponse::success('Login Succesfully', ['user' => $user]);
        } catch (\Exception $e) {
            dd($e);
            throw $e;
        }
    }

    public function logout(Request $request)
    {
        try {
            $user = $request->user();
            $user->tokens()->delete();
            return ApiResponse::success('Logout Succesfully', ['user' => $user]);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function refresh(Request $request)
    {
        try {
            $user = $request->user();
            $user->tokens()->delete();

            $token = $user->createToken('auth_token')->plainTextToken;
            $user['access_token'] = $token;

            return ApiResponse::success('Refresh Token', ['user' => $user]);
            // $request->user()->currentAccessToken()->delete();
            // return ApiResponse::success('Logout Success');
        } catch (\Exception $e) {
            throw $e;
        }
    }

    //update fcm id
    public function updateFcmId(Request $request)
    {
        try {
            // Validate the request...
            $validated = $request->validate([
                'fcm_id' => 'required',
            ]);

            $user = $request->user();
            $user->fcm_id = $validated['fcm_id'];
            $user->save();


            return ApiResponse::success('Update Fcm Token', ['user' => $user]);

        } catch (\Exception $e) {
            throw $e;
        }

    }

    public function verifyEmail(Request $request, $id)
    {
        try {
            $message = "Not Authenticated";
            $user = User::findOrFail($id);

            // Validasi Email Link
            if(!$request->hasValidSignature()){
                $message = "Invalid or expired verification link.";
            }else if ($request->token !== $user->email_verification_token) {
                $message = "Invalid verification token";
            }else{
                if (!$user->hasVerifiedEmail()) {
                    $user->markEmailAsVerified();
                    $message = "Email verified successfully.";
                    $user->email_verification_token = null;
                    $user->save();
                    // return response()->json(['message' => 'Email verified.'], 200);
                }else{
                    $message = "Email already verified";
                }
            }

            return view('mail.email-verified',compact('message'));
        } catch (\Exception $e) {
            // throw $e;
            $message = 'Not Authenticated';
            return view('mail.email-verified',compact('message'));
        }


    }

    protected function sendVerificationEmail($user)
    {
        $token = Str::random(60);
        $user->email_verification_token = $token;
        $user->save();

        $verificationUrl = URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(60),
            ['id' => $user->id, 'token' => $token]
        );

        Mail::send('mail.verify', ['url' => $verificationUrl], function ($message) use ($user) {
            // $email = 'donayfreelance@gmail.com';
            $email = $user->email;
            $message->to($email)->subject('Verify your email address');
        });
    }

    public function updateProfile(Request $request){
        try {

            // if ($request->hasFile('photo')) {
            //     $request->validate([
            //         'photo' => 'image|mimes:jpeg,png,jpg,gif|max:10240',
            //     ]);
            // }

            // dd($request->file('photo'));



            if ($request->hasFile('photo')) {

                $request->validate([
                    'photo' => 'image|mimes:jpeg,png,jpg,gif|max:10240',
                ]);

                // Get the file from the request
                $file = $request->file('photo');

                $folder = 'photo_profile/'.str_replace(' ', '', $request->email);


                // Generate a unique filename
                $filename = time() . '.' . $file->getClientOriginalExtension();

                 // Compress the image
                $image = Image::make($file);
                $image->resize(800, null, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                });

                // Save the file
                // $path = $file->storeAs($folder, $filename, 'public');

                // Save the compressed image to the public disk
                $path = $folder . '/' . $filename;
                Storage::disk('public')->put($path, (string) $image->encode());


                // return ApiResponse::success('Update Profile Succesfully',['user' => $path]);


                $user = auth()->user();
                $user->name = $request->name;
                $user->phone = $request->phone;
                $user->photo = $path;
                $user->save();

                return ApiResponse::success('Update Profile Succesfully', ['user' => new UpdateProfileResource($user)]);
            }else{
                $user = auth()->user();
                $user->name = $request->name;
                $user->phone = $request->phone;
                $user->save();

                return ApiResponse::success('Update Profile Succesfully', ['user' => new UpdateProfileResource($user)]);
            }

            // dd($request);

        } catch (\Exception $e) {
            // dd($e);
            throw $e;
        }
    }

    public function testEmail()
    {

        try {
            $id = "1";
            $user = User::findOrFail($id);
            // dd($user);
            $this->sendVerificationEmail($user);


        } catch (\Exception $e) {
            dd($e);
            throw $e;
        }


    }

}
