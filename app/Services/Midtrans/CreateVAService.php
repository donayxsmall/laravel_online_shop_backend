<?php

namespace App\Services\Midtrans;

use Midtrans\CoreApi;

class CreateVAService extends Midtrans
{
    protected $order;

    public function __construct($order)
    {
        parent::__construct();

        $this->order = $order;
    }

    public function getVA()
    {

        try {

            // ITEM DETAILS
            $itemDetails = [];
            foreach ($this->order->orderItems as $orderItem) {
                $itemDetails[] = [
                    'id' => $orderItem->product->id,
                    'price' => $orderItem->product->price,
                    'quantity' => $orderItem->quantity,
                    'name' => $orderItem->product->name,
                ];
            }

            $itemDetails[] = [
                'id' => 'SHIPPING COST',
                'price' => $this->order->shipping_cost,
                'quantity' => 1,
                'name' => 'SHIPPING COST',
            ];


            $itemDetails[] = [
                'id' => 'BANK_FEE',
                'price' => $this->order->bank_fee,
                'quantity' => 1,
                'name' => 'BANK_FEE',
            ];

            // // Populate customer's billing address
            // $billing_address = array(
            //     'first_name'   => "Andri",
            //     'last_name'    => "Setiawan",
            //     'address'      => "Karet Belakang 15A, Setiabudi.",
            //     'city'         => "Jakarta",
            //     'postal_code'  => "51161",
            //     'phone'        => "081322311801",
            //     'country_code' => 'IDN'
            // );

            // // Populate customer's shipping address
            // $shipping_address = array(
            //     'first_name'   => "John",
            //     'last_name'    => "Watson",
            //     'address'      => "Bakerstreet 221B.",
            //     'city'         => "Jakarta",
            //     'postal_code'  => "51162",
            //     'phone'        => "081322311801",
            //     'country_code' => 'IDN'
            // );

            // Customer Details
            $customer_details = array(
                'first_name'       => $this->order->user->name,
                // 'last_name'        => "",
                'email'            => $this->order->user->email,
                // 'phone'            => "",
                // 'billing_address'  => "",
                // 'shipping_address' => ""
            );


            $paymentVaName = $this->order->payment_va_name;

            if(in_array($paymentVaName,['bca','bni','bri','cimb'])){
                $params = [
                    'payment_type' => 'bank_transfer',
                    'transaction_details' => [
                        'order_id' => $this->order->transaction_number,
                        'gross_amount' => $this->order->total_cost,
                    ],
                    'item_details' => $itemDetails,
                    'customer_details' => $customer_details,
                    'bank_transfer' => [
                        'bank' => $this->order->payment_va_name
                    ]
                ];
            }else if($paymentVaName == "mandiri"){
                $params = [
                    'payment_type' => 'echannel',
                    'transaction_details' => [
                        'order_id' => $this->order->transaction_number,
                        'gross_amount' => $this->order->total_cost,
                    ],
                    'item_details' => $itemDetails,
                    'customer_details' => $customer_details,
                    'echannel' => [
                        'bill_info1' => 'Payment',
                        'bill_info2' => 'Online purchase'
                    ]
                ];
            }else if($paymentVaName == "permata"){
                $params = [
                    'payment_type' => 'permata',
                    'transaction_details' => [
                        'order_id' => $this->order->transaction_number,
                        'gross_amount' => $this->order->total_cost,
                    ],
                    'item_details' => $itemDetails,
                    'customer_details' => $customer_details,
                ];
            }

            // dd($params);



            $response = CoreApi::charge($params);

            return $response;

            // $charge = CoreApi::charge($params);
            // if(!$charge){
            //     return ['code' => 0, 'message' => 'Transaction Failed'];
            // }

            // return ['code' => 1, 'message' => 'Success', 'result' => $charge];



        }catch(\Exception $e){
            // return ['code' => 0, 'message' => 'Transaction Failed'];
            // throw new MidtransException($e->getMessage());
            // dd($e);
            throw $e;
        }


    }
}
