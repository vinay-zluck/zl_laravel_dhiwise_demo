<?php

namespace App\Exceptions;

use App\Utils\ResponseUtil;
use Exception;
use Illuminate\Http\Response;

class InsufficientParametersException extends Exception
{
    public $data;
    public $code;
    
    public function __construct()
    {
        $error = ResponseUtil::generateResponse(
            'BAD_REQUEST',
            'Insufficient parameters',
            []
        );
        $this->data = $error;
        $this->code = Response::HTTP_BAD_REQUEST;
    }
}    
