<?php

declare(strict_types=1);

namespace Src\Agenda\User\Domain\Model\ValueObjects;

use Src\Common\Domain\Exceptions\RequiredException;
use Src\Common\Domain\ValueObject;

final class Username extends ValueObject
{
    private string $username;

    public function __construct(?string $username)
    {

        if (!$username) {
            throw new RequiredException('username');
        }

        $this->username = $username;
    }

    public function __toString(): string
    {
        return $this->username;
    }

    public function jsonSerialize(): string
    {
        return $this->username;
    }
}
