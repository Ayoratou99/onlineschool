<?php

namespace Modules\Users\Exceptions;

use Exception;

class KeycloakAuthenticationException extends Exception
{
    /**
     * Create a new exception instance.
     */
    public function __construct(string $message = 'Invalid credentials or authentication failed.', int $code = 401)
    {
        parent::__construct($message, $code);
    }
}
