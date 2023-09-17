<?php

declare(strict_types=1);

namespace Src\Agenda\Candidatos\Domain\Model\ValueObjects;

use Src\Common\Domain\Exceptions\RequiredException;
use Src\Common\Domain\ValueObject;

final class CandidatoName extends ValueObject
{
    private string $candidatoName;

    public function __construct(?string $candidatoName)
    {
        if (!$candidatoName) {
            throw new RequiredException('CandidatoName');
        }

        $this->candidatoName = $candidatoName;
    }

    public function __toString(): string
    {
        return $this->candidatoName;
    }

    public function jsonSerialize(): string
    {
        return $this->candidatoName;
    }
}
