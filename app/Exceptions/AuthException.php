<?php

namespace App\Exceptions;

use Exception;

class AuthException extends Exception
{
    public function render()
    {
        return response()->json(
            [
                'error' => true,
                'message' => $this->getMessage() ?: 'Error de autenticación.'
            ],
            401
        );
    }
}
