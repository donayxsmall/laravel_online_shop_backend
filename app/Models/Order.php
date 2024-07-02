<?php

namespace App\Models;

use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'address_id',
        'subtotal',
        'shipping_cost',
        'bank_fee',
        'total_cost',
        'status',
        'payment_method',
        'payment_va_name',
        'payment_va_number',
        'payment_ewallet',
        'shipping_service',
        'shipping_service_type',
        'shipping_resi',
        'transaction_number',
        'etd_shipping',
    ];

    protected function serializeDate(\DateTimeInterface $date)
    {
        return Carbon::instance($date)->setTimezone('Asia/Jakarta')->format('Y-m-d H:i:s');
    }

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function orderItems(){
        return $this->hasMany(OrderItem::class);
    }

    public function address(){
        return $this->belongsTo(Address::class);
    }
}
