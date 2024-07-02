<?php

namespace App\Exceptions;

use Throwable;
use App\Helpers\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;


trait ApiExceptionHandler
{

    protected function handleApiException($request, Throwable $exception)
    {
        // dd($exception);
        if ($exception instanceof ValidationException) {
            // $errors = $exception->validator->errors()->all();
            $errors = implode(' -- ', $exception->validator->errors()->all());
            return ApiResponse::error($errors);
            // return response()->json(['status' => 'error', 'message' =>  $errors , 'error' => $errors], 400);
        }

        if ($this->isDatabaseConnectionError($exception)) {
            return ApiResponse::error('Database connection error.');
            // return response()->json(['status' => 'error' , 'message' => 'Database connection error.','error' => 'Database Connection error'], 500);
        }

        if ($exception instanceof AuthenticationException) {
            return ApiResponse::error($exception->getMessage(), "", 401);
        }

        if ($exception instanceof \Exception) {
            return ApiResponse::error($exception->getMessage());
            // return response()->json(['status' => 'error' , 'message' => $exception->getMessage(), 'error' => $exception->getMessage()], 400);
        }
    }

    protected function isDatabaseConnectionError(Throwable $e)
    {
        return $e instanceof \PDOException || $e instanceof ConnectionException;
    }
}
