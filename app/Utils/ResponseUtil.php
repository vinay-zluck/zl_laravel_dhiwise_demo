<?php

namespace App\Utils;

class ResponseUtil
{
    public static function generateResponse($success, $message, $data)
    {
        return [
            'STATUS'  => $success,
            'MESSAGE' => $message,
            'DATA'    => $data,
        ];
    }

    /**
     * @param string $message
     * @param array $data
     *
     * @return array
     */
    public static function generateError($error, $message, $data)
    {
        return [
            'STATUS'  => $error,
            'MESSAGE' => $message,
            'ERROR'   => $data,
        ];
    }
}
