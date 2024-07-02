<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShippingResiStatus extends Model
{
    use HasFactory;

    protected $fillable = [
        'shipping_resi',
        'status',
    ];
}
