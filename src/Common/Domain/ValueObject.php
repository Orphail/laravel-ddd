<?php

declare(strict_types=1);

namespace Src\Common\Domain;

use JsonSerializable;

abstract class ValueObject implements JsonSerializable
{
    public function splitField(?string $field, $max_length = 80): array
    {
        return explode(PHP_EOL, wordwrap($field ? (string)$this->{$field} : $this->jsonSerialize(), $max_length, PHP_EOL));
    }

    public function equals(ValueObject $other): bool
    {
        return $this->jsonSerialize() === $other->jsonSerialize();
    }
}