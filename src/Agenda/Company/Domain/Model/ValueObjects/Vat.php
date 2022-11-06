<?php

namespace Src\Agenda\Company\Domain\Model\ValueObjects;

use Src\Agenda\Company\Domain\Exceptions\IncorrectVatFormatException;
use Src\Common\Domain\Exceptions\RequiredException;
use Src\Common\Domain\ValueObject;

final class Vat extends ValueObject
{
    private string $vat;

    public function __construct(?string $vat)
    {
        if (!$vat) {
            throw new RequiredException('vat');
        }

        if (!preg_match('/([a-z]|[A-Z]|[0-9])[0-9]{7}([a-z]|[A-Z]|[0-9])/', $vat)) {
            throw new IncorrectVatFormatException();
        }

        $this->vat = $vat;
    }

    public function __toString(): string
    {
        return $this->vat;
    }

    public function jsonSerialize(): string
    {
        return $this->vat;
    }

}