<?php

namespace Src\Common\Domain\Exceptions;

final class UnauthorizedUserException extends \Exception
{
    public function __construct(string $custom_message = '')
    {
        parent::__construct($custom_message ?: 'The user is not authorized to access this resource or perform this action');
    }
}