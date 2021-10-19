<?php

namespace App\Exceptions;

use App\Utils\ResponseUtil;
use Exception;
use Illuminate\Http\Response;

class ValidationException extends Exception
{
    public $data;
    public $code;
    
    public function __construct($data)
    {
        $error = ResponseUtil::generateResponse(
            'VALIDATION_ERROR',
            'Invalid Data, Validation Failed',
            $data
        );
        $this->data = $error;
        $this->code = Response::HTTP_UNPROCESSABLE_ENTITY;
    }
}    
