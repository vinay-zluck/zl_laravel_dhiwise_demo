<?php

namespace App\Exceptions;

use App\Utils\ResponseUtil;
use Exception;
use Illuminate\Http\Response;

class LoginFailedException extends Exception
{
    public $data;
    public $code;

    public function __construct($data)
    {
        $error = ResponseUtil::generateResponse(
            'BAD_REQUEST',
            'Login Failed',
            $data
        );

        $this->data = $error;
        $this->code = Response::HTTP_BAD_REQUEST;
    }
}    
