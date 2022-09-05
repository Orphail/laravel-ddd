<?php

namespace Src\Agenda\User\Domain\Exceptions;

class CompanyRequiredException extends \DomainException
{
    public function __construct()
    {
        parent::__construct('Company is required for non-admin users');
    }
}