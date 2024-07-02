<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Order;
use Illuminate\Http\Request;
use App\Helpers\ResponseHelper;
use Yajra\DataTables\Facades\DataTables;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;
use App\Http\Requests\order\UpdateOrderRequest;

class OrderController extends Controller
{
    // index
    public function index(Request $request){
        if ($request->ajax()) {
            // $order = Order::query();
            $order = Order::get();
            // $order = Order::query()->orderBy('created_at', 'desc');

            return DataTables::of($order)
                ->addIndexColumn()
                ->addColumn('transaction_date', function ($order) {
                    return date('Y-m-d',strtotime($order->created_at));
                })
                ->addColumn('total_cost', function ($order) {
                    return number_format($order->total_cost);
                })
                ->addColumn('action', function ($row) {
                    $actionBtn = '<a href="javascript:void(0)" data-id="' . $row->id . '" class="edit btn btn-info btn-icon btn-sm m-1"><i class="fas fa-edit"></i> Edit</a>
                    <a href="javascript:void(0)" data-id="' . $row->id . '" class="delete btn btn-danger btn-icon btn-sm m-1"><i class="fas fa-times"></i> Delete</a>
                    ';
                    return $actionBtn;
                })
                ->rawColumns(['action'])
                ->toJson();

        }

        return view('pages.order.index-datatables');
    }

    // show
    public function show($id){

    }

    // edit
    public function edit($id){
        $order = Order::with('orderItems.product')->find($id);
        $order->load('user', 'address');
        $listStatus = [
                        "pending" => "pending" ,
                        "paid" => "paid" ,
                        "on_delivery" => "on_delivery",
                        "delivered" => "delivered",
                        "expired" => "expired",
                        "cancelled" => "cancelled",
                    ];

        return view('pages.order.edit',compact('order','listStatus'));
    }

    public function update(UpdateOrderRequest $request, Order $order)
    {
        try {
            $data = $request->all();
            $data['total_cost'] = str_replace(',', '', $data['total_cost']);

            $order->update($data);

            // send notification to user
            if($request->status == 'on_delivery'){
                $this->sendNotificationToUser($order->first()->user_id,
                'Order No '.$order->transaction_number.''.PHP_EOL.
                'Paket Dikirim dengan no resi '.$order->shipping_resi
            );
            }


            return  redirect()->route('order.index')->with('success', 'Order succesfully updated');
        } catch (\Exception $e) {
            dd($e);
            return redirect()->back()->with('error', 'Failed to update order. Please try again.');
        }

    }

    public function destroy(Order $order)
    {
        try {

            $order->delete();
            // return redirect()->route('product.index')->with('success', 'Product successfully deleted');
            return ResponseHelper::message('success', 'Order successfully deleted');
        } catch (\Exception $e) {
            // return redirect()->back()->with('error', 'Failed to delete Product. Please try again.');
            return ResponseHelper::message('error', 'Failed to delete Order. Please try again.');
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
            return ResponseHelper::message('error', 'Failed to Send Notif. Please try again.');
        }
    }

}
