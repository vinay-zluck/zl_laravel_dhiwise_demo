<?php

namespace App\Exceptions;

use App\Utils\ResponseUtil;
use Exception;
use Illuminate\Http\Response;

class ChangePasswordFailureException extends Exception
{
    public $data;
    public $code;
    
    public function __construct($data)
    {
        $error = ResponseUtil::generateResponse(
            'FAILURE',
            "Password cannot be changed due to $data",
            []
        );
        
        $this->data = $error;
        $this->code = Response::HTTP_INTERNAL_SERVER_ERROR; 
    }
}    
