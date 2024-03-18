<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
// use App\Exceptions\ApiExceptionHandler as ExceptionHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use App\Exceptions\ApiExceptionHandler;
use Throwable;

class Handler extends ExceptionHandler
{

    use ApiExceptionHandler;
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });

    }

    // public function render($request, Throwable $e){
    //     if ($request->is('api/*')) {
    //         $retval = $this->render1($request,$exception);
    //     }else{
    //         $retval = parent::render($request, $e);
    //     }

    //     return $retval;
    // }

    public function render($request,Throwable $exception){

        if ($request->is('api/*')) {
            // if ($exception instanceof ValidationException) {
            //     $errors = $exception->validator->errors()->all();
            //     return response()->json(['message' => 'The given data was invalid.', 'errors' => $errors], 422);
            // }

            // if ($this->isDatabaseConnectionError($exception)) {
            //     return response()->json(['message' => 'Database connection error.','errors' => 'Database Connection error'], 500);
            // }

            return $this->handleApiException($request, $exception);
        }

        return parent::render($request, $exception);


    }

    protected function isDatabaseConnectionError(Throwable $e)
    {
        return $e instanceof \PDOException || $e instanceof \Illuminate\Database\ConnectionException;
    }

    // public function render($request, Throwable $exception)
    // {
    //     return $this->handleApiException($request, $exception);
    // }

    // protected function handleApiException($request, Throwable $e)
    // {
    //     if ($e instanceof ValidationException) {
    //         $errors = $e->validator->errors()->all();
    //         return response()->json(['message' => 'The given data was invalid.', 'errors' => $errors], 422);
    //     }

    //     return parent::render($request, $e);
    // }
}
