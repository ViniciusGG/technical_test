<?php

namespace App\Exceptions;

use App\Services\Api\ApiResponseService;
use ErrorException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Support\Facades\App;
use Throwable;

class Handler extends ExceptionHandler
{
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

    }


    public function render($request, Throwable $e)
    {
        if ($request->is('api/*')) {

            //QueryException and ErrorException
            if ($e instanceof QueryException || $e instanceof ErrorException) {
                $message = "Unfortunately, an internal server error has occurred. Please try again later..";
                $apiResponse = new ApiResponseService();
                return $apiResponse->errorResponse($message, [], 500);
            }
            if($e instanceof ModelNotFoundException) {
                $message = $e->getModel()::getModelLabel() . __('Not Found');
                $apiResponse = new ApiResponseService();
                return $apiResponse->errorResponse($message, [], 404);
            }

        }
        return parent::render($request, $e);
    }
}
