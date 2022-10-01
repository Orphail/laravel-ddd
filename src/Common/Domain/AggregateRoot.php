<?php

namespace Src\Common\Domain;

abstract class AggregateRoot
{
    abstract public function toArray(): array;

    public function jsonSerialize(): array
    {
        return $this->toArray();
    }
}