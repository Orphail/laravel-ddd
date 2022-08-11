<?php

declare(strict_types=1);

namespace Src\User\Domain\Model\ValueObject;

use Src\Common\Exceptions\RequiredException;

final class Name implements \JsonSerializable
{
    private string $name;

    public function __construct(?string $name)
    {

        if (!$name) {
            throw new RequiredException('nombre');
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