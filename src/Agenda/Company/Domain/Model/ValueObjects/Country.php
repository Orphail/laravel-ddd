<?php

declare(strict_types=1);

namespace Src\Agenda\Company\Domain\Model\ValueObjects;

use Src\Agenda\Company\Domain\Exceptions\InvalidISOCodeException;
use Src\Common\Domain\Exceptions\RequiredException;
use Src\Common\Domain\ValueObject;

final class Country extends ValueObject
{
    private string $value;

    public function __construct(?string $value)
    {
        if (!$value) {
            throw new RequiredException('country');
        }

        if (!$this->isValidISOCode($value)) {
            throw new InvalidISOCodeException();
        }

        $this->value = $value;
    }

    private function isValidISOCode(string $value): bool
    {
        return (bool)preg_match('/^[A-Z]{2}$/', $value);
    }

    public function __toString(): string
    {
        return $this->value;
    }

    public function jsonSerialize(): string
    {
        return $this->value;
    }
}