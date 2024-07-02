<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Models\Order;
use App\Models\Product;
use App\Models\OrderItem;
use App\Helpers\ApiResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Exceptions\MidtransException;
use App\Http\Resources\OrderResource;
use Illuminate\Support\Facades\Storage;
use App\Services\Midtrans\CreateVAService;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;
use App\Http\Requests\Api\order\OrderRequest;

class OrderController extends Controller
{
    public function order(OrderRequest $request)
    {
        try {
           // Validate
           $validated = $request->all();

           $subtotal = 0;
           foreach ($request->items as $item) {
             $product = Product::find($item['product_id']);
             $subtotal += $product->price * $item['quantity'];
           }

        //    dd($request);

        //    dd($subtotal);

           // create order
           $order = Order::create([
              'user_id' => $request->user()->id,
              'address_id' => $request->address_id,
              'subtotal' => $subtotal,
              'shipping_cost' => $request->shipping_cost,
              'bank_fee' => $request->bank_fee,
              'total_cost' => $subtotal + $request->shipping_cost + $request->bank_fee,
              'status' => 'pending',
              'payment_method' => $request->payment_method,
              'shipping_service' => $request->shipping_service,
              'shipping_service_type' => $request->shipping_service_type,
              'etd_shipping' => $request->etd_shipping,
              'transaction_number' => 'TRX' . rand(100000,999999),
           ]);

           // if payment_va_name and payment_va_number is not null
           if ($request->payment_va_name){
            $order->update([
                'payment_va_name' => $request->payment_va_name,
            ]);
           }


           // create order items
           foreach ($request->items as $item) {
             OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item['product_id'],
                'quantity' => $item['quantity'],
             ]);
           }

        //    return ApiResponse::success('Order creeated Succesfully', ['order' => $order->load('user','orderItems')]);

           // request ke midtrans

           $midtrans = new CreateVAService($order->load('user','orderItems'));
           $apiResponse = $midtrans->getVA();


           if($request->payment_va_name == "mandiri"){
                $order->payment_va_number = $apiResponse->bill_key;
           }else if($request->payment_va_name == "permata"){
                $order->payment_va_number = $apiResponse->permata_va_number;
           }else{
                $order->payment_va_number = $apiResponse->va_numbers[0]->va_number;
           }

           $order->save();


            // dd($order);

           return ApiResponse::success('Order creeated Succesfully', ['order' => $order]);

        } catch (\Exception $e) {
            // dd($e);
            return ApiResponse::error('Transaction Failed');
        }
    }

    public function checkStatusOrder($id){
        try {
            $order = Order::find($id);
            if(!$order){
                return ApiResponse::error('Order Not Found');
            }

            return ApiResponse::success('Check Status Successfully',['status' => $order->status]);

        } catch (\Exception $e) {
            //throw $th;
            return ApiResponse::error('Check Status Failed');
        }

    }

    // Get Order By ID
    public function getOrderById($id){

        try {
            $order = Order::with('orderItems.product')->find($id);
            $order->load('user', 'address');
            if(!$order){
                return ApiResponse::error('Get Order Not Found');
            }

            // Transform the order items' product images
            $order->orderItems->transform(function ($orderItem) {
                $orderItem->product->image = asset(Storage::url($orderItem->product->image));
                return $orderItem;
            });

            return ApiResponse::success('Get Order Successfully',['order_detail' => $order]);
        } catch (\Exception $e) {
            // dd($e);
            return ApiResponse::error('Get Order Failed');
        }

    }

    // public function getOrderByUser(Request $request){
    //     try {
    //         $orders = Order::where(['user_id' => $request->user()->id])->orderBy('created_at','desc')->get();

    //         return ApiResponse::success('Get Order Successfully',['orders' => $orders]);
    //     } catch (\Exception $e) {
    //         return ApiResponse::error('Get Order Failed');
    //     }
    // }

    public function getOrderByUser(Request $request){
        try {
            // $orders = Order::where(['user_id' => $request->user()->id])->orderBy('created_at','desc')->get();

            $orders = Order::with('orderItems.product')->where(['user_id' => $request->user()->id ])->orderBy('created_at','desc')->get();
            $orders->load('user', 'address');

            // $user = $orders->load('user');
            // $address = $orders->load('address');

            if(!$orders){
                return ApiResponse::error('Get Order Not Found');
            }

            foreach ($orders as $order) {
                foreach ($order->orderItems as $orderItem) {
                    if (isset($orderItem->product->image)) {
                        // Cek apakah 'image' adalah URL lengkap atau hanya path relatif
                        if (!filter_var($orderItem->product->image, FILTER_VALIDATE_URL)) {
                            $orderItem->product->image = asset(Storage::url($orderItem->product->image));
                        }
                    }
                }
            }

            return ApiResponse::success('Get Order Successfully',['order_detail' => $orders]);
        } catch (\Exception $e) {
            dd($e);
            return ApiResponse::error('Get Order Failed');
        }
    }

    public function testNotif(Request $request){

        try {

            // dd($request->user_id);
            $this->sendNotificationToUser($request->user_id,'asdasd');

            // return $bb;
        } catch (\Exception $e) {
            dd($e);
        }


    }

    public function sendNotificationToUser($userId,$message){
        try {

            // dd($userId);
            $user = User::find($userId);
            $token = $user->fcm_id;

            // dd($token);

            if($token != null){
                $messaging = app('firebase.messaging');
                $notification = Notification::create('Paket Dikirim', $message);

                $message = CloudMessage::withTarget('token',$token)
                ->withNotification($notification);
                $messaging->send($message);

            }
        } catch (\Throwable $e) {
            dd($e);
        }
    }



}




