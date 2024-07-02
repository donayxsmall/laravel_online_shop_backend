<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class BannerSlide extends Model
{
    use HasFactory;

    protected $table = 'banner_slides';

    protected $fillable = [
        'name',
        'is_active',
        'image',
    ];

    protected function serializeDate(\DateTimeInterface $date)
    {
        return Carbon::instance($date)->setTimezone('Asia/Jakarta')->format('Y-m-d H:i:s');
    }
}
