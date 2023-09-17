<?php

declare(strict_types=1);

namespace Src\Agenda\User\Domain\Model\ValueObjects;

use Src\Common\Domain\Exceptions\RequiredException;
use Src\Common\Domain\ValueObject;

final class Role extends ValueObject
{
    private string $role;

    public function __construct(?string $role)
    {

        if (!$role) {
            throw new RequiredException('role');
        }

        $this->role = $role;
    }

    public function __toString(): string
    {
        return $this->role;
    }

    public function jsonSerialize(): string
    {
        return $this->role;
    }
}
