<?php

namespace App\Exceptions;

use Exception;

class MidtransException extends Exception
{
    public function render($request)
    {
        return response()->json([
            'status' => 'error',
            'message' => 'Error saat melakukan pembayaran',
            'error' => $this->getMessage()
        ]);
    }
}
