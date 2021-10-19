<?php

namespace App\Http\Controllers;

use App\Utils\ResponseUtil;
use Response;
use Illuminate\Http\Response as HttpResponse;


class AppBaseController extends Controller
{
    /**
     * @param mixed $data
     *
     * @return mixed
     */
    public function successResponse($data)
    {
        return Response::json(ResponseUtil::generateResponse(
            'SUCCESS',
            'Your request is successfully executed',
            $data
        ), HttpResponse::HTTP_OK);
    }

    /**
     * @param mixed $data
     *
     * @return array
     */
    public function recordNotFound($data): array
    {
        return Response::json(ResponseUtil::generateResponse(
            'RECORD_NOT_FOUND',
            'Record not found with specified criteria.',
            $data
        ), HttpResponse::HTTP_NOT_FOUND);
    }

    /**
     * @param mixed $data
     *
     * @return mixed
     */
    public function loginSuccess($data)
    {
        return Response::json(ResponseUtil::generateResponse(
            'SUCCESS',
            'Login Successful',
            $data
        ), HttpResponse::HTTP_OK);
    }

    /**
     * @param mixed $data
     *
     * @return array
     */
    public function changePasswordSuccess($data): array
    {
        return Response::json(ResponseUtil::generateResponse(
            'SUCCESS',
            $data,
            []
        ), HttpResponse::HTTP_OK);
    }
}
