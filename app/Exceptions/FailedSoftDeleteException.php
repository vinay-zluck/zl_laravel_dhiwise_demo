<?php

namespace App\Exceptions;

use App\Utils\ResponseUtil;
use Exception;
use Illuminate\Http\Response;

class FailedSoftDeleteException extends Exception
{
    public $data;
    public $code;
    
    public function __construct()
    {
        $error = ResponseUtil::generateResponse(
            'FAILURE',
            'Data can not be deleted due to internal server error',
            []
        );
        $this->data = $error;
        $this->code = Response::HTTP_INTERNAL_SERVER_ERROR;
    }
}    
