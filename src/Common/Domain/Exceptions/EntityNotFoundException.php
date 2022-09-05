<?php

namespace Src\Common\Domain\Exceptions;

class EntityNotFoundException extends \DomainException
{
    public function __construct(string $message = 'Entity not found')
    {
        parent::__construct($message);
    }
}