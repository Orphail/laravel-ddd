<?php

declare(strict_types=1);

namespace Src\Agenda\Company\Domain\Model\ValueObjects;

use Src\Common\Domain\Exceptions\RequiredException;
use Src\Common\Domain\ValueObject;

final class FiscalName extends ValueObject
{
    private string $name;

    public function __construct(?string $name)
    {
        if (!$name) {
            throw new RequiredException('razón social');
        }

        $this->name = $name;
    }

    public function __toString(): string
    {
        return $this->name;
    }

    public function jsonSerialize(): string
    {
        return $this->name;
    }
}