<?php

namespace Src\Agenda\Company\Domain\Exceptions;

class RequiredMainAddressException extends \DomainException
{
    public function __construct()
    {
        parent::__construct('Main address is required');
    }
}