<?php

namespace App\Exceptions;

use App\Utils\ResponseUtil;
use Exception;
use Illuminate\Http\Response;

class UsernamePasswordWrongException extends Exception
{
    public $data;
    public $code;
    
    public function __construct()
    {
        $error = ResponseUtil::generateResponse(
            'BAD_REQUEST',
            'username or password is wrong',
            []
        );
        $this->data = $error;
        $this->code = Response::HTTP_BAD_REQUEST;
    }
}    
