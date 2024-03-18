<?php
namespace App\Helpers;

class ResponseHelper
{
    public static function message($status, $message)
    {
        return response()->json([
            'icon' => $status == 'success' ? 'success' : 'error',
            'title' => ucfirst($status) . '!',
            'message' => $message
        ], $status == 'success' ? 200 : 400);
    }
}
