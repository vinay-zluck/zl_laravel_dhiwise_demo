<?php

namespace App\Exceptions;

use App\Utils\ResponseUtil;
use Exception;
use Illuminate\Http\Response;

class FailureResponseException extends Exception
{
    public $data;
    public $code;
    
    public function __construct($data)
    {
        $error = ResponseUtil::generateResponse(
            'FAILURE',
            'Internal Server Error',
            $data
        );
        $this->data = $error;
        $this->code = Response::HTTP_INTERNAL_SERVER_ERROR;
    }
}    
