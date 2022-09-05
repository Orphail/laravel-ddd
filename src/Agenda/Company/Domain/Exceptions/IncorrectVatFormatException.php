<?php

namespace Src\Agenda\Company\Domain\Exceptions;

class IncorrectVatFormatException extends \DomainException
{
    public function __construct()
    {
        parent::__construct('Vat must be valid');
    }
}