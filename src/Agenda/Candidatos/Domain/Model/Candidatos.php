<?php

namespace Src\Agenda\Candidatos\Domain\Model;

use Src\Agenda\Candidatos\Domain\Model\ValueObjects\CandidatoName;
use Src\Agenda\Candidatos\Domain\Model\ValueObjects\Source;
use Src\Common\Domain\AggregateRoot;

class Candidatos extends AggregateRoot implements \JsonSerializable
{
    public function __construct(
        public readonly ?int $id,
        public readonly CandidatoName $name,
        public readonly Source $source,
        public readonly ?int $owner,
        public readonly ?string $created_at,
        public readonly ?int $created_by,
    ) {}

    public function toArray(): array
    {
        return [
            // TODO Add properties\
            'id' => $this->id,
            'name' => $this->name,
            'source' => $this->source,
            'owner' => $this->owner,
            'created_at' => $this->created_at,
            'created_by' => $this->created_by,
        ];
    }

    public function jsonSerialize(): array
    {
        return $this->toArray();
    }
}
