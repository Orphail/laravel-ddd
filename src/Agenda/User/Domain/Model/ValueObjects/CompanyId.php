<?php

declare(strict_types=1);

namespace Src\Agenda\User\Domain\Model\ValueObjects;

use Src\Common\Domain\ValueObject;

final class CompanyId extends ValueObject
{
    public readonly ?int $value;

    public function __construct(?int $companyId)
    {
        $this->value = $companyId;
    }

    public function jsonSerialize(): ?int
    {
        return $this->value;
    }
}