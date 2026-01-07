<?php

namespace Modules\Users\Exceptions;

use Exception;

class KeycloakConnectionException extends Exception
{
    /**
     * Create a new exception instance.
     */
    public function __construct(string $message = 'Unable to connect to Keycloak server.', int $code = 503)
    {
        parent::__construct($message, $code);
    }
}
