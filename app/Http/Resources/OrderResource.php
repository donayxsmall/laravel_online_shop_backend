<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use App\Http\Resources\OrderItemResource;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'address_id' => $this->address_id,
            'subtotal' => $this->subtotal,
            'shipping_cost' => $this->shipping_cost,
            'total_cost' => $this->total_cost,
            'status' => $this->status,
            'payment_method' => $this->payment_method,
            'payment_va_name' => $this->payment_va_name,
            'payment_va_number' => $this->payment_va_number,
            'payment_ewallet' => $this->payment_ewallet,
            'shipping_service' => $this->shipping_service,
            'shipping_resi' => $this->shipping_resi,
            'transaction_number' => $this->transaction_number,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'bank_fee' => $this->bank_fee,
            'order_items' => OrderItemResource::collection($this->order_items),
        ];
    }
}
