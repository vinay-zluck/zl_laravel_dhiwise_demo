<?php

namespace App\Exceptions;

use App\Utils\ResponseUtil;
use Exception;
use Illuminate\Http\Response;

class LoginUnAuthorizeException extends Exception
{
    public $data;
    public $code;
    
    public function __construct($data)
    {
        $error = ResponseUtil::generateError(
            'UNAUTHORIZED',
            'You are not authorized to access the request',
            $data
        );
        $this->data = $error;
        $this->code = Response::HTTP_UNAUTHORIZED;
    }
}    
