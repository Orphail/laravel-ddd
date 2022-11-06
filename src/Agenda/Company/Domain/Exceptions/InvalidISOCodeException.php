<?php

namespace Src\Agenda\Company\Domain\Exceptions;

final class InvalidISOCodeException extends \DomainException
{
    public function __construct()
    {
        parent::__construct(__('country must be a valid ISO code (2 digits)'));
    }
}