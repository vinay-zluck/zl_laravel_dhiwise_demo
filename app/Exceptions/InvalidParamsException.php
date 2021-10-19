<?php

namespace App\Exceptions;

use App\Utils\ResponseUtil;
use Exception;
use Illuminate\Http\Response;

class InvalidParamsException extends Exception
{
    public $data;
    public $code;
    
    public function __construct($data)
    {
        $error = ResponseUtil::generateResponse(
            'VALIDATION_ERROR',
            'Invalid values in parameters',
            $data
        );
        
        $this->data = $error;
        $this->code = Response::HTTP_UNPROCESSABLE_ENTITY;
    }
}    
