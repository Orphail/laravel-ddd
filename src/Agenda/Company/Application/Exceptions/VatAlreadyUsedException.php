<?php

namespace Src\Agenda\Company\Application\Exceptions;

final class VatAlreadyUsedException extends \DomainException
{
    public function __construct()
    {
        parent::__construct('Vat is already used');
    }
}