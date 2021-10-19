<?php

namespace App\Exceptions;

use App\Utils\ResponseUtil;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;
use Spatie\Permission\Exceptions\UnauthorizedException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
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
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    /**
     * @param  Request  $request
     * @param  Throwable  $e
     *
     * @return JsonResponse|Response|\Symfony\Component\HttpFoundation\Response
     * @throws Throwable
     */
    public function render($request, Throwable $e)
    {
        \Log::error($e->getTraceAsString());
        if ($e instanceof ModelNotFoundException && $request->expectsJson()) {
            $modelName = class_basename($e->getModel());
            return response()->json(ResponseUtil::generateResponse(
                'RECORD_NOT_FOUND',
                'Record not found with specified criteria.',
                'Record not found with specified criteria.'
            ), Response::HTTP_NOT_FOUND);
        }

        if ($e instanceof ValidationException && $request->expectsJson()) {
            $firstError = collect($e->errors())->first();
            return response()->json(ResponseUtil::generateResponse(
                'VALIDATION_ERROR',
                'Invalid Data, Validation Failed',
                $firstError[0]
            ), Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        if ($e instanceof UnauthorizedException && $request->expectsJson()) {
            return response()->json(ResponseUtil::generateError(
                'UNAUTHORIZED',
                'User does not have the right permissions.',
                'User does not have the right permissions.',
            ), Response::HTTP_FORBIDDEN);
        }
        
        $status = $e->getCode() != 0 ? $e->getCode() : Response::HTTP_BAD_REQUEST;
        $data = isset($e->data) ? $e->data : [
            'STATUS' => Response::HTTP_BAD_REQUEST,
            'MESSAGE' => $e->getMessage(),
            'ERROR' => $e->getMessage()
        ];

        if ($request->expectsJson()) {
            return response()->json($data,$status);
        }

        return parent::render($request, $e);
    }
}
