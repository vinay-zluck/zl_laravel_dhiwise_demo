<?php

namespace App\Exceptions;

use App\Utils\ResponseUtil;
use Exception;
use Illuminate\Http\Response;

class DuplicateDataException extends Exception
{
    public $data;
    public $code;
    
    public function __construct($data)
    {
        $error = ResponseUtil::generateResponse(
            'VALIDATION_ERROR',
            'Data Duplication Found',
            $data
        );
        $this->data = $error;
        $this->code = Response::HTTP_UNPROCESSABLE_ENTITY;
    }
}    
