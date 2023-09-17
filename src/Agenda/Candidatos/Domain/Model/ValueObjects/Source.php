<?php

declare(strict_types=1);

namespace Src\Agenda\Candidatos\Domain\Model\ValueObjects;

use Src\Common\Domain\Exceptions\RequiredException;
use Src\Common\Domain\ValueObject;

final class Source extends ValueObject
{
    private string $source;

    public function __construct(?string $source)
    {
        if (!$source) {
            throw new RequiredException('source');
        }

        $this->source = $source;
    }

    public function __toString(): string
    {
        return $this->source;
    }

    public function jsonSerialize(): string
    {
        return $this->source;
    }
}
