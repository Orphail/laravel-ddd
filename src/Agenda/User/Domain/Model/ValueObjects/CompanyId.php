<?php

declare(strict_types=1);

namespace Src\Agenda\User\Domain\Model\ValueObjects;

final class CompanyId implements \JsonSerializable
{
    private ?int $companyId;

    public function __construct(?int $companyId)
    {
        $this->companyId = $companyId;
    }

    public function value(): ?int
    {
        return $this->companyId;
    }

    public function jsonSerialize(): ?int
    {
        return $this->value();
    }
}