<?php

namespace Src\User\Domain\Exceptions;

final class IncorrectEmailFormatException extends \DomainException
{
    public function __construct()
    {
        parent::__construct('El campo debe ser un email válido');
    }
}