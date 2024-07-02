<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Address extends Model
{
    use HasFactory;

    protected $table = 'addresses';

    protected $fillable = [
        'name',
        'full_address',
        'phone',
        'prov_id',
        'city_id',
        'district_id',
        'prov_name',
        'city_name',
        'district_name',
        'postal_code',
        'user_id',
        'is_default',
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }

    protected function serializeDate(\DateTimeInterface $date)
    {
        return Carbon::instance($date)->setTimezone('Asia/Jakarta')->format('Y-m-d H:i:s');
    }
}
