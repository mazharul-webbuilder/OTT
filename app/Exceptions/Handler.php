<?php

namespace App\Exceptions;

use App\Traits\ResponseTrait;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    use ResponseTrait;
    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
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
     *
     * @return void
     */
    public function register()
    {
        // Customize API Exception response
        $this->renderable(function (\Spatie\Permission\Exceptions\UnauthorizedException $e, $request) {
            return response()->json([
                'status'    => false,
                'message'   => $e->getMessage(),
                'data'      => null,
                'errors'    => true
            ],401);

//            return $this->errorResponse(message: $e->getMessage(), data: null);
        });

        // Customize ValidationException response for API and Custom Request Class
        $this->renderable(function (ValidationException $exception){
            return response()->json([
                'status' => false,
                'message' => 'Validation Error',
                'data' => null,
                'errors' => $exception->errors()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        });

        // Customize Authorization validation for policy and gate
        $this->renderable(function (AccessDeniedHttpException  $exception){
            return response()->json([
                'status' => false,
                'message' => 'You are trying to action on someone else data',
                'data' => null,
                'errors' => $exception->getMessage()
            ], Response::HTTP_FORBIDDEN);
        });

//        $this->reportable(function (Throwable $e) {
//            //
//        });
    }
}
