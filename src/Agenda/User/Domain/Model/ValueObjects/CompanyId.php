<?php

declare(strict_types=1);

namespace Src\Agenda\User\Domain\Model\ValueObjects;

final class CompanyId implements \JsonSerializable
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