<?php

namespace Src\Common\Domain\Exceptions;

final class MaximumValueException extends \DomainException
{
    public function __construct($fieldName, $value)
    {
        parent::__construct(__("The maximum value for '$fieldName' is '$value"));
    }
}