<?php

namespace App\Exceptions;

use App\Utils\ResponseUtil;
use Exception;
use Illuminate\Http\Response;

class BadRequestException extends Exception
{
    public $data;
    public $code;

    public function __construct($data)
    {
        $error = ResponseUtil::generateResponse(
            'BAD_REQUEST',
            'The request cannot be fulfilled due to bad syntax',
            $data
        );
        
        $this->data = $error;
        $this->code = Response::HTTP_BAD_REQUEST;
    }
}    
