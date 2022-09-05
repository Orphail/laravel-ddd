<?php

declare(strict_types=1);

namespace Src\Agenda\Company\Domain\Model\Entities;

use Src\Agenda\Company\Domain\Model\ValueObjects\Name;

class Department implements \JsonSerializable
{
    public function __construct(
        public ?int $id,
        public readonly Name $name,
        public readonly ?int $address_id = null,
        public readonly bool $is_active = true
    ) {}

    public function id(?int $newId = null): ?int
    {
        if ($newId) {
            $this->id = $newId;
        }
        return $this->id;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'address_id' => $this->address_id,
            'is_active' => $this->is_active
        ];
    }

    public function jsonSerialize(): array
    {
        return $this->toArray();
    }
}